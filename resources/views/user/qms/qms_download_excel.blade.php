@php
$boqs = App\Models\ProjectBoq::where('pboq_project', $project->project_id)->get();
$boqTotal = $boqs->sum('pboq_amount');
$progressRows = App\Models\DailyProgress::where('dprogress_project', $project->project_id)->where('dprogress_trash', 1)->get();
$progressByBoq = $progressRows->groupBy('dprogress_boq');
$doneValue = 0;
$carryForwardCount = $progressRows->where('dprogress_cforward', 1)->count();
$dprEntries = $progressRows->count();
$labourTrackerIds = App\Models\LabourTracker::where('ltracker_project', $project->project_id)->where('ltracker_trash', 1)->pluck('ltracker_id');
$labourDays = App\Models\LabourWorker::whereIn('lworker_tracker', $labourTrackerIds)->get()->sum(function($worker) {
return (float) $worker->lworker_male + (float) $worker->lworker_female;
});
$issues = App\Models\Issue::with(['category', 'engineer'])->where('issue_project', $project->project_id)->where('issue_status', 1)->get();
$openIssuesCount = $issues->count();

foreach($boqs as $boq) {
$completedQty = optional($progressByBoq->get($boq->pboq_id))->sum('dprogress_qty') ?? 0;
$doneValue += $completedQty * $boq->pboq_rate;
}

$overallProgress = $boqTotal > 0 ? round(($doneValue / $boqTotal) * 100) : 0;
$overallProgress = max(0, min(100, $overallProgress));
$preparedBy = optional($project->engineer)->user_name ?? (auth()->user()->user_name ?? 'Site Engineer');
$priorityLabels = [1 => 'Low', 2 => 'Medium', 3 => 'High', 4 => 'Critical'];
@endphp
<table class="table table-bordered">
    <thead>
        <tr>
            <th>S.No</th>
            <th>Field</th>
            <th>Value</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>Project</td>
            <td>{{ $project->project_name }}</td>
        </tr>
        <tr>
            <td>2</td>
            <td>Location</td>
            <td>{{ $project->project_location }}</td>
        </tr>
        <tr>
            <td>3</td>
            <td>Client</td>
            <td>{{ $project->client->client_name }}</td>
        </tr>
        <tr>
            <td>4</td>
            <td>BOQ Progress</td>
            <td>{{ $overallProgress }}%</td>
        </tr>
        <tr>
            <td>5</td>
            <td>Work Done Value</td>
            <td>&#8377;{{ Helper::moneyFormat($doneValue) }}</td>
        </tr>
        <tr>
            <td>6</td>
            <td>DPR Entries</td>
            <td>{{ $dprEntries }}</td>
        </tr>
        <tr>
            <td>7</td>
            <td>Total Labour Days</td>
            <td>{{ $labourDays }}</td>
        </tr>
        <tr>
            <td>8</td>
            <td>Open Issues</td>
            <td>{{ $openIssuesCount }}</td>
        </tr>
        <tr>
            <td>9</td>
            <td>Carry Forward Items</td>
            <td>{{ $carryForwardCount }}</td>
        </tr>
    </tbody>
</table>
