<?php

namespace App\Services;

use App\Models\LabourTracker;
use App\Models\LabourWorker;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class LabourAttendanceService
{
    public function save(Request $request, ?int $id = null): LabourTracker
    {
        $request->validate([
            'ltracker_date' => 'required|date_format:Y-m-d|before_or_equal:today',
            'ltracker_project' => ['required', Rule::in([app(ProjectContext::class)->id()])],
            'ltracker_subcontractor' => 'required|integer|exists:subcontractors,subcontractor_id',
            'ltracker_incharge' => 'required|integer|exists:users,user_id',
            'ltracker_shift' => 'required|in:1,2', 'ltracker_in' => 'required|date_format:H:i',
            'ltracker_out' => 'required|date_format:H:i', 'ltracker_remarks' => 'nullable|string|max:5000',
            'workers' => 'required|array', 'workers.CATEGORY' => 'required|array|min:1|max:100',
            'workers.SKILL' => 'required|array', 'workers.MALE' => 'required|array',
            'workers.FEMALE' => 'required|array', 'workers.WAGE' => 'required|array',
            'workers.OVERTIME_HOURS' => 'nullable|array', 'workers.OVERTIME_RATE' => 'nullable|array',
            'workers.CATEGORY.*' => 'required|integer|exists:category,category_id',
            'workers.SKILL.*' => 'required|in:1,2,3',
            'workers.MALE.*' => 'required|integer|min:0|max:10000',
            'workers.FEMALE.*' => 'required|integer|min:0|max:10000',
            'workers.WAGE.*' => 'required|numeric|min:0|max:9999999|decimal:0,2',
            'workers.OVERTIME_HOURS.*' => 'nullable|numeric|min:0|max:24|decimal:0,2',
            'workers.OVERTIME_RATE.*' => 'nullable|numeric|min:0|max:9999999|decimal:0,2',
        ]);
        $workers = $request->workers;
        $keys = array_keys($workers['CATEGORY']);
        foreach (['SKILL', 'MALE', 'FEMALE', 'WAGE'] as $field) {
            if (!isset($workers[$field]) || array_keys($workers[$field]) !== $keys) {
                throw ValidationException::withMessages(['workers' => 'Complete every attendance row.']);
            }
        }
        $rows = []; $totalCents = 0;
        foreach ($keys as $key) {
            $male = (int) $workers['MALE'][$key]; $female = (int) $workers['FEMALE'][$key];
            $hours = (string) ($workers['OVERTIME_HOURS'][$key] ?? 0);
            $rate = (string) ($workers['OVERTIME_RATE'][$key] ?? 0);
            if ($male + $female === 0 || ((float) $hours > 0 && (float) $rate <= 0)) {
                throw ValidationException::withMessages(['workers' => 'Each row needs attending workers and an hourly rate when overtime is recorded.']);
            }
            $cost = WageCalculator::calculate($male, $female, (string) $workers['WAGE'][$key], $hours, $rate);
            $totalCents += (int) round($cost['total'] * 100);
            $rows[] = ['lworker_category' => $workers['CATEGORY'][$key], 'lworker_skill' => $workers['SKILL'][$key],
                'lworker_male' => $male, 'lworker_female' => $female, 'lworker_wage' => $workers['WAGE'][$key],
                'lworker_overtime_hours' => $hours, 'lworker_overtime_rate' => $rate, 'lworker_total' => $cost['total']];
        }
        $in = CarbonImmutable::parse($request->ltracker_date.' '.$request->ltracker_in);
        if ($totalCents > 99999999999999) {
            throw ValidationException::withMessages(['workers' => 'The calculated attendance cost exceeds the supported amount.']);
        }
        $out = CarbonImmutable::parse($request->ltracker_date.' '.$request->ltracker_out);
        if ($out->lessThan($in)) { $out = $out->addDay(); }
        if ($in->equalTo($out)) {
            throw ValidationException::withMessages(['ltracker_out' => 'Out time must differ from in time.']);
        }
        foreach ($rows as $row) {
            if ((float) $row['lworker_overtime_hours'] > $out->diffInMinutes($in) / 60) {
                throw ValidationException::withMessages(['workers' => 'Overtime hours cannot exceed the recorded shift duration.']);
            }
        }
        return DB::transaction(function () use ($request, $id, $rows, $totalCents, $in, $out) {
            $tracker = $id ? LabourTracker::whereKey($id)->lockForUpdate()->firstOrFail() : new LabourTracker;
            $data = $request->only(['ltracker_date', 'ltracker_project', 'ltracker_subcontractor',
                'ltracker_incharge', 'ltracker_shift', 'ltracker_in', 'ltracker_out', 'ltracker_remarks']);
            $data += ['ltracker_thours' => round($out->diffInMinutes($in) / 60, 2),
                'ltracker_total' => $totalCents / 100, 'ltracker_updated_on' => now(), 'ltracker_updated_by' => Auth::id()];
            if (!$id) { $data += ['ltracker_added_on' => now(), 'ltracker_added_by' => Auth::id(), 'ltracker_status' => 1, 'ltracker_trash' => 1]; }
            $tracker->fill($data)->save();
            LabourWorker::where('lworker_tracker', $tracker->ltracker_id)->delete();
            foreach ($rows as $row) {
                DB::table('labour_workers')->insert($row + ['lworker_tracker' => $tracker->ltracker_id,
                    'lworker_added_on' => now(), 'lworker_added_by' => Auth::id(),
                    'lworker_updated_on' => now(), 'lworker_updated_by' => Auth::id(), 'lworker_status' => 1]);
            }
            return $tracker;
        });
    }
}
