@extends('user.layouts.app')
@section('title', 'Dashboard - ' . Request()->settings->settings_title)
@section('custom_style')
<style>
.dashboard-summary-link {
   display: block;
   color: inherit;
   text-decoration: none;
}

.dashboard-summary-link:hover {
   color: inherit;
}
.boq-summary-header {
   display: flex;
   align-items: center;
   justify-content: space-between;
   gap: 1rem;
}

.boq-project-title {
   font-size: 1rem;
   font-weight: 700;
   color: var(--gray-900);
   margin-bottom: .15rem;
}

.boq-project-meta {
   color: var(--body-color);
   font-size: .8125rem;
   line-height: 1.2;
}

.boq-total-wrap {
   display: flex;
   align-items: center;
   gap: .75rem;
   margin-left: auto;
   min-width: 0;
}

.boq-total-text {
   color: #ff7a1a;
   font-size: .875rem;
   font-weight: 700;
   white-space: nowrap;
   text-align: right;
}

.boq-total-label {
   display: block;
   color: var(--body-color);
   font-size: .625rem;
   font-weight: 700;
   letter-spacing: .04em;
   line-height: 1.1;
   text-transform: uppercase;
}

.boq-total-progress {
   width: 140px;
   height: 10px;
   overflow: hidden;
   border-radius: 999px;
   background: #24304f;
   flex: 0 0 140px;
}

.boq-total-progress span {
   display: block;
   width: var(--boq-progress, 0%);
   height: 100%;
   border-radius: inherit;
   background: #ffc221;
}

.boq-total-percent {
   color: #ffc221;
   font-size: 1.5rem;
   line-height: 1;
   font-weight: 800;
   white-space: nowrap;
}

.boq-row-progress-wrap {
   display: inline-flex;
   align-items: center;
   gap: .5rem;
   min-width: 125px;
}

.boq-row-progress {
   width: 80px;
   height: 8px;
   overflow: hidden;
   border-radius: 999px;
   background: #24304f;
   flex: 0 0 80px;
}

