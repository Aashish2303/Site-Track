@extends('user.layouts.app')
@section('title', 'Add User - ' . Request()->settings->settings_title)
@section('contents')
<div class="content">
   <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
      <div class="breadcrumb-arrow">
         <h4 class="mb-1">Add User</h4>
         <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
               <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
               <li class="breadcrumb-item"><a href="{{ route('user.users') }}">Users</a></li>
               <li class="breadcrumb-item active">Add User</li>
            </ol>
         </div>
      </div>
      <div class="gap-2 d-flex align-items-center flex-wrap">
         <a href="{{ route('user.add_user') }}" class="btn btn-icon btn-white" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
         <a href="{{ route('user.users') }}" class="btn btn-secondary"><i class="ti ti-arrow-left me-1"></i>Back</a>
      </div>
   </div>
   <div class="row row-gap-3 vertical-tab">
      <div class="col-xl-12 col-lg-12">
         <div class="patient-form-wizard flex-fill">
            <div class="form-wizard-content active">
               <form id="user_form" action="{{ route('user.add_user') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="card">
                     <div class="card-header">
                        <h5 class="mb-0">Add User</h5>
                     </div>
                     <div class="card-body pb-0">
                        <div class="mb-3">
                           <label class="form-label">Profile Image</label>
                           <div class="d-flex align-items-center flex-wrap gap-3">
                              <div class="flex-shrink-0">
                                 <div class="position-relative d-flex align-items-center border rounded">
                                    <img src="{{ asset(config('constants.admin_path').'img/no_image.png') }}" id="user_image_src" class="avatar avatar-xxl" alt="">
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
                        <div class="row mb-3">
                           <div class="col-xl-6 col-md-6">
                              <div class="mb-3">
                                 <label class="form-label">Name<span class="text-danger ms-1">*</span></label>
                                 <input type="text" name="user_name" class="form-control" value="{{ old('user_name') }}" autocomplete="off">
                                 @if($errors->has('user_name'))
                                 <div>{{ $errors->first('user_name') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-6 col-md-6">
                              <div class="mb-3">
                                 <label class="form-label">Role<span class="text-danger ms-1">*</span></label>
                                 <select name="user_role" class="form-control select2" data-toggle="select2">
                                    <option value="">Select</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->role_id }}">{{ $role->role_name }}</option>
                                    @endforeach
                                 </select>
                                 @if($errors->has('user_role'))
                                 <div>{{ $errors->first('user_role') }}</div>
                                 @endif
                              </div>
                              <div id="user_role_error"></div>
                           </div>
                           <div class="col-xl-6 col-md-6">
                              <div class="mb-3">
                                 <label class="form-label">Username<span class="text-danger ms-1">*</span></label>
                                 <input type="text" name="user_lname" class="form-control" value="{{ old('user_lname') }}" autocomplete="off">
                                 @if($errors->has('user_lname'))
                                 <div>{{ $errors->first('user_lname') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-6 col-md-6">
                              <div class="mb-3">
                                 <label class="form-label">Email<span class="text-danger ms-1">*</span></label>
                                 <input type="text" name="user_email" class="form-control" value="{{ old('user_email') }}" autocomplete="off">
                                 @if($errors->has('user_email'))
                                 <div>{{ $errors->first('user_email') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-6 col-md-6">
                              <div class="mb-3">
                                 <label class="form-label">Password<span class="text-danger ms-1">*</span></label>
                                 <div class="input-group">
                                    <input type="password" name="user_password" id="user_password" class="form-control" autocomplete="new-password">
                                    <div class="input-group-text" data-st-click="show_password()">
                                       <i class="ti ti-eye"></i>
                                    </div>
                                 </div>
                                 <div id="user_password_error"></div><small class="text-muted">Use at least 12 characters, including a letter and a number.</small>
                                 @if($errors->has('user_password'))
                                 <div>{{ $errors->first('user_password') }}</div>
                                 @endif
                              </div>
                           </div>
                           <div class="col-xl-6 col-md-6">
                              <div class="mb-3">
                                 <label class="form-label">Mobile No<span class="text-danger ms-1">*</span></label>
                                 <input type="text" name="user_phone" class="form-control" value="{{ old('user_phone') }}" autocomplete="off">
                                 @if($errors->has('user_phone'))
                                 <div>{{ $errors->first('user_phone') }}</div>
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
<script nonce="{{ app(\App\Security\SecurityPolicy::class)->nonce() }}">
function show_password() {
   var x = document.getElementById("user_password");
   if (x.type === "password") {
      x.type = "text";
   } else {
      x.type = "password";
   }
}

user_image.onchange = evt => {
  const [file] = user_image.files
  if (file) {
    user_image_src.src = URL.createObjectURL(file)
  }
}
</script>
@endsection
