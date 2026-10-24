@extends('user.layouts.app')
@section('title', 'Projects Directory - '.Request()->settings->settings_title)
@section('contents')
<div class="content">
    <div class="st-page-intro d-flex flex-wrap justify-content-between gap-3"><div><span class="st-eyebrow">GLOBAL OVERVIEW</span><h1>Projects Directory</h1><p>Choose a project to open its daily work, progress, materials and labour workspace.</p></div>
        @if(Helper::userHasPermission('user.add_project'))<a class="btn btn-primary align-self-start" href="{{ route('user.add_project') }}"><i class="ti ti-plus" aria-hidden="true"></i> Add project</a>@endif
    </div>
    <form method="GET" action="{{ route('user.projects') }}" class="st-directory-search mb-4"><label class="visually-hidden" for="project-search">Find a project</label><input id="project-search" name="search" type="search" class="form-control" placeholder="Find a project…" value="{{ request('search') }}"><button class="btn btn-white">Search</button></form>
    <div class="st-project-grid">
        @forelse($projects as $project)
        @php
            $stats = $boqStats[$project->project_id] ?? null;
            $budget = (float) ($stats->budget ?? 0);
            $reported = $budget > 0 ? round(($reportedValue[$project->project_id] ?? 0) / $budget * 100, 1) : 0;
            $approved = $budget > 0 ? round(($approvedValue[$project->project_id] ?? 0) / $budget * 100, 1) : 0;
        @endphp
        <article class="card st-project-card"><div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-4"><span class="st-project-icon"><i class="ti ti-building" aria-hidden="true"></i></span><span class="badge badge-soft-warning">{{ [1 => 'Active', 2 => 'On hold', 3 => 'Completed'][$project->project_status] ?? 'Archived' }}</span></div>
            <h4>{{ $project->project_name }}</h4><p class="text-muted mb-1">{{ $project->client?->client_name ?? 'Client not assigned' }}</p><p class="text-muted">{{ $project->project_location }}</p>
            <div class="st-progress-label"><span>Approved progress</span><strong>{{ $approved }}%</strong></div><div class="st-project-progress" role="progressbar" aria-label="Approved progress" aria-valuenow="{{ $approved }}" aria-valuemin="0" aria-valuemax="{{ max(100, $approved) }}"><span style="width:{{ min(100, max(0, $approved)) }}%"></span></div>
            <p class="st-reported-summary">Reported: {{ $reported }}% @if($reported > 100)<span class="badge badge-soft-warning">Exceeds BOQ</span>@endif</p>
            <div class="st-project-meta"><span>BOQ ITEMS<strong>{{ $stats->items ?? 0 }}</strong></span><span>SITE ENGINEER<strong>{{ $project->engineer?->user_name ?? 'Not assigned' }}</strong></span></div>
            @if(Helper::userHasPermission('user.view_project'))<form action="{{ route('user.select_project') }}" method="POST">@csrf<input type="hidden" name="project_id" value="{{ $project->project_id }}"><button class="btn btn-primary w-100" type="submit">Open project dashboard <i class="ti ti-arrow-right" aria-hidden="true"></i></button></form>@endif
            @if(Helper::userHasPermission('user.edit_project'))<a class="st-project-edit" href="{{ route('user.edit_project', $project->project_id) }}">Edit project details</a>@endif
        </div></article>
        @empty<div class="card"><div class="card-body st-empty"><h5>No projects found</h5><p>Try another search or add your first project.</p></div></div>@endforelse
    </div>
    <div class="mt-4">{{ $projects->links('pagination::bootstrap-5') }}</div>
</div>
@endsection
