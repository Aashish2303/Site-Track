@extends('user.layouts.app')
@section('title', 'Add Subcontractor - ' . Request()->settings->settings_title)
@section('contents')
<div class="content">
   <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
      <div class="breadcrumb-arrow">
         <h4 class="mb-1">Add Subcontractor</h4>
         <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
               <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
               <li class="breadcrumb-item"><a href="{{ route('user.subcontractors') }}">Subcontractors</a></li>
               <li class="breadcrumb-item active">Add Subcontractor</li>
            </ol>
         </div>
      </div>
      <div class="gap-2 d-flex align-items-center flex-wrap">
         <a href="{{ route('user.add_subcontractor') }}" class="btn btn-icon btn-white" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
         <a href="{{ route('user.subcontractors') }}" class="btn btn-secondary"><i class="ti ti-arrow-left me-1"></i>Back</a>
      </div>
   </div>
   <div class="row row-gap-3 vertical-tab">
      <div class="col-xl-12 col-lg-12">
         <div class="patient-form-wizard flex-fill">
            <div class="form-wizard-content active">
               <form id="subcontractor_form" action="{{ route('user.add_subcontractor') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="card">
                     <div class="card-header">
                        <h5 class="mb-0">Add Subcontractor</h5>
                     </div>
                     <div class="card-body pb-0">
                        <div class="row mb-3">
                           <div class="col-xl-6 col-md-6">
                              <div class="mb-3">
                                 <label class="form-label">Name<span class="text-danger ms-1">*</span></label>
                                 <input type="text" name="subcontractor_name" class="form-control" value="{{ old('subcontractor_name') }}" autocomplete="off">
                                 @if($errors->has('subcontractor_name'))
                                 <div>{{ $errors->first('subcontractor_name') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-6 col-md-6">
                              <div class="mb-3">
                                 <label class="form-label">Email<span class="text-danger ms-1">*</span></label>
                                 <input type="text" name="subcontractor_email" class="form-control" value="{{ old('subcontractor_email') }}" autocomplete="off">
                                 @if($errors->has('subcontractor_email'))
                                 <div>{{ $errors->first('subcontractor_email') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-6 col-md-6">
                              <div class="mb-3">
                                 <label class="form-label">Phone<span class="text-danger ms-1">*</span></label>
                                 <input type="text" name="subcontractor_phone" class="form-control" value="{{ old('subcontractor_phone') }}" autocomplete="off">
                                 @if($errors->has('subcontractor_phone'))
                                 <div>{{ $errors->first('subcontractor_phone') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-12 col-md-12">
                              <div class="mb-3">
                                 <label class="form-label">Address<span class="text-danger ms-1">*</span></label>
                                 <textarea name="subcontractor_address" class="form-control" autocomplete="off">{{ old('subcontractor_address') }}</textarea>
                                 @if($errors->has('subcontractor_address'))
                                 <div>{{ $errors->first('subcontractor_address') }}</div>
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
