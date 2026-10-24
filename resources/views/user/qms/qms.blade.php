@extends('user.layouts.app')
@section('title', 'QMS Reports - ' . Request()->settings->settings_title)
@section('custom_style')
<style>
.qms-filter-bar {
   display: flex;
   align-items: flex-end;
   gap: 1rem;
   margin-bottom: 1.5rem;
}

.qms-filter-field {
   flex: 1 1 auto;
   min-width: 0;
}

.qms-filter-field label {
   display: block;
   margin-bottom: .45rem;
   color: var(--body-color);
   font-size: .75rem;
   font-weight: 700;
   letter-spacing: .08em;
   text-transform: uppercase;
}

.qms-filter-actions {
   display: flex;
   gap: .5rem;
   flex: 0 0 auto;
}

.qms-report-panel {
   --qms-panel-bg: #fff;
   --qms-panel-border: #d7e0ee;
   --qms-panel-text: #0f172a;
   --qms-muted-text: #64748b;
   --qms-meta-text: #475569;
   --qms-head-bg: linear-gradient(90deg, #fff7ed 0%, #fff 48%, #eff6ff 100%);
   --qms-soft-bg: #f8fafc;
   --qms-table-head-bg: #eef2f7;
   --qms-table-border: #e2e8f0;
   --qms-carry-bg: #fffbeb;
   --qms-carry-text: #0f172a;
   --qms-shadow: 0 10px 24px rgba(15, 23, 42, .04);
   overflow: hidden;
   border: 1px solid var(--qms-panel-border);
   border-radius: 10px;
   background: var(--qms-panel-bg);
   color: var(--qms-panel-text);
   box-shadow: var(--qms-shadow);
}

.qms-report-head {
   display: flex;
   align-items: center;
   justify-content: space-between;
   gap: 1rem;
   padding: 1.25rem 1.5rem;
   border-bottom: 1px solid var(--qms-panel-border);
   background: var(--qms-head-bg);
}

.qms-report-kicker {
   color: var(--qms-muted-text);
   font-size: .72rem;
   font-weight: 800;
   letter-spacing: .1em;
   text-transform: uppercase;
}

.qms-report-title {
   margin: .25rem 0 .15rem;
   color: var(--qms-panel-text);
   font-size: 1.35rem;
   font-weight: 800;
}

.qms-report-meta {
   color: var(--qms-meta-text);
   font-size: .875rem;
}

.qms-prepared {
   text-align: right;
   color: var(--qms-muted-text);
   font-size: .75rem;
   line-height: 1.25;
}

.qms-prepared strong {
   display: block;
   color: var(--qms-panel-text);
   font-size: .9rem;
}

.qms-prepared span {
   color: #008a49;
   font-weight: 700;
}

.qms-report-body {
   padding: 1.25rem 1.5rem 1.5rem;
}

.qms-stat-grid {
   display: grid;
   grid-template-columns: repeat(5, minmax(0, 1fr));
   gap: .75rem;
   margin-bottom: 1rem;
}

.qms-stat-card {
   min-height: 88px;
   padding: 1rem;
   border: 1px solid var(--qms-panel-border);
   border-radius: 8px;
   background: var(--qms-soft-bg);
   text-align: center;
}

.qms-stat-card i {
   display: block;
   margin-bottom: .45rem;
   color: var(--qms-stat-color, #1d6fb8);
   font-size: 1.25rem;
}

.qms-stat-card .value {
   display: block;
   color: var(--qms-stat-color, #1d6fb8);
   font-size: 1.15rem;
   line-height: 1;
   font-weight: 800;
}

.qms-stat-card .label {
   display: block;
   margin-top: .4rem;
   color: var(--qms-muted-text);
   font-size: .68rem;
   font-weight: 800;
   letter-spacing: .08em;
   text-transform: uppercase;
}

.qms-carry-alert {
   display: flex;
   align-items: center;
   gap: .6rem;
   padding: .85rem 1rem;
   margin-bottom: 1.15rem;
   border: 1px solid #f5c542;
   border-radius: 8px;
   background: var(--qms-carry-bg);
   color: var(--qms-carry-text);
   font-weight: 600;
}

.qms-carry-alert i,
.qms-carry-alert strong {
   color: #ff9f1a;
}

.qms-section-title {
   display: flex;
   align-items: center;
   gap: .45rem;
   margin-bottom: .65rem;
   color: var(--qms-panel-text);
   font-size: .95rem;
   font-weight: 800;
}

.qms-section-title i {
   color: #ff7a1a;
}

.qms-table {
   margin-bottom: 1.25rem;
}

.qms-table thead th {
   border-bottom: 1px solid var(--qms-panel-border);
   background: var(--qms-table-head-bg);
   color: var(--qms-muted-text);
   font-size: .72rem;
   letter-spacing: .08em;
   text-transform: uppercase;
}

.qms-table tbody td {
   vertical-align: middle;
   border-color: var(--qms-table-border);
   background: var(--qms-panel-bg);
   color: var(--qms-panel-text);
}

.qms-progress-wrap {
   display: flex;
   align-items: center;
   gap: .6rem;
   min-width: 150px;
}

.qms-progress-track {
   width: 100%;
   height: 8px;
   overflow: hidden;
   border-radius: 999px;
   background: #24304f;
}

.qms-progress-track span {
   display: block;
   width: var(--qms-progress, 0%);
   height: 100%;
   border-radius: inherit;
   background: var(--qms-progress-color, #ffc221);
}

.qms-progress-percent {
   color: var(--qms-progress-color, #ffc221);
   font-size: .8125rem;
   font-weight: 800;
   white-space: nowrap;
}

.qms-empty-state {
   padding: 2rem;
   border: 1px dashed var(--qms-empty-border, #cbd5e1);
   border-radius: 10px;
   background: var(--qms-empty-bg, #fff);
   color: var(--qms-empty-color, #64748b);
   text-align: center;
}

[data-bs-theme=dark] .qms-filter-field label {
   color: #9fb2d0;
}

[data-bs-theme=dark] .qms-report-panel {
   --qms-panel-bg: #111821;
   --qms-panel-border: #2c3a50;
   --qms-panel-text: #e8eef8;
   --qms-muted-text: #93a4bf;
   --qms-meta-text: #b6c3d7;
   --qms-head-bg: linear-gradient(90deg, #1e2226 0%, #111821 48%, #101d2f 100%);
   --qms-soft-bg: #172233;
   --qms-table-head-bg: #1d2a3d;
   --qms-table-border: #2b3a52;
   --qms-carry-bg: rgba(255, 159, 26, .12);
   --qms-carry-text: #f8fafc;
   --qms-shadow: none;
}

[data-bs-theme=dark] .qms-empty-state {
   --qms-empty-bg: #111821;
   --qms-empty-border: #2c3a50;
   --qms-empty-color: #93a4bf;
}

@media (max-width: 1199.98px) {
   .qms-stat-grid {
      grid-template-columns: repeat(3, minmax(0, 1fr));
   }
}

@media (max-width: 767.98px) {
   .qms-filter-bar,
   .qms-report-head {
      flex-direction: column;
      align-items: stretch;
   }

   .qms-filter-actions {
      width: 100%;
      flex-wrap: wrap;
   }

   .qms-filter-actions .btn {
      flex: 1 1 auto;
   }

   .qms-prepared {
      text-align: left;
   }

   .qms-stat-grid {
      grid-template-columns: 1fr;
   }
}
</style>
@endsection
@section('contents')
@php
   $selectedProject = $projects->firstWhere('project_id', Request()->project) ?? $projects->first();
@endphp
<div class="content">
   <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
      <div class="breadcrumb-arrow">
         <h4 class="mb-1">QMS Reports</h4>
         <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
               <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
               <li class="breadcrumb-item active">QMS Reports</li>
            </ol>
         </div>
      </div>
      <div class="gap-2 d-flex align-items-center flex-wrap">
         <a href="{{ route('user.qms') }}" class="btn btn-icon btn-white" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
      </div>
   </div>

   <div class="qms-filter-bar">
      <div class="qms-filter-field">
         <label for="project">Select Project</label>
         <select name="project" id="project" class="form-control select2" data-toggle="select2" data-st-change="select_filter()">
            @foreach($projects as $project)
            <option value="{{ $project->project_id }}" {{ ($selectedProject && $selectedProject->project_id == $project->project_id) ? 'selected':'' }}>{{ $project->project_name }}</option>
            @endforeach
         </select>
      </div>
      <div class="qms-filter-actions">

         <a href="{{ route('user.qms_download_excel',['project'=>$selectedProject->project_id]) }}" class="btn btn-success"><i class="ti ti-download me-1"></i>Download</a>
      </div>
   </div>

   @if($selectedProject)
      @php
         $boqs = App\Models\ProjectBoq::where('pboq_project', $selectedProject->project_id)->get();
         $boqTotal = $boqs->sum('pboq_amount');
         $progressRows = App\Models\DailyProgress::where('dprogress_project', $selectedProject->project_id)->where('dprogress_trash', 1)->get();
         $progressByBoq = $progressRows->groupBy('dprogress_boq');
         $doneValue = 0;
         $carryForwardCount = $progressRows->where('dprogress_cforward', 1)->count();
         $dprEntries = $progressRows->count();
         $labourTrackerIds = App\Models\LabourTracker::where('ltracker_project', $selectedProject->project_id)->where('ltracker_trash', 1)->pluck('ltracker_id');
         $labourDays = App\Models\LabourWorker::whereIn('lworker_tracker', $labourTrackerIds)->get()->sum(function($worker) {
            return (float) $worker->lworker_male + (float) $worker->lworker_female;
         });
         $issues = App\Models\Issue::with(['category', 'engineer'])->where('issue_project', $selectedProject->project_id)->where('issue_status', 1)->get();
         $openIssuesCount = $issues->count();

         foreach($boqs as $boq) {
            $completedQty = optional($progressByBoq->get($boq->pboq_id))->sum('dprogress_qty') ?? 0;
            $doneValue += $completedQty * $boq->pboq_rate;
         }

         $overallProgress = $boqTotal > 0 ? round(($doneValue / $boqTotal) * 100) : 0;
         $overallProgress = max(0, min(100, $overallProgress));
         $preparedBy = optional($selectedProject->engineer)->user_name ?? (auth()->user()->user_name ?? 'Site Engineer');
         $priorityLabels = [1 => 'Low', 2 => 'Medium', 3 => 'High', 4 => 'Critical'];
      @endphp
      <div class="qms-report-panel">
         <div class="qms-report-head">
            <div>
               <div class="qms-report-kicker">SiteTrack Pro - QMS Report</div>
               <h5 class="qms-report-title">{{ $selectedProject->project_name }}</h5>
               <div class="qms-report-meta">{{ optional($selectedProject->client)->client_name ?? 'Client' }} &middot; {{ $selectedProject->project_location }} &middot; {{ date('d M Y') }}</div>
            </div>
            <div class="qms-prepared">
               Prepared by
               <strong>{{ $preparedBy }}</strong>
               <span>QMS / Quality</span>
            </div>
         </div>

         <div class="qms-report-body">
            <div class="qms-stat-grid">
               <div class="qms-stat-card" style="--qms-stat-color:#ff9f1a;">
                  <i class="ti ti-chart-bar"></i>
                  <span class="value">{{ $overallProgress }}%</span>
                  <span class="label">BOQ Progress</span>
               </div>
               <div class="qms-stat-card" style="--qms-stat-color:#00b77a;">
                  <i class="ti ti-coins"></i>
                  <span class="value">&#8377;{{ Helper::moneyFormat($doneValue) }}</span>
                  <span class="label">Done Value</span>
               </div>
               <div class="qms-stat-card" style="--qms-stat-color:#1d6fb8;">
                  <i class="ti ti-trending-up"></i>
                  <span class="value">{{ $dprEntries }}</span>
                  <span class="label">DPR Entries</span>
               </div>
               <div class="qms-stat-card" style="--qms-stat-color:#7c5cff;">
                  <i class="ti ti-users-group"></i>
                  <span class="value">{{ $labourDays }}</span>
                  <span class="label">Labour Days</span>
               </div>
               <div class="qms-stat-card" style="--qms-stat-color:#ff4d57;">
                  <i class="ti ti-alert-triangle"></i>
                  <span class="value">{{ $openIssuesCount }}</span>
                  <span class="label">Open Issues</span>
               </div>
            </div>

            <div class="qms-carry-alert">
               <i class="ti ti-corner-up-right"></i>
               <div><strong>{{ $carryForwardCount }} Carry Forward items</strong> - work delayed from planned dates</div>
            </div>

            <div class="qms-section-title"><i class="ti ti-list-details"></i>BOQ Progress</div>
            <div class="table-responsive">
               <table class="table qms-table">
                  <thead>
                     <tr>
                        <th>Work Item</th>
                        <th>Unit</th>
                        <th>BOQ Qty</th>
                        <th>Completed</th>
                        <th>Balance</th>
                        <th>% Done</th>
                     </tr>
                  </thead>
                  <tbody>
                     @forelse($boqs as $boq)
                        @php
                           $completedQty = optional($progressByBoq->get($boq->pboq_id))->sum('dprogress_qty') ?? 0;
                           $balanceQty = max(0, $boq->pboq_qty - $completedQty);
                           $boqPercent = $boq->pboq_qty > 0 ? round(($completedQty / $boq->pboq_qty) * 100) : 0;
                           $boqPercent = max(0, min(100, $boqPercent));
                           $boqColor = $boqPercent >= 80 ? '#00b77a' : ($boqPercent >= 50 ? '#ffc221' : '#ff7a1a');
                        @endphp
                        <tr>
                           <td class="fw-semibold">{{ $boq->pboq_item }}</td>
                           <td>{{ $boq->pboq_unit }}</td>
                           <td>{{ $boq->pboq_qty }}</td>
                           <td class="text-success fw-semibold">{{ $completedQty }}</td>
                           <td class="{{ $balanceQty > 0 ? 'text-danger' : 'text-muted' }} fw-semibold">{{ $balanceQty }}</td>
                           <td>
                              <div class="qms-progress-wrap" style="--qms-progress: {{ $boqPercent }}%; --qms-progress-color: {{ $boqColor }};">
                                 <div class="qms-progress-track"><span></span></div>
                                 <span class="qms-progress-percent">{{ $boqPercent }}%</span>
                              </div>
                           </td>
                        </tr>
                     @empty
                        <tr>
                           <td colspan="6" class="text-center text-muted">No BOQ items found</td>
                        </tr>
                     @endforelse
                  </tbody>
               </table>
            </div>

            <div class="qms-section-title"><i class="ti ti-alert-triangle"></i>Open Issues</div>
            <div class="table-responsive">
               <table class="table qms-table mb-0">
                  <thead>
                     <tr>
                        <th>Date</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Priority</th>
                        <th>Assigned To</th>
                     </tr>
                  </thead>
                  <tbody>
                     @forelse($issues as $issue)
                        <tr>
                           <td class="fw-semibold">{{ date('d M y', strtotime($issue->issue_date)) }}</td>
                           <td class="fw-semibold">{{ $issue->issue_title }}</td>
                           <td>{{ optional($issue->category)->icategory_name ?? '-' }}</td>
                           <td>{{ $priorityLabels[$issue->issue_priority] ?? '-' }}</td>
                           <td>{{ optional($issue->engineer)->user_name ?? '-' }}</td>
                        </tr>
                     @empty
                        <tr>
                           <td colspan="5" class="text-center text-muted">No open issues found</td>
                        </tr>
                     @endforelse
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   @else
      <div class="qms-empty-state">
         No active project found for QMS report.
      </div>
   @endif
</div>
@endsection
@section('custom_script')
<script nonce="{{ app(\App\Security\SecurityPolicy::class)->nonce() }}">
function select_filter() {
   var project = $('#project').val();
   window.location.href = "{{ route('user.qms') }}?project=" + project;
}
</script>
@endsection
