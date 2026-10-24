@extends('user.layouts.app')
@section('title', 'BOQ Tracker' . Request()->settings->settings_title)
@section('custom_style')
<style>
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
   <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
      <div class="breadcrumb-arrow">
         <h4 class="mb-1">BOQ Tracker</h4>
         <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
               <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
               <li class="breadcrumb-item active">BOQ Tracker</li>
            </ol>
         </div>
      </div>
      <div class="gap-2 d-flex align-items-center flex-wrap boq-project-filter">
         <select name="project" id="project" class="form-control select2" data-toggle="select2" onchange="select_project()">
            <option value="">All Project</option>
            @foreach($all_projects as $all_project)
            <option value="{{ $all_project->project_id }}" {{ $all_project->project_id == Request()->project ? 'selected':'' }}>{{ $all_project->project_name }}</option>
            @endforeach
         </select>
      </div>
   </div>
   @foreach($projects as $project)
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
   @php $total_average = 0; @endphp
   @else
   @php 
   $total_average = ($done_value / $boq_total) * 100;   
   $total_average = round($total_average);
   @endphp
   @endif
   <div class="card mb-4">
      <div class="card-header boq-summary-header">
         <div>
            <h5 class="boq-project-title">{{ $project->project_name }}</h5>
            <div class="boq-project-meta">{{ $project->project_location }} - {{ $boqs->count() }} BOQ items</div>
            <div>
               <a href="javascript:void(0)" class="btn btn-sm btn-primary mt-2" onclick="add_boq_item({{ $project->project_id }})">Add Extra Item</a>
            </div>
         </div>
         <div class="boq-total-wrap">
            <div class="boq-total-text">
               <span class="boq-total-label">Reported / Total</span>
               ₹{{ Helper::moneyFormat($done_value) }} / ₹{{ Helper::moneyFormat($boq_total) }}
            </div>
            <div class="boq-total-progress" style="--boq-progress: {{ $total_average }}%;" aria-label="{{ $total_average }}% complete">
               <span></span>
            </div>
            <div class="boq-total-percent">{{ $total_average }}%</div>
         </div>
      </div>
      <div class="card-body">
         <div class="table-responsive-sm">
            <table id="client_table" class="table border mb-0 w-100">
               <thead class="table-light">
                  <tr>
                     <th>Work Item</th>
                     <th>Unit</th>
                     <th>BOQ Qty</th>
                     <th>Reported Qty</th>
                     <th>Balance Qty</th>
                     <th>Rate (₹)</th>
                     <th>Reported Value (₹)</th>
                     <th>Progress</th>
                     <th>Edit</th>
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
                     $boq_percentage = ((float) $boq->pboq_qty > 0 ? ($completed_boq / $boq->pboq_qty) * 100 : 0);
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
                     <td>
                        <a href="javascript:void(0)" class="btn btn-icon btn-sm btn-success" onclick="edit_boq_item({{ $boq->pboq_id }})" title="Edit"><i class="ti ti-edit"></i></a>
                     </td>
                  </tr>
                  @endforeach
               </tbody>
            </table>
         </div>
      </div>
   </div>
   @endforeach
</div>
<div id="addBoqModal" class="modal fade">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="text-dark modal-title fw-bold text-truncate">Add Item</h5>
            <button type="button" class="btn-close btn-close-modal" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-circle-x-filled"></i></button>
         </div>
         <form id="add_boq_item_form" action="{{ route('user.add_boq_item') }}" method="POST">
         </form>
      </div>
   </div>
</div>
<div id="editBoqModal" class="modal fade">
   <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="text-dark modal-title fw-bold text-truncate">Edit Item</h5>
            <button type="button" class="btn-close btn-close-modal" data-bs-dismiss="modal" aria-label="Close"><i class="ti ti-circle-x-filled"></i></button>
         </div>
         <form id="edit_boq_item_form" action="{{ route('user.edit_boq_item') }}" method="POST">
         </form>
      </div>
   </div>
</div>
@endsection
@section('custom_script')
<script>
function add_boq_item(project_id)
{
    var csrf = "{{ csrf_token() }}";

    $.ajax({
        url: "{{ route('user.get_boq_project_details') }}",
        type: "post",
        data: "project_id="+project_id+'&_token='+csrf,
        success: function (data) {

            $('#addBoqModal').modal('show');
            $("#add_boq_item_form").html(data);
        }
    });
}

function edit_boq_item(pboq_id)
{
    var csrf = "{{ csrf_token() }}";

    $.ajax({
        url: "{{ route('user.get_boq_item_details') }}",
        type: "post",
        data: "pboq_id="+pboq_id+'&_token='+csrf,
        success: function (data) {

            $('#editBoqModal').modal('show');
            $("#edit_boq_item_form").html(data);
        }
    });
}

function select_project()
{
   var project = $("#project").val();

   window.location.href = '/user/boq_tracker?project='+project;
}
</script>
@endsection
