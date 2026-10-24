<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DailyProgress;
use App\Services\ProgressWorkflow;
use App\Services\WorkflowSchema;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Helper;

class ProgressReviewController extends Controller
{
    public function show(int $id, WorkflowSchema $schema)
    {
        abort_unless(Helper::userHasPermission('user.daily_progress'), 403);
        if (!$schema->ready()) { return response()->view('user.workspace.setup', ['set' => 'daily_progress'], 503); }
        $entry = DailyProgress::with(['boq', 'project'])->findOrFail($id);
        $review = DB::table('progress_reviews')->where('progress_id', $id)->first();
        $photos = DB::table('progress_photos')->where('progress_id', $id)->orderBy('uploaded_at')->get();
        $events = DB::table('progress_events as e')->leftJoin('users as u', 'u.user_id', '=', 'e.actor_id')
            ->where('progress_id', $id)->select('e.*', 'u.user_name')->orderByDesc('e.id')->get();
        $reported = DailyProgress::where('dprogress_boq', $entry->dprogress_boq)->where('dprogress_trash', 1)->sum('dprogress_qty');
        $approved = DB::table('progress_reviews as r')->join('daily_progress as p', 'p.dprogress_id', '=', 'r.progress_id')
            ->where('p.dprogress_project', $entry->dprogress_project)->where('p.dprogress_boq', $entry->dprogress_boq)
            ->where('p.dprogress_trash', 1)->where('r.state', 'approved')->sum('r.approved_qty');
        return view('user.progress.review', compact('entry', 'review', 'photos', 'events', 'reported', 'approved') + ['set' => 'daily_progress']);
    }

    public function review(Request $request, int $id, ProgressWorkflow $workflow, WorkflowSchema $schema)
    {
        abort_unless(Helper::userHasPermission('user.daily_progress'), 403);
        abort_unless($schema->ready(), 503, 'The site workflow database update is required.');
        $workflow->review($request, $id);
        return redirect()->route('user.progress_review', $id)->with('success', 'Review recorded. The approval history has been preserved.');
    }

    public function photo(int $id, int $photo, WorkflowSchema $schema)
    {
        abort_unless(Helper::userHasPermission('user.daily_progress'), 403);
        abort_unless($schema->ready(), 503);
        DailyProgress::findOrFail($id);
        $file = DB::table('progress_photos')->where('progress_id', $id)->where('id', $photo)->first();
        abort_unless($file && Storage::disk('evidence')->exists($file->path), 404);
        return Storage::disk('evidence')->response($file->path, null,
            ['Content-Type' => $file->mime, 'Cache-Control' => 'private, no-store', 'X-Content-Type-Options' => 'nosniff']);
    }
}
