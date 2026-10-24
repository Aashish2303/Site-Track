@extends('user.layouts.app')
@section('title', 'View Project - ' . Request()->settings->settings_title)
@section('custom_style')
<style>
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

.boq-extra-row > * {
   background-color: #fff3cd;
}

.boq-extra-row > :first-child {
   border-left: 4px solid #ff9f1a;
}

.boq-extra-row .ti-star-filled {
   color: #ff9f1a;
}
</style>
@endsection
@section('contents')
<div class="content">
   <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
      <div class="breadcrumb-arrow">
         <h4 class="mb-1">View Project</h4>
         <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
               <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
               <li class="breadcrumb-item"><a href="{{ route('user.projects') }}">Projects</a></li>
               <li class="breadcrumb-item active">View Project</li>
            </ol>
         </div>
      </div>
      <div class="gap-2 d-flex align-items-center flex-wrap">
         <a href="{{ route('user.view_project',['id'=>$project->project_id]) }}" class="btn btn-icon btn-white" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
         <a href="{{ route('user.projects') }}" class="btn btn-secondary"><i class="ti ti-arrow-left me-1"></i>Back</a>
      </div>
   </div>

   <div class="row">
      <div class="col-xl-4">
         <div class="card mb-4 mb-xl-0">
            <div class="card-body">
               <div class="d-flex align-items-center pb-3 mb-3 border-bottom">
                  <div>
                     @if($project->project_status == 1)
                     <span class="badge badge-soft-primary">Active</span>
                     @elseif($project->project_status == 2)
                     <span class="badge badge-soft-danger">On Hold</span>
                     @elseif($project->project_status == 3)
                     <span class="badge badge-soft-success">Completed</span>
                     @endif
                     <h5 class="mb-1 mt-2">{{ $project->project_name }}</h5>
                     <p class="fs-13 mb-0">Period : {{ date('d M Y',strtotime($project->project_start)).' - '.date('d M Y',strtotime($project->project_end)) }}</p>
                  </div>
               </div>
               <h6 class="mb-2">Basic Information</h6>
               <p class="mb-3">Created On <span class="float-end text-dark">{{ date('d M Y',strtotime($project->project_added_on)) }}</span></p>
               <p class="mb-3">Location <span class="float-end text-dark">{{ $project->project_location }}</span></p>
               <p class="mb-3">Client <span class="float-end text-dark">{{ $project->client->client_name }}</span></p>
               <p class="mb-3">Site Engineer <span class="float-end text-dark">{{ $project->engineer->user_name }}</span></p>
               <p class="mb-3">Budget <span class="float-end text-dark">{{ Helper::moneyFormat($project->project_budget) }}</span></p>
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
               @endphp
               @if($boq_total == 0)
               @php
                  $total_average = 0;
               @endphp
               @endphp
               @else
               @php
               $total_average = ($done_value / $boq_total) * 100;
               $total_average = round($total_average);
               @endphp
               @endif
               @php
               $rowProgressColor = $total_average >= 100 ? '#00b884' : ($total_average >= 60 ? '#ffc221' : '#ff4d57');
               @endphp
               <h6 class="mb-2 mt-3 mb-2 pt-3 border-top">Progress</h6>
               <p class="mb-0">
                  <div class="boq-row-progress-wrap" style="--boq-row-progress: '.$total_average.'%; --boq-row-progress-color: {{ $rowProgressColor }}" aria-label="'.$total_average.'% complete">
                     <div class="boq-row-progress">
                        <span></span>
                     </div>
                     <span class="boq-row-progress-percent">{{ $total_average }}%</span>
                  </div>
               </p>
            </div>
         </div>
      </div>
      <div class="col-xl-8">
         <div class="card shadow flex-fill w-100">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2">
               <h5 class="mb-0 d-inline-flex align-items-center">BOQ Items</h5>
            </div>
            <div class="card-body">
               <div class="row row-gap-3">
                  <div class="col-xl-12 d-flex">
                     <div class="p-3 border rounded flex-fill">
                        <div class="table-responsive table-nowrap">
                           <table id="client_table" class="table border mb-0 w-100">
                              <thead class="table-light">
                                 <tr>
                                    <th>Work Item</th>
                                    <th>Unit</th>
                                    <th>BOQ Qty</th>
                                    <th>Completed Qty</th>
                                    <th>Balance Qty</th>
                                    <th>Rate (₹)</th>
                                    <th>Done Value (₹)</th>
                                    <th>Progress</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @foreach($boqs as $boq)
                                 <tr @if($boq->pboq_extra == 1) class="boq-extra-row" @endif>
                                    <td>@if($boq->pboq_extra == 1) <i class="ti ti-star-filled"></i> @endif {{ $boq->pboq_item }}</td>
                                    <td>{{ $boq->pboq_unit }}</td>
                                    <td>{{ $boq->pboq_qty }}</td>
                                    @if(isset($progress_qty[$boq->pboq_id]))
                                    @php $completed_boq = array_sum($progress_qty[$boq->pboq_id]); @endphp
                                    <td>{{ $completed_boq }}</td>
                                    @else
                                    @php $completed_boq = 0; @endphp
                                    <td>0</td>
                                    @endif
                                    <td>{{ $boq->pboq_qty - $completed_boq }}</td>
                                    <td>{{ Helper::moneyFormat($boq->pboq_rate) }}</td>
                                    @php $total_value = $completed_boq * $boq->pboq_rate; @endphp
                                    <td>{{ Helper::moneyFormat($total_value) }}</td>
                                    @php
                                    $boq_percentage = ($completed_boq / $boq->pboq_qty) * 100;
                                    $boq_percentage = round($boq_percentage);
                                    $rowProgressColor = $boq_percentage >= 100 ? '#00b884' : ($boq_percentage >= 60 ? '#ffc221' : '#ff4d57');
                                    @endphp
                                    <td>
                                       <div class="boq-row-progress-wrap" style="--boq-row-progress: {{ $boq_percentage }}%; --boq-row-progress-color: {{ $rowProgressColor }};" aria-label="{{ $boq_percentage }}% complete">
                                          <div class="boq-row-progress">
                                             <span></span>
                                          </div>
                                          <span class="boq-row-progress-percent">{{ $boq_percentage }}%</span>
                                       </div>
                                    </td>
                                 </tr>
                                 @endforeach
                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