.boq-row-progress span {
   display: block;
   width: var(--boq-row-progress, 0%);
   height: 100%;
   border-radius: inherit;
   background: var(--boq-row-progress-color, #ffc221);
}

.boq-row-progress-percent {
   color: var(--boq-row-progress-color, #ffc221);
   font-size: .8125rem;
   font-weight: 800;
   white-space: nowrap;
}

.boq-project-filter {
   width: 320px;
   max-width: 100%;
}

.boq-project-filter .select2-container {
   width: 100% !important;
}

.boq-extra-row > * {
   background-color: #fff3cd;
}

.boq-extra-row > :first-child {
   border-left: 4px solid #ff9f1a;
}

.boq-extra-row .ti-star-filled {
   color: #ff9f1a;
}

.dashboard-project-progress-item {
   padding-bottom: .875rem;
   margin-bottom: .875rem;
   border-bottom: 1px solid var(--border-color);
}

.dashboard-project-progress-item:last-child {
   padding-bottom: 0;
   margin-bottom: 0;
   border-bottom: 0;
}

.dashboard-project-progress-head {
   display: flex;
   align-items: flex-end;
   justify-content: space-between;
   gap: 1rem;
   margin-bottom: .45rem;
}

.dashboard-project-progress-percent {
   color: var(--dashboard-project-progress-color, #ffc221);
   font-size: 1.35rem;
   line-height: 1;
   font-weight: 800;
   white-space: nowrap;
}

.dashboard-project-progress-track {
   width: 100%;
   height: 8px;
   overflow: hidden;
   border-radius: 999px;
   background: #24304f;
}

.dashboard-project-progress-track span {
   display: block;
   width: var(--dashboard-project-progress, 0%);
   height: 100%;
   border-radius: inherit;
   background: var(--dashboard-project-progress-color, #ffc221);
}

@media (max-width: 767.98px) {
   .boq-summary-header,
   .boq-total-wrap {
      align-items: flex-start;
   }

   .boq-summary-header {
      flex-direction: column;
   }

   .boq-total-wrap {
      width: 100%;
      flex-wrap: wrap;
      margin-left: 0;
   }

   .boq-total-text {
      text-align: left;
   }

   .boq-project-filter {
      width: 100%;
   }
}
</style>
@endsection
@section('contents')
<div class="content">
   <div class="d-flex align-items-center justify-content-between gap-3 flex-wrap st-page-intro">
         <div>
            <span class="st-eyebrow">WORKSPACE OVERVIEW</span>
            <h1>A clear view of your sites.</h1>
            <p>Welcome back, {{ Auth::user()->user_name }}. Here’s where things stand today.</p>
         </div>
         <div class="gap-2 d-flex align-items-center flex-wrap">
            <a href="{{ route('user.dashboard') }}" class="btn btn-icon btn-white" aria-label="Refresh dashboard"><i class="ti ti-refresh" aria-hidden="true"></i></a>
            @if(Helper::userHasPermission('user.add_project'))
            <a href="{{ route('user.add_project') }}" class="btn btn-primary"><i class="ti ti-plus me-1" aria-hidden="true"></i>New project</a>
            @endif
         </div>
   </div>
   <div class="row st-metrics">
      <div class="col-xl-2 col-md-6 d-flex">
         <a href="{{ route('user.projects') }}" class="card flex-fill labour-summary-card dashboard-summary-link">
            <div class="card-body">
               <div class="d-flex align-items-center justify-content-between">
                  <div>
                     <p class="mb-1">Active sites</p>
                     <h6 class="mb-0">{{ $active_projects }}</h6>
                  </div>
                  <span class="avatar rounded-circle badge-soft-primary"><i class="ti ti-building fs-24"></i></span>
               </div>
            </div>
         </a>
      </div>
      <div class="col-xl-2 col-md-6 d-flex">
         <a href="{{ route('user.daily_works') }}" class="card flex-fill labour-summary-card dashboard-summary-link">
            <div class="card-body">
               <div class="d-flex align-items-center justify-content-between">
                  <div>
                     <p class="mb-1">Today's work</p>
                     <h6 class="mb-0">{{ $todays_work }}</h6>
                  </div>
                  <span class="avatar rounded-circle badge-soft-pink"><i class="ti ti-calendar fs-24"></i></span>
               </div>
            </div>
         </a>
      </div>
      <div class="col-xl-2 col-md-6 d-flex">
         <a href="{{ route('user.daily_progress') }}" class="card flex-fill labour-summary-card dashboard-summary-link">
            <div class="card-body">
               <div class="d-flex align-items-center justify-content-between">
                  <div>
                     <p class="mb-1">Today's team</p>
                     <h6 class="mb-0">{{ $today_workers }}</h6>
                  </div>
                  <span class="avatar rounded-circle badge-soft-indigo"><i class="ti ti-users-group fs-24"></i></span>
               </div>
            </div>
         </a>
      </div>
      <div class="col-xl-2 col-md-6 d-flex">
         <a href="{{ route('user.indents') }}" class="card flex-fill labour-summary-card dashboard-summary-link">
            <div class="card-body">
               <div class="d-flex align-items-center justify-content-between">
                  <div>
                     <p class="mb-1">Pending approvals</p>
                     <h6 class="mb-0">{{ $pending_approvals }}</h6>
                  </div>
                  <span class="avatar rounded-circle badge-soft-orange"><i class="ti ti-bell fs-24"></i></span>
               </div>
            </div>
         </a>
      </div>
      <div class="col-xl-2 col-md-6 d-flex">
         <a href="{{ route('user.issues') }}" class="card flex-fill labour-summary-card dashboard-summary-link">
            <div class="card-body">
               <div class="d-flex align-items-center justify-content-between">
                  <div>
                     <p class="mb-1">Open issues</p>
                     <h6 class="mb-0">{{ $open_issues }}</h6>
                  </div>
                  <span class="avatar rounded-circle badge-soft-orange"><i class="ti ti-alert-triangle fs-24"></i></span>
               </div>
            </div>
         </a>
      </div>
      <div class="col-xl-2 col-md-6 d-flex">
         <a href="{{ route('user.orders') }}" class="card flex-fill labour-summary-card dashboard-summary-link">
            <div class="card-body">
               <div class="d-flex align-items-center justify-content-between">
                  <div>
                     <p class="mb-1">Today's orders</p>
                     <h6 class="mb-0">{{ $todays_order }}</h6>
                  </div>
                  <span class="avatar rounded-circle badge-soft-orange"><i class="ti ti-package fs-24"></i></span>
               </div>
            </div>
         </a>
      </div>
   </div>
   <div class="row">
      <div class="col-xl-6 col-md-6 d-flex">
         <div class="card shadow flex-fill w-100">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
               <h5 class="mb-0">Project progress</h5>
               <a href="{{ route('user.projects') }}" class="btn btn-sm btn-white flex-shrink-0">View All</a>
            </div>
            <div class="card-body">
               <div class="overflow-auto">
                  @forelse($projects as $project)
                  @php 
                  $boqs = App\Models\ProjectBoq::where('pboq_project',$project->project_id)->get();
                  $boq_total = array_sum(array_column($boqs->toArray(),'pboq_amount'));
                  $all_progress = App\Models\DailyProgress::join('project_boqs','project_boqs.pboq_id', 'daily_progress.dprogress_boq')->where('dprogress_project',$project->project_id)->get();
                  $progress_amount = array();
                  @endphp
                  @foreach($all_progress as $progress)
                  @php 
                  $progress_qty[$progress->dprogress_boq][] = $progress->dprogress_qty;
                  $progress_amount[] = $progress->dprogress_qty * $progress->pboq_rate;
                  @endphp
                  @endforeach
                  @php 
                  $done_value = array_sum($progress_amount); 
                  $total_average = $boq_total > 0 ? ($done_value / $boq_total) * 100 : 0;
                  $total_average = round($total_average);
                  $total_average = max(0, min(100, $total_average));
                  $projectProgressColor = $total_average >= 60 ? '#ffc221' : ($total_average > 0 ? '#ff7a1a' : '#ff4d57');
                  @endphp
                  <div class="dashboard-project-progress-item" style="--dashboard-project-progress: {{ $total_average }}%; --dashboard-project-progress-color: {{ $projectProgressColor }};">
                     <div class="dashboard-project-progress-head">
                        <div class="min-w-0">
                           <h6 class="fw-semibold fs-14 text-truncate mb-1">
                              <a href="javascript:void(0)">{{ $project->project_name }}</a>
                           </h6>
                           <p class="fs-13 mb-0">{{ $project->project_location }} - {{ $project->client->client_name }}</p>
                        </div>
                        <div class="dashboard-project-progress-percent">{{ $total_average }}%</div>
                     </div>
                     <div class="dashboard-project-progress-track" aria-label="{{ $total_average }}% complete">
                        <span></span>
                     </div>
                  </div>
                  @empty
                  <div class="st-empty"><i class="ti ti-building" aria-hidden="true"></i><h6>No active projects yet</h6><p>Your active sites and their progress will appear here.</p></div>
                  @endforelse
               </div>
            </div>
         </div>
      </div>
      <div class="col-xl-6 col-md-6 d-flex">
         <div class="card shadow flex-fill w-100">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
               <h5 class="mb-0">Needs your attention</h5>
               <a href="{{ route('user.indents') }}" class="btn btn-sm btn-white flex-shrink-0">View All</a>
            </div>
            <div class="card-body">
               <div class="overflow-auto">
                  @forelse($pending_indents as $pending_indent)
                  <div class="d-flex justify-content-between align-items-center mb-3">
                     <div class="d-flex align-items-center">
                           <div class="ms-2">
                              <div>
                                 <h6 class="fw-semibold fs-14 text-truncate mb-1"><a href="javascript:void(0)">{{ date('d M Y',strtotime($pending_indent->indent_date)) }}</a></h6>
                                 <p class="fs-13 mb-0">{{ $pending_indent->project->project_name }}</p>
                              </div>
                           </div>
                     </div>
                     <div class="flex-shrink-0 ms-2">
                        @if($pending_indent->indent_status == 1)
                        <span class="badge badge-soft-success">Pending Quotation</span>
                        @elseif($pending_indent->indent_status == 2)
                        <span class="badge badge-soft-success">MD Review</span>
                        @endif
                     </div>
                  </div>
                  @empty
                  <div class="st-empty"><i class="ti ti-circle-check" aria-hidden="true"></i><h6>You’re all caught up</h6><p>New quotations and approvals will appear here.</p></div>
                  @endforelse
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
