<?php

namespace App\Services;

use App\Models\DailyProgress;
use App\Models\ProjectBoq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ProgressWorkflow
{
    public function save(Request $request, ?int $id = null): DailyProgress
    {
        $project = app(ProjectContext::class)->id();
        $request->validate([
            'dprogress_date' => 'required|date_format:Y-m-d|before_or_equal:today',
            'dprogress_project' => ['required', Rule::in([$project])],
            'dprogress_category' => 'required|integer|exists:category,category_id',
            'dprogress_description' => 'required|string|max:255',
            'dprogress_boq' => ['required', 'integer', Rule::exists('project_boqs', 'pboq_id')->where('pboq_project', $project)],
            'dprogress_qty' => 'required|numeric|min:0|max:999999999999|decimal:0,4',
            'dprogress_location' => 'required|string|max:255',
            'dprogress_subcontractor' => 'required|integer|exists:subcontractors,subcontractor_id',
            'dprogress_incharge' => 'required|integer|exists:users,user_id',
            'dprogress_remarks' => 'nullable|string|max:5000', 'dprogress_cforward' => 'nullable|in:1,2',
            'revision' => $id ? 'required|integer|min:1' : 'nullable|integer',
            'correction_note' => $id ? 'required|string|max:2000' : 'nullable|string|max:2000',
            'photos' => 'nullable|array|max:5', 'photos.*' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);
        $stored = [];
        try {
            return DB::transaction(function () use ($request, $id, $project, &$stored) {
                $old = $id ? DailyProgress::whereKey($id)->lockForUpdate()->firstOrFail() : null;
                $review = $id ? DB::table('progress_reviews')->where('progress_id', $id)->lockForUpdate()->first() : null;
                if ($id && (int) $request->revision !== (int) ($review->revision ?? 1)) {
                    throw ValidationException::withMessages(['revision' => 'This entry changed. Reload it before saving your correction.']);
                }
                $revision = $id ? ($review->revision ?? 1) + 1 : 1;
                $fields = $request->only(['dprogress_date', 'dprogress_project', 'dprogress_category',
                    'dprogress_description', 'dprogress_boq', 'dprogress_qty', 'dprogress_location',
                    'dprogress_subcontractor', 'dprogress_incharge', 'dprogress_remarks']);
                $boq = ProjectBoq::whereKey($fields['dprogress_boq'])->firstOrFail();
                $fields['dprogress_pdone'] = (float) $boq->pboq_qty > 0
                    ? round((float) $fields['dprogress_qty'] / (float) $boq->pboq_qty * 100, 2) : 0;
                $fields['dprogress_cforward'] = $request->input('dprogress_cforward', 2);
                $fields['dprogress_updated_on'] = now(); $fields['dprogress_updated_by'] = Auth::id();
                $entry = $old ?? new DailyProgress;
                $before = $old?->getOriginal();
                if (!$id) {
                    $fields += ['dprogress_added_on' => now(), 'dprogress_added_by' => Auth::id(),
                        'dprogress_status' => 5, 'dprogress_trash' => 1];
                }
                $entry->fill($fields)->save();
                DB::table('progress_reviews')->updateOrInsert(['progress_id' => $entry->dprogress_id], [
                    'state' => 'pending', 'approved_qty' => 0, 'revision' => $revision,
                    'reviewer_id' => null, 'reviewed_at' => null, 'note' => null,
                ]);
                foreach ($request->file('photos', []) as $photo) {
                    $path = 'progress-evidence/'.\Illuminate\Support\Str::random(40).'.jpg';
                    Storage::disk('evidence')->put($path, app(PrivateUploads::class)->imageBytes($photo->getRealPath())); $stored[] = $path;
                    DB::table('progress_photos')->insert(['progress_id' => $entry->dprogress_id,
                        'path' => $path, 'mime' => 'image/jpeg', 'uploaded_by' => Auth::id(), 'uploaded_at' => now()]);
                }
                $this->event($entry->dprogress_id, $id ? 'corrected' : 'submitted', $revision,
                    $before ? ['entry' => $before, 'review' => $review] : null,
                    ['entry' => $entry->toArray(), 'state' => 'pending', 'new_photo_paths' => $stored], $request->input('correction_note'));
                return $entry;
            });
        } catch (\Throwable $e) {
            foreach ($stored as $path) { Storage::disk('evidence')->delete($path); }
            throw $e;
        }
    }

    public function review(Request $request, int $id): void
    {
        abort_unless(WorkflowAccess::can('user.review_progress'), 403);
        $request->validate(['decision' => 'required|in:approved,rejected',
            'approved_qty' => 'required_if:decision,approved|nullable|numeric|min:0|max:999999999999|decimal:0,4',
            'revision' => 'required|integer|min:1', 'note' => 'required|string|max:2000']);
        DB::transaction(function () use ($request, $id) {
            $entry = DailyProgress::whereKey($id)->lockForUpdate()->firstOrFail();
            // A shared BOQ lock serializes reviews for this item so cumulative figures stay consistent.
            ProjectBoq::whereKey($entry->dprogress_boq)->lockForUpdate()->firstOrFail();
            $review = DB::table('progress_reviews')->where('progress_id', $id)->lockForUpdate()->first();
            abort_unless($review, 409, 'Reload the entry before reviewing it.');
            if ((int) $request->revision !== (int) $review->revision || !in_array($review->state, ['pending', 'legacy'], true)) {
                throw ValidationException::withMessages(['revision' => 'This revision has already changed or been reviewed. Reload the entry.']);
            }
            abort_if((int) $entry->dprogress_added_by === (int) Auth::id()
                || (int) $entry->dprogress_updated_by === (int) Auth::id(), 403,
                'Another authorised reviewer must verify your submission or correction.');
            $quantity = $request->decision === 'approved' ? (float) $request->approved_qty : 0;
            if ($quantity > (float) $entry->dprogress_qty) {
                throw ValidationException::withMessages(['approved_qty' => 'Approved quantity cannot exceed this submission’s reported quantity.']);
            }
            $update = ['state' => $request->decision, 'approved_qty' => $quantity,
                'reviewer_id' => Auth::id(), 'reviewed_at' => now(), 'note' => $request->note];
            DB::table('progress_reviews')->where('progress_id', $id)->update($update);
            $this->event($id, $request->decision, $review->revision, (array) $review, $update, $request->note);
        });
    }

    public function changeWorkStatus(Request $request): void
    {
        $request->validate(['dprogress_id' => 'required|integer', 'status' => 'required|integer|between:1,5']);
        DB::transaction(function () use ($request) {
            $entry = DailyProgress::whereKey($request->dprogress_id)->lockForUpdate()->firstOrFail();
            $before = $entry->toArray();
            $review = DB::table('progress_reviews')->where('progress_id', $entry->dprogress_id)->lockForUpdate()->first();
            $revision = ($review->revision ?? 1) + 1;
            $entry->dprogress_status = $request->status;
            $entry->dprogress_updated_by = Auth::id(); $entry->dprogress_updated_on = now(); $entry->save();
            DB::table('progress_reviews')->updateOrInsert(['progress_id' => $entry->dprogress_id], [
                'state' => 'pending', 'approved_qty' => 0, 'revision' => $revision,
                'reviewer_id' => null, 'reviewed_at' => null, 'note' => null,
            ]);
            $this->event($entry->dprogress_id, 'work_status_changed', $revision,
                ['entry' => $before, 'review' => $review], ['entry' => $entry->toArray(), 'state' => 'pending']);
        });
    }

    public function event(int $id, string $action, int $revision, ?array $before, array $after, ?string $note = null): void
    {
        DB::table('progress_events')->insert(['progress_id' => $id, 'actor_id' => Auth::id(),
            'action' => $action, 'revision' => $revision,
            'before_data' => $before ? json_encode($before, JSON_THROW_ON_ERROR) : null,
            'after_data' => json_encode($after, JSON_THROW_ON_ERROR), 'note' => $note, 'created_at' => now()]);
    }
    public static function publicSnapshot(mixed $snapshot): mixed
    {
        if (!is_array($snapshot)) { return $snapshot; }
        $allowed = ['entry','review','state','revision','approved_qty','note','dprogress_date','dprogress_description','dprogress_qty','dprogress_location','dprogress_remarks','dprogress_cforward','dprogress_status'];
        $out = [];
        foreach ($snapshot as $key => $value) { if (in_array($key, $allowed, true)) { $out[$key] = is_array($value) ? self::publicSnapshot($value) : $value; } }
        return $out;
    }

}
