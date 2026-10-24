@extends('user.layouts.app')
@section('title', $project->project_name.' - '.Request()->settings->settings_title)
@section('contents')
<div class="content">
    <div class="st-page-intro"><span class="st-eyebrow">PROJECT WORKSPACE</span><h1>{{ $project->project_name }}</h1><p>{{ $project->project_location }} · {{ $project->client?->client_name ?? 'Client not assigned' }}</p></div>
    @php
        $links = [
            ['user.daily_works', 'ti-calendar', 'Daily work reports', 'Plan and record the day’s work.'],
            ['user.daily_progress', 'ti-chart-bar', 'Daily progress reports', 'Reported quantities, photos and engineer verification.'],
            ['user.boq_tracker', 'ti-list-check', 'BOQ tracker', 'Work quantities, rates and remaining scope.'],
            ['user.site_materials', 'ti-package', 'Site materials', 'Receipts, usage, returns and current site stock.'],
            ['user.labour_trackers', 'ti-users-group', 'Labour attendance', 'Trade-wise crew attendance, wages and overtime.'],
            ['user.labour_payments', 'ti-wallet', 'Labour payments', 'Weekly wages, advances and outstanding balances.'],
            ['user.indents', 'ti-clipboard', 'Material requests', 'Track site indents and quotations.'],
            ['user.issues', 'ti-alert-triangle', 'Site issues', 'Track issues and follow up on resolution.'],
        ];
    @endphp
    <div class="st-workspace-grid mb-4">
    @foreach($links as [$route, $icon, $label, $description])
        @php $allowed = match($route) {
            'user.site_materials' => Helper::userHasPermission('user.assign_materials'),
            'user.labour_payments' => \App\Services\WorkflowAccess::can($route),
            default => Helper::userHasPermission($route),
        }; @endphp
        @if($allowed)<a href="{{ route($route) }}" class="card st-workspace-link"><div class="card-body"><i class="ti {{ $icon }}" aria-hidden="true"></i><h5>{{ $label }}</h5><p>{{ $description }}</p><span>Open <i class="ti ti-arrow-up-right" aria-hidden="true"></i></span></div></a>@endif
    @endforeach
    </div>
    <div class="d-flex flex-wrap gap-2 mb-4">
        @if(Helper::userHasPermission('user.vendor_ledger'))<a class="btn btn-white" href="{{ route('user.vendor_ledger', ['project' => $project->project_id]) }}">Project vendor expenses</a>@endif
        @if(Helper::userHasPermission('user.subcontractor_ledger'))<a class="btn btn-white" href="{{ route('user.subcontractor_ledger', ['project' => $project->project_id]) }}">Project subcontractor expenses</a>@endif
        @if(\App\Services\WorkflowAccess::can('user.reports'))<a class="btn btn-primary" href="{{ route('user.reports', ['project' => $project->project_id]) }}">Project reports</a>@endif
    </div>
    <div class="card"><div class="card-header"><h5 class="mb-1">Reported and approved BOQ quantities</h5><p class="mb-0 text-muted">Only reviewed quantities count as approved. Earlier entries remain unverified until an engineer reviews them.</p></div><div class="card-body"><div class="table-responsive"><table class="table mb-0">
        <thead><tr><th>Work item</th><th>Unit</th><th>BOQ quantity</th><th>Reported</th><th>Approved</th><th>Remaining approved scope</th><th>Check</th></tr></thead><tbody>
        @forelse($boqs as $boq)
            @php $r = (float) ($reported[$boq->pboq_id] ?? 0); $a = (float) ($approved[$boq->pboq_id] ?? 0); @endphp
            <tr><td>{{ $boq->pboq_item }}</td><td>{{ $boq->pboq_unit }}</td><td>{{ number_format($boq->pboq_qty, 4) }}</td><td>{{ number_format($r, 4) }}</td><td>{{ number_format($a, 4) }}</td><td>{{ number_format($boq->pboq_qty - $a, 4) }}</td><td>@if($r > (float) $boq->pboq_qty || $a > (float) $boq->pboq_qty)<span class="badge badge-soft-warning">Exceeds BOQ</span>@else Within BOQ @endif</td></tr>
        @empty<tr><td colspan="7">No BOQ items have been recorded for this project.</td></tr>@endforelse
        </tbody></table></div></div></div>
</div>
@endsection
