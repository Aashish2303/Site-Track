@extends('user.layouts.app')
@section('title', 'Edit Indent - ' . Request()->settings->settings_title)
@section('contents')
<div class="content">
   <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
      <div class="breadcrumb-arrow">
         <h4 class="mb-1">Edit Indent</h4>
         <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
               <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
               <li class="breadcrumb-item"><a href="{{ route('user.indents') }}">Material Indents</a></li>
               <li class="breadcrumb-item active">Edit Indent</li>
            </ol>
         </div>
      </div>
      <div class="gap-2 d-flex align-items-center flex-wrap">
         <a href="{{ route('user.edit_indent',['id'=>$indent->indent_id]) }}" class="btn btn-icon btn-white" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
         <a href="{{ route('user.indents') }}" class="btn btn-secondary"><i class="ti ti-arrow-left me-1"></i>Back</a>
      </div>
   </div>
   <div class="row row-gap-3 vertical-tab">
      <div class="col-xl-12 col-lg-12">
         <div class="patient-form-wizard flex-fill">
            <div class="form-wizard-content active">
               <form id="indent_form" action="{{ route('user.edit_indent',['id'=>$indent->indent_id]) }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="card">
                     <div class="card-header">
                        <h5 class="mb-0">Edit Indent</h5>
                     </div>
                     <div class="card-body pb-0">
                        <div class="row mb-2">
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Date<span class="text-danger ms-1">*</span></label>
                                 <input type="date" name="indent_date" class="form-control" value="{{ $indent->indent_date }}" autocomplete="off">
                                 @if($errors->has('indent_date'))
                                 <div>{{ $errors->first('indent_date') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Project<span class="text-danger ms-1">*</span></label>
                                 <select name="indent_project" class="form-control select2" data-toggle="select2">
                                    <option value="">Select</option>
                                    @foreach($projects as $project)
                                    <option value="{{ $project->project_id }}" {{ $indent->indent_project == $project->project_id ? 'selected':'' }}>{{ $project->project_name }}</option>
                                    @endforeach
                                 </select>
                                 @if($errors->has('indent_project'))
                                 <div>{{ $errors->first('indent_project') }}</div>
                                 @endif
                                 <div id="indent_project_error"></div>
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Urgency<span class="text-danger ms-1">*</span></label>
                                 <select name="indent_urgency" class="form-control select2" data-toggle="select2">
                                    <option value="">Select</option>
                                    <option value="1" {{ $indent->indent_urgency == 1 ? 'selected':'' }}>Normal</option>
                                    <option value="2" {{ $indent->indent_urgency == 2 ? 'selected':'' }}>High</option>
                                    <option value="3" {{ $indent->indent_urgency == 3 ? 'selected':'' }}>Critical</option>
                                 </select>
                                 @if($errors->has('indent_urgency'))
                                 <div>{{ $errors->first('indent_urgency') }}</div>
                                 @endif
                                 <div id="indent_urgency_error"></div>
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Site Engineer<span class="text-danger ms-1">*</span></label>
                                 <select name="indent_engineer" class="form-control select2" data-toggle="select2">
                                    <option value="">Select</option>
                                    @foreach($engineers as $engineer)
                                    <option value="{{ $engineer->user_id }}" {{ $indent->indent_engineer == $engineer->user_id ? 'selected':'' }}>{{ $engineer->user_name }}</option>
                                    @endforeach
                                 </select>
                                 @if($errors->has('indent_engineer'))
                                 <div>{{ $errors->first('indent_engineer') }}</div>
                                 @endif
                                 <div id="indent_engineer_error"></div>
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Notes / Specifications<span class="text-danger ms-1">*</span></label>
                                 <input type="text" name="indent_notes" class="form-control" value="{{ $indent->indent_notes }}" autocomplete="off">
                                 @if($errors->has('indent_notes'))
                                 <div>{{ $errors->first('indent_notes') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Note for Approval<span class="text-danger ms-1">*</span></label>
                                 <input type="text" name="indent_napproval" class="form-control" value="{{ $indent->indent_napproval }}" autocomplete="off">
                                 @if($errors->has('indent_napproval'))
                                 <div>{{ $errors->first('indent_napproval') }}</div>
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
