@extends('user.layouts.app')
@section('title', 'Add Project - ' . Request()->settings->settings_title)
@section('custom_style')
<style>
.boq-upload-box {
   background: var(--light-200);
   border: 2px dashed var(--border-color);
   border-radius: 8px;
   min-height: 168px;
   padding: 26px 16px 20px;
   text-align: center;
   display: flex;
   flex-direction: column;
   align-items: center;
   justify-content: center;
   transition: border-color .2s ease, background-color .2s ease;
}
.boq-upload-box.is-dragover {
   background: rgba(31, 109, 178, 0.06);
   border-color: var(--primary);
}
.boq-upload-icon {
   color: var(--primary);
   font-size: 32px;
   line-height: 1;
   margin-bottom: 10px;
}
.boq-upload-box h6 {
   color: var(--gray-900);
   font-size: 16px;
   font-weight: 700;
   margin-bottom: 2px;
}
.boq-upload-box p {
   color: var(--gray-500);
   font-size: 13px;
   margin-bottom: 12px;
}
.boq-upload-actions {
   display: flex;
   align-items: center;
   justify-content: center;
   gap: 10px;
   flex-wrap: wrap;
}
.boq-upload-btn {
   background: var(--primary);
   border-color: var(--primary);
   color: #ffffff;
   font-size: 13px;
   font-weight: 700;
   padding: 7px 16px;
}
.boq-upload-btn:hover,
.boq-upload-btn:focus {
   background: var(--primary-hover);
   border-color: var(--primary-hover);
   color: #ffffff;
}
.boq-template-btn {
   background: #ffffff;
   border: 1px solid var(--border-color);
   color: var(--secondary);
   font-size: 13px;
   font-weight: 700;
   padding: 7px 16px;
}
.boq-template-btn:hover,
.boq-template-btn:focus {
   background: rgba(13, 120, 88, 0.08);
   border-color: var(--secondary);
   color: var(--secondary);
}
.boq-file-name {
   color: var(--gray-600);
   display: block;
   font-size: 12px;
   margin-top: 10px;
   max-width: 100%;
   overflow: hidden;
   text-overflow: ellipsis;
   white-space: nowrap;
}
.boq-file-input {
   height: 1px;
   opacity: 0;
   overflow: hidden;
   position: absolute;
   width: 1px;
}
</style>
@endsection
@section('contents')
<div class="content">
   <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
      <div class="breadcrumb-arrow">
         <h4 class="mb-1">Add Project</h4>
         <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
               <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
               <li class="breadcrumb-item"><a href="{{ route('user.projects') }}">Projects</a></li>
               <li class="breadcrumb-item active">Add Project</li>
            </ol>
         </div>
      </div>
      <div class="gap-2 d-flex align-items-center flex-wrap">
         <a href="{{ route('user.add_project') }}" class="btn btn-icon btn-white" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
         <a href="{{ route('user.projects') }}" class="btn btn-secondary"><i class="ti ti-arrow-left me-1"></i>Back</a>
      </div>
   </div>
   <div class="row row-gap-3 vertical-tab">
      <div class="col-xl-12 col-lg-12">
         <div class="patient-form-wizard flex-fill">
            <div class="form-wizard-content active">
               <form id="project_form" action="{{ route('user.add_project') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="card">
                     <div class="card-header">
                        <h5 class="mb-0">Add Project</h5>
                     </div>
                     <div class="card-body pb-0">
                        <div class="row mb-3">
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Project<span class="text-danger ms-1">*</span></label>
                                 <input type="text" name="project_name" class="form-control" value="{{ old('project_name') }}" autocomplete="off">
                                 @if($errors->has('project_name'))
                                 <div>{{ $errors->first('project_name') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Location<span class="text-danger ms-1">*</span></label>
                                 <input type="text" name="project_location" class="form-control" value="{{ old('project_location') }}" autocomplete="off">
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
                                    <option value="{{ $client->client_id }}">{{ $client->client_name }}</option>
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
                                    <option value="{{ $engineer->user_id }}">{{ $engineer->user_name }}</option>
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
                                 <input type="date" name="project_start" class="form-control" value="{{ old('project_start') }}" autocomplete="off">
                                 @if($errors->has('project_start'))
                                 <div>{{ $errors->first('project_start') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Target Date<span class="text-danger ms-1">*</span></label>
                                 <input type="date" name="project_end" class="form-control" value="{{ old('project_end') }}" autocomplete="off">
                                 @if($errors->has('project_end'))
                                 <div>{{ $errors->first('project_end') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-4 col-md-4">
                              <div class="mb-3">
                                 <label class="form-label">Budget (₹)<span class="text-danger ms-1">*</span></label>
                                 <input type="number" name="project_budget" class="form-control" value="{{ old('project_budget') }}" autocomplete="off">
                                 @if($errors->has('project_budget'))
                                 <div>{{ $errors->first('project_budget') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-12 col-md-12">
                              <div class="mb-3">
                                 <div class="boq-upload-box" id="boqUploadBox">
                                    <div class="boq-upload-icon">
                                       <i class="ti ti-file-spreadsheet"></i>
                                    </div>
                                    <h6>Upload BOQ Excel</h6>
                                    <p>Columns: S.No, Item, Unit, Qty, Rate, Remarks</p>
                                    <div class="boq-upload-actions">
                                       <label class="btn boq-upload-btn mb-0" for="boq_file">
                                          <i class="ti ti-upload me-1"></i>Choose File
                                       </label>
                                       <a href="{{ asset(config('constants.admin_path').'excel/sample_boq.xlsx') }}" class="btn boq-template-btn" download>
                                          <i class="ti ti-download me-1"></i>Template
                                       </a>
                                    </div>
                                    <span class="boq-file-name" id="boqFileName">No file chosen</span>
                                    <input type="file" name="boq_file" id="boq_file" class="boq-file-input" accept=".xls,.xlsx">
                                 </div>
                                 @if($errors->has('boq_file'))
                                 <div>{{ $errors->first('boq_file') }}</div>
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
@section('custom_script')
<script>
document.addEventListener('DOMContentLoaded', function () {
   var uploadBox = document.getElementById('boqUploadBox');
   var fileInput = document.getElementById('boq_file');
   var fileName = document.getElementById('boqFileName');

   if (!uploadBox || !fileInput || !fileName) {
      return;
   }

   var updateFileName = function () {
      fileName.textContent = fileInput.files.length ? fileInput.files[0].name : 'No file chosen';
   };

   fileInput.addEventListener('change', updateFileName);

   ['dragenter', 'dragover'].forEach(function (eventName) {
      uploadBox.addEventListener(eventName, function (event) {
         event.preventDefault();
         uploadBox.classList.add('is-dragover');
      });
   });

   ['dragleave', 'drop'].forEach(function (eventName) {
      uploadBox.addEventListener(eventName, function (event) {
         event.preventDefault();
         uploadBox.classList.remove('is-dragover');
      });
   });

   uploadBox.addEventListener('drop', function (event) {
      if (event.dataTransfer.files.length) {
         fileInput.files = event.dataTransfer.files;
         updateFileName();
      }
   });
});
</script>
@endsection
