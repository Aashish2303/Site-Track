@extends('user.layouts.app')
@section('title', 'Edit Project - ' . Request()->settings->settings_title)
@section('contents')
<div class="content">
   <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
      <div class="breadcrumb-arrow">
         <h4 class="mb-1">Edit Project</h4>
         <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
               <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
               <li class="breadcrumb-item"><a href="{{ route('user.projects') }}">Projects</a></li>
               <li class="breadcrumb-item active">Edit Project</li>
            </ol>
         </div>
      </div>
      <div class="gap-2 d-flex align-items-center flex-wrap">
         <a href="{{ route('user.edit_project',['id'=>$project->project_id]) }}" class="btn btn-icon btn-white" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
         <a href="{{ route('user.projects') }}" class="btn btn-secondary"><i class="ti ti-arrow-left me-1"></i>Back</a>
      </div>
   </div>
   <div class="row row-gap-3 vertical-tab">
      <div class="col-xl-12 col-lg-12">
         <div class="patient-form-wizard flex-fill">
            <div class="form-wizard-content active">
               <form id="edit_project_form" action="{{ route('user.edit_project',['id'=>$project->project_id]) }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="card">
                     <div class="card-header">
                        <h5 class="mb-0">Edit Project</h5>
                     </div>
                     <div class="card-body pb-0">
                        <div class="row mb-3">
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Project<span class="text-danger ms-1">*</span></label>
                                 <input type="text" name="project_name" class="form-control" value="{{ $project->project_name }}" autocomplete="off">
                                 @if($errors->has('project_name'))
                                 <div>{{ $errors->first('project_name') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Location<span class="text-danger ms-1">*</span></label>
                                 <input type="text" name="project_location" class="form-control" value="{{ $project->project_location }}" autocomplete="off">
                                 @if($errors->has('project_location'))
                                 <div>{{ $errors->first('project_location') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Client<span class="text-danger ms-1">*</span></label>
                                 <select name="project_client" class="form-control select2" data-toggle="select2">
                                    <option value="">Select</option>
                                    @foreach($clients as $client)
                                    <option value="{{ $client->client_id }}" {{ $project->project_client == $client->client_id ? 'selected':'' }}>{{ $client->client_name }}</option>
                                    @endforeach
                                 </select>
                                 @if($errors->has('project_client'))
                                 <div>{{ $errors->first('project_client') }}</div>
                                 @endif
                                 <div id="project_client_error"></div>
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Site Engineer<span class="text-danger ms-1">*</span></label>
                                 <select name="project_engineer" class="form-control select2" data-toggle="select2">
                                    <option value="">Select</option>
                                    @foreach($engineers as $engineer)
                                    <option value="{{ $engineer->user_id }}" {{ $project->project_engineer == $engineer->user_id ? 'selected':'' }}>{{ $engineer->user_name }}</option>
                                    @endforeach
                                 </select>
                                 @if($errors->has('project_engineer'))
                                 <div>{{ $errors->first('project_engineer') }}</div>
                                 @endif
                                 <div id="project_engineer_error"></div>
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Start Date<span class="text-danger ms-1">*</span></label>
                                 <input type="date" name="project_start" class="form-control" value="{{ $project->project_start }}" autocomplete="off">
                                 @if($errors->has('project_start'))
                                 <div>{{ $errors->first('project_start') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Target Date<span class="text-danger ms-1">*</span></label>
                                 <input type="date" name="project_end" class="form-control" value="{{ $project->project_end }}" autocomplete="off">
                                 @if($errors->has('project_end'))
                                 <div>{{ $errors->first('project_end') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Budget (₹)<span class="text-danger ms-1">*</span></label>
                                 <input type="number" name="project_budget" class="form-control" value="{{ $project->project_budget }}" autocomplete="off">
                                 @if($errors->has('project_budget'))
                                 <div>{{ $errors->first('project_budget') }}</div>
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