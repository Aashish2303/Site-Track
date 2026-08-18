@extends('user.layouts.app')
@section('title', 'Add Issue - ' . Request()->settings->settings_title)
@section('contents')
<div class="content">
   <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
      <div class="breadcrumb-arrow">
         <h4 class="mb-1">Add Issue</h4>
         <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
               <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
               <li class="breadcrumb-item"><a href="{{ route('user.issues') }}">Issues</a></li>
               <li class="breadcrumb-item active">Add Issue</li>
            </ol>
         </div>
      </div>
      <div class="gap-2 d-flex align-items-center flex-wrap">
         <a href="{{ route('user.add_issue') }}" class="btn btn-icon btn-white" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
         <a href="{{ route('user.issues') }}" class="btn btn-secondary"><i class="ti ti-arrow-left me-1"></i>Back</a>
      </div>
   </div>
   <div class="row row-gap-3 vertical-tab">
      <div class="col-xl-12 col-lg-12">
         <div class="patient-form-wizard flex-fill">
            <div class="form-wizard-content active">
               <form id="issue_form" action="{{ route('user.add_issue') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="card">
                     <div class="card-header">
                        <h5 class="mb-0">Add Issue</h5>
                     </div>
                     <div class="card-body pb-0">
                        <div class="row mb-3">
                           <div class="col-xl-6 col-md-6">
                              <div class="mb-3">
                                 <label class="form-label">Date<span class="text-danger ms-1">*</span></label>
                                 <input type="date" name="issue_date" class="form-control" value="{{ old('issue_date') }}" autocomplete="off">
                                 @if($errors->has('issue_date'))
                                 <div>{{ $errors->first('issue_date') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-6 col-md-6">
                              <div class="mb-3">
                                 <label class="form-label">Project<span class="text-danger ms-1">*</span></label>
                                 <select name="issue_project" class="form-control select2" data-toggle="select2">
                                    <option value="">Select</option>
                                    @foreach($projects as $project)
                                    <option value="{{ $project->project_id }}">{{ $project->project_name }}</option>
                                    @endforeach
                                 </select>
                                 @if($errors->has('issue_project'))
                                 <div>{{ $errors->first('issue_project') }}</div>
                                 @endif
                                 <div id="issue_project_error"></div>
                              </div>
                           </div>
                           <div class="col-xl-12 col-md-12">
                              <div class="mb-3">
                                 <label class="form-label">Title<span class="text-danger ms-1">*</span></label>
                                 <input type="text" name="issue_title" class="form-control" value="{{ old('issue_title') }}" autocomplete="off">
                                 @if($errors->has('issue_title'))
                                 <div>{{ $errors->first('issue_title') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-12 col-md-12">
                              <div class="mb-3">
                                 <label class="form-label">Description<span class="text-danger ms-1">*</span></label>
                                 <input type="text" name="issue_description" class="form-control" value="{{ old('issue_description') }}" autocomplete="off">
                                 @if($errors->has('issue_description'))
                                 <div>{{ $errors->first('issue_description') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-6 col-md-6">
                              <div class="mb-3">
                                 <label class="form-label">Category<span class="text-danger ms-1">*</span></label>
                                 <select name="issue_category" class="form-control select2" data-toggle="select2">
                                    <option value="">Select</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->icategory_id }}">{{ $category->icategory_name }}</option>
                                    @endforeach
                                 </select>
                                 @if($errors->has('issue_category'))
                                 <div>{{ $errors->first('issue_category') }}</div>
                                 @endif
                                 <div id="issue_category_error"></div>
                              </div>
                           </div>
                           <div class="col-xl-6 col-md-6">
                              <div class="mb-3">
                                 <label class="form-label">Priority<span class="text-danger ms-1">*</span></label>
                                 <select name="issue_priority" class="form-control select2" data-toggle="select2">
                                    <option value="">Select</option>
                                    <option value="1">Low</option>
                                    <option value="2">Medium</option>
                                    <option value="3">High</option>
                                    <option value="4">Critical</option>
                                 </select>
                                 @if($errors->has('issue_priority'))
                                 <div>{{ $errors->first('issue_priority') }}</div>
                                 @endif
                                 <div id="issue_priority_error"></div>
                              </div>
                           </div>
                           <div class="col-xl-6 col-md-6">
                              <div class="mb-3">
                                 <label class="form-label">Assigned To<span class="text-danger ms-1">*</span></label>
                                 <select name="issue_assign" class="form-control select2" data-toggle="select2">
                                    <option value="">Select</option>
                                    @foreach($engineers as $engineer)
                                    <option value="{{ $engineer->user_id }}">{{ $engineer->user_name }}</option>
                                    @endforeach
                                 </select>
                                 @if($errors->has('issue_assign'))
                                 <div>{{ $errors->first('issue_assign') }}</div>
                                 @endif
                                 <div id="issue_assign_error"></div>
                              </div>
                           </div>
                           <div class="col-xl-6 col-md-6">
                              <div class="mb-3">
                                 <label class="form-label">Remarks</label>
                                 <input type="text" name="issue_remarks" class="form-control" value="{{ old('issue_remarks') }}" autocomplete="off">
                                 @if($errors->has('issue_remarks'))
                                 <div>{{ $errors->first('issue_remarks') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-6 col-md-6">
                              <div class="mb-3">
                                 <label class="form-label">Image</label>
                                 <input type="file" name="issue_image" class="form-control" accept="image/*">
                                 @if($errors->has('issue_image'))
                                 <div>{{ $errors->first('issue_image') }}</div>
                                 @endif
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
