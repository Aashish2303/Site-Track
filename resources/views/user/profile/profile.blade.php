@extends('user.layouts.app')
@section('title', 'Profile - ' . Request()->settings->settings_title)
@section('contents')
<div class="content">
   <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
      <div class="breadcrumb-arrow">
         <h4 class="mb-1">Profile</h4>
         <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
               <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
               <li class="breadcrumb-item active">Profile</li>
            </ol>
         </div>
      </div>
      <div class="gap-2 d-flex align-items-center flex-wrap">
         <a href="{{ route('user.profile') }}" class="btn btn-icon btn-white" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
      </div>
   </div>
   <ul class="nav nav-tabs nav-item-primary mb-3 border-bottom pb-4 mb-4 d-flex align-items-center gap-2">
      <li class="nav-item">
         <a href="{{ route('user.profile') }}" class="nav-link border rounded fw-semibold active">Profile</a>
      </li>
      <li class="nav-item">
         <a href="{{ route('user.change_password') }}" class="nav-link border rounded fw-semibold">Change Password</a>
      </li>
   </ul>
   <form id="profile_form" action="{{ route('user.profile') }}" method="post" enctype="multipart/form-data">
      @csrf
      <div class="card mb-0">
         <div class="card-header border-0 pb-1">
            <h5 class="mb-0 pt-2">Personal Information</h5>
         </div>
         <div class="card-body">
            <div class="mb-3">
               <label class="form-label">Profile Image</label>
               <div class="d-flex align-items-center flex-wrap gap-3">
                  <div class="flex-shrink-0">
                     <div class="position-relative d-flex align-items-center border rounded">
                        @if(!empty(Auth::user()->user_image))
                        <img src="{{ asset(config('constants.admin_path').'uploads/profile/'.Auth::user()->user_image) }}" id="user_image_src" class="avatar avatar-xxl" alt="">
                        @else
                        <img src="{{ asset(config('constants.admin_path').'img/no_image.png') }}" id="user_image_src" class="avatar avatar-xxl" alt="">
                        @endif
                     </div>
                  </div>
                  <div class="d-inline-flex flex-column align-items-start">
                     <div class="d-inline-flex align-items-start gap-2">
                        <div class="drag-upload-btn btn btn-dark position-relative mb-2">
                           <i class="ti ti-arrows-exchange-2 me-1"></i>Change Image
                           <input type="file" name="user_image" id="user_image" class="form-control image-sign" accept="image/png, image/jpeg, image/jpg">
                        </div>
                     </div>
                     <span class="fs-13 text-body">Use JPEG, PNG</span>
                  </div>
               </div>
            </div>
            <div class="border-bottom mb-3 pb-3 justify-content-center">
               <div class="row">
                  <div class="col-xl-6 col-md-6">
                     <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="user_name" id="user_name" class="form-control" value="{{ Auth::user()->user_name }}" autocomplete="off">
                        @if($errors->has('user_name'))
                        <div>{{ $errors->first('user_name') }}</div>
                        @endif
                     </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                     <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="user_lname" id="user_lname" class="form-control" value="{{ Auth::user()->user_lname }}" autocomplete="off">
                        @if($errors->has('user_lname'))
                        <div>{{ $errors->first('user_lname') }}</div>
                        @endif
                     </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                     <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="text" name="user_email" id="user_email" class="form-control" value="{{ Auth::user()->user_email }}" autocomplete="off">
                        @if($errors->has('user_email'))
                        <div>{{ $errors->first('user_email') }}</div>
                        @endif
                     </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                     <div class="mb-3">
                        <label class="form-label d-block">Mobile No</label>
                        <input type="text" name="user_phone" id="user_phone" class="form-control w-100" value="{{ Auth::user()->user_phone }}" autocomplete="off">
                        @if($errors->has('user_phone'))
                        <div>{{ $errors->first('user_phone') }}</div>
                        @endif
                     </div>
                  </div>
               </div>
            </div>
            <div class="d-flex align-items-center justify-content-end gap-2">
               <button type="submit" name="submit" class="btn btn-primary" value="submit"> Submit</button>
            </div>
         </div>
      </div>
   </form>
</div>
@endsection
@section('custom_script')
<script>
user_image.onchange = evt => {
  const [file] = user_image.files
  if (file) {
    user_image_src.src = URL.createObjectURL(file)
  }
}
</script>
@endsection