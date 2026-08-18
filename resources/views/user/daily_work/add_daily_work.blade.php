@extends('user.layouts.app')
@section('title', 'Add Daily Work - ' . Request()->settings->settings_title)
@section('contents')
<div class="content">
   <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
      <div class="breadcrumb-arrow">
         <h4 class="mb-1">Add Daily Work</h4>
         <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
               <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
               <li class="breadcrumb-item"><a href="{{ route('user.daily_works') }}">Daily Works</a></li>
               <li class="breadcrumb-item active">Add Daily Work</li>
            </ol>
         </div>
      </div>
      <div class="gap-2 d-flex align-items-center flex-wrap">
         <a href="{{ route('user.add_daily_work') }}" class="btn btn-icon btn-white" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
         <a href="{{ route('user.daily_works') }}" class="btn btn-secondary"><i class="ti ti-arrow-left me-1"></i>Back</a>
      </div>
   </div>
   <div class="row row-gap-3 vertical-tab">
      <div class="col-xl-12 col-lg-12">
         <div class="patient-form-wizard flex-fill">
            <div class="form-wizard-content active">
               <form id="daily_work_form" action="{{ route('user.add_daily_work') }}" method="POST">
                  @csrf
                  <div class="card">
                     <div class="card-header">
                        <h5 class="mb-0">Add Daily Work</h5>
                     </div>
                     <div class="card-body pb-0">
                        <div class="row mb-3">
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Date<span class="text-danger ms-1">*</span></label>
                                 <input type="date" name="dwork_date" class="form-control" value="{{ old('dwork_date') }}" autocomplete="off">
                                 @if($errors->has('dwork_date'))
                                 <div>{{ $errors->first('dwork_date') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Project<span class="text-danger ms-1">*</span></label>
                                 <select name="dwork_project" class="form-control select2" data-toggle="select2">
                                    <option value="">Select</option>
                                    @foreach($projects as $project)
                                    <option value="{{ $project->project_id }}">{{ $project->project_name }}</option>
                                    @endforeach
                                 </select>
                                 @if($errors->has('dwork_project'))
                                 <div>{{ $errors->first('dwork_project') }}</div>
                                 @endif
                                 <div id="dwork_project_error"></div>
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Category<span class="text-danger ms-1">*</span></label>
                                 <select name="dwork_category" class="form-control select2" data-toggle="select2">
                                    <option value="">Select</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                                    @endforeach
                                 </select>
                                 @if($errors->has('dwork_category'))
                                 <div>{{ $errors->first('dwork_category') }}</div>
                                 @endif
                                 <div id="dwork_category_error"></div>
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Description Of Work<span class="text-danger ms-1">*</span></label>
                                 <input type="text" name="dwork_description" class="form-control" value="{{ old('dwork_description') }}" autocomplete="off">
                                 @if($errors->has('dwork_description'))
                                 <div>{{ $errors->first('dwork_description') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Location On Site<span class="text-danger ms-1">*</span></label>
                                 <input type="text" name="dwork_location" class="form-control" value="{{ old('dwork_location') }}" autocomplete="off">
                                 @if($errors->has('dwork_location'))
                                 <div>{{ $errors->first('dwork_location') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Quantity<span class="text-danger ms-1">*</span></label>
                                 <input type="text" name="dwork_qty" class="form-control" value="{{ old('dwork_qty') }}" autocomplete="off">
                                 @if($errors->has('dwork_qty'))
                                 <div>{{ $errors->first('dwork_qty') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Subcontractor<span class="text-danger ms-1">*</span></label>
                                 <select name="dwork_subcontractor" class="form-control select2" data-toggle="select2">
                                    <option value="">Select</option>
                                    @foreach($subcontractors as $subcontractor)
                                    <option value="{{ $subcontractor->subcontractor_id }}">{{ $subcontractor->subcontractor_name }}</option>
                                    @endforeach
                                 </select>
                                 @if($errors->has('dwork_subcontractor'))
                                 <div>{{ $errors->first('dwork_subcontractor') }}</div>
                                 @endif
                                 <div id="dwork_subcontractor_error"></div>
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Site Incharge<span class="text-danger ms-1">*</span></label>
                                 <select name="dwork_incharge" class="form-control select2" data-toggle="select2">
                                    <option value="">Select</option>
                                    @foreach($incharges as $incharge)
                                    <option value="{{ $incharge->user_id }}">{{ $incharge->user_name }}</option>
                                    @endforeach
                                 </select>
                                 @if($errors->has('dwork_incharge'))
                                 <div>{{ $errors->first('dwork_incharge') }}</div>
                                 @endif
                                 <div id="dwork_incharge_error"></div>
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Remarks</label>
                                 <input type="text" name="dwork_remarks" class="form-control" value="{{ old('dwork_remarks') }}" autocomplete="off">
                                 @if($errors->has('dwork_remarks'))
                                 <div>{{ $errors->first('dwork_remarks') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Mark As Carry Forward<span class="text-danger ms-1">*</span></label>
                                 <div class="mt-2">
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="cforward_yes" name="dwork_cforward" class="form-check-input" value="1">
                                        <label class="form-check-label" for="cforward_yes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="cforward_no" name="dwork_cforward" class="form-check-input" value="2" checked>
                                        <label class="form-check-label" for="cforward_no">No</label>
                                    </div>
                                </div>
                                 @if($errors->has('dwork_cforward'))
                                 <div>{{ $errors->first('dwork_cforward') }}</div>
                                 @endif
                                 <div id="dwork_cforward_error"></div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
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
