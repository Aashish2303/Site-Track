@extends('user.layouts.app')
@section('title', 'Add Daily Progress - ' . Request()->settings->settings_title)
@section('contents')
<div class="content">
   <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
      <div class="breadcrumb-arrow">
         <h4 class="mb-1">Add Daily Progress</h4>
         <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
               <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
               <li class="breadcrumb-item"><a href="{{ route('user.daily_progress') }}">Daily Progress</a></li>
               <li class="breadcrumb-item active">Add Daily Progress</li>
            </ol>
         </div>
      </div>
      <div class="gap-2 d-flex align-items-center flex-wrap">
         <a href="{{ route('user.add_daily_progress') }}" class="btn btn-icon btn-white" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
         <a href="{{ route('user.daily_progress') }}" class="btn btn-secondary"><i class="ti ti-arrow-left me-1"></i>Back</a>
      </div>
   </div>
   <div class="row row-gap-3 vertical-tab">
      <div class="col-xl-12 col-lg-12">
         <div class="patient-form-wizard flex-fill">
            <div class="form-wizard-content active">
               <form id="daily_progress_form" action="{{ route('user.add_daily_progress') }}" method="POST">
                  @csrf
                  <div class="card">
                     <div class="card-header">
                        <h5 class="mb-0">Add Daily Progress</h5>
                     </div>
                     <div class="card-body pb-0">
                        <div class="row mb-3">
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Date<span class="text-danger ms-1">*</span></label>
                                 <input type="date" name="dprogress_date" class="form-control" value="{{ old('dprogress_date') }}" autocomplete="off">
                                 @if($errors->has('dprogress_date'))
                                 <div>{{ $errors->first('dprogress_date') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Project<span class="text-danger ms-1">*</span></label>
                                 <select name="dprogress_project" id="dprogress_project" class="form-control select2" data-toggle="select2" onchange="select_boq()">
                                    <option value="">Select</option>
                                    @foreach($projects as $project)
                                    <option value="{{ $project->project_id }}">{{ $project->project_name }}</option>
                                    @endforeach
                                 </select>
                                 @if($errors->has('dprogress_project'))
                                 <div>{{ $errors->first('dprogress_project') }}</div>
                                 @endif
                                 <div id="dprogress_project_error"></div>
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Category<span class="text-danger ms-1">*</span></label>
                                 <select name="dprogress_category" class="form-control select2" data-toggle="select2">
                                    <option value="">Select</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                                    @endforeach
                                 </select>
                                 @if($errors->has('dprogress_category'))
                                 <div>{{ $errors->first('dprogress_category') }}</div>
                                 @endif
                                 <div id="dprogress_category_error"></div>
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Description Of Work<span class="text-danger ms-1">*</span></label>
                                 <input type="text" name="dprogress_description" class="form-control" value="{{ old('dprogress_description') }}" autocomplete="off">
                                 @if($errors->has('dprogress_description'))
                                 <div>{{ $errors->first('dprogress_description') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">BOQ Item<span class="text-danger ms-1">*</span></label>
                                 <select name="dprogress_boq" id="dprogress_boq" class="form-control select2" data-toggle="select2">
                                    <option value="">Select</option>
                                 </select>
                                 @if($errors->has('dprogress_boq'))
                                 <div>{{ $errors->first('dprogress_boq') }}</div>
                                 @endif
                                 <div id="dprogress_boq_error"></div>   
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Quantity Done Today<span class="text-danger ms-1">*</span></label>
                                 <input type="number" step="0.0001" min="0" name="dprogress_qty" class="form-control" value="{{ old('dprogress_qty') }}" autocomplete="off">
                                 @if($errors->has('dprogress_qty'))
                                 <div>{{ $errors->first('dprogress_qty') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Location<span class="text-danger ms-1">*</span></label>
                                 <input type="text" name="dprogress_location" class="form-control" value="{{ old('dprogress_location') }}" autocomplete="off">
                                 @if($errors->has('dprogress_location'))
                                 <div>{{ $errors->first('dprogress_location') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Subcontractor<span class="text-danger ms-1">*</span></label>
                                 <select name="dprogress_subcontractor" class="form-control select2" data-toggle="select2">
                                    <option value="">Select</option>
                                    @foreach($subcontractors as $subcontractor)
                                    <option value="{{ $subcontractor->subcontractor_id }}">{{ $subcontractor->subcontractor_name }}</option>
                                    @endforeach
                                 </select>
                                 @if($errors->has('dprogress_subcontractor'))
                                 <div>{{ $errors->first('dprogress_subcontractor') }}</div>
                                 @endif
                                 <div id="dprogress_subcontractor_error"></div>
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Site Incharge<span class="text-danger ms-1">*</span></label>
                                 <select name="dprogress_incharge" class="form-control select2" data-toggle="select2">
                                    <option value="">Select</option>
                                    @foreach($incharges as $incharge)
                                    <option value="{{ $incharge->user_id }}">{{ $incharge->user_name }}</option>
                                    @endforeach
                                 </select>
                                 @if($errors->has('dprogress_incharge'))
                                 <div>{{ $errors->first('dprogress_incharge') }}</div>
                                 @endif
                                 <div id="dprogress_incharge_error"></div>
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Remarks</label>
                                 <input type="text" name="dprogress_remarks" class="form-control" value="{{ old('dprogress_remarks') }}" autocomplete="off">
                                 @if($errors->has('dprogress_remarks'))
                                 <div>{{ $errors->first('dprogress_remarks') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Mark As Carry Forward<span class="text-danger ms-1">*</span></label>
                                 <div class="mt-2">
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="cforward_yes" name="dprogress_cforward" class="form-check-input" value="1">
                                        <label class="form-check-label" for="cforward_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="cforward_no" name="dprogress_cforward" class="form-check-input" value="2" checked>
                                        <label class="form-check-label" for="cforward_no">No</label>
                                    </div>
                                </div>
                                 @if($errors->has('dprogress_cforward'))
                                 <div>{{ $errors->first('dprogress_cforward') }}</div>
                                 @endif
                                 <div id="dprogress_cforward_error"></div>
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">% Work Done: <span id="dprogress_sdone">0</span> <span class="text-danger ms-1">*</span></label>
                                 <input type="range" class="form-range" name="dprogress_pdone" id="dprogress_pdone" value="0" min="0" max="100" onchange="select_work_done()">
                                 @if($errors->has('dprogress_pdone'))
                                 <div>{{ $errors->first('dprogress_pdone') }}</div>
                                 @endif
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  @include('user.progress.evidence-fields')
                  <div class="d-flex justify-content-end flex-wrap align-items-center gap-2">
                     <button type="submit" name="submit" class="btn btn-primary" id="save-basic-info" value="submit">Submit</button>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
@section('custom_script')
<script>
function select_boq()
{
	var project_id = $("#dprogress_project").val();

	var csrf = "{{ csrf_token() }}";

	$.ajax({
	    url: "{{ route('user.select_boq') }}",
	    type: "post",
	    data: "project_id="+project_id+'&_token='+csrf,
	    success: function (data) {

	       $("#dprogress_boq").html(data);
	    }
	});
}

function select_work_done()
{
   $("#dprogress_sdone").text($("#dprogress_pdone").val());
}
</script>
@endsection