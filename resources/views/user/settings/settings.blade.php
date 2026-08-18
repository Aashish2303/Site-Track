@extends('user.layouts.app')
@section('title', 'Settings - ' . Request()->settings->settings_title)
@section('contents')
<div class="content">
   <div class="d-flex align-items-center justify-content-between gap-2 mb-4 flex-wrap">
      <div class="breadcrumb-arrow">
         <h4 class="mb-1">Settings</h4>
         <div class="text-end">
            <ol class="breadcrumb m-0 py-0">
               <li class="breadcrumb-item"><a href="{{ route('user.dashboard') }}">Home</a></li>
               <li class="breadcrumb-item active">Settings</li>
            </ol>
         </div>
      </div>
      <div class="gap-2 d-flex align-items-center flex-wrap">
         <a href="{{ route('user.settings') }}" class="btn btn-icon btn-white" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
      </div>
   </div>
   <ul class="nav nav-tabs nav-item-primary mb-3 border-bottom pb-4 mb-4 d-flex align-items-center gap-2">
      <li class="nav-item">
         <a href="{{ route('user.settings') }}" class="nav-link border rounded fw-semibold active">Settings</a>
      </li>
      <li class="nav-item">
         <a href="{{ route('user.email_config_settings') }}" class="nav-link border rounded fw-semibold">Email Settings</a>
      </li>
   </ul>
   <form id="settings_form" action="{{ route('user.settings') }}" method="post" enctype="multipart/form-data">
      @csrf
      <div class="card mb-0">
         <div class="card-header border-0 pb-1">
            <h5 class="mb-0 pt-2">General Settings</h5>
         </div>
         <div class="card-body">
            <div class="border-bottom mb-3 pb-3 justify-content-center">
               <div class="row">
                  <div class="col-xl-6 col-md-6">
                     <div class="mb-3">
                        <label class="form-label">Project Title</label>
                        <input type="text" name="settings_title" id="settings_title" class="form-control" value="{{ Request()->settings->settings_title }}" autocomplete="off">
                        @if($errors->has('settings_title'))
                        <div>{{ $errors->first('settings_title') }}</div>
                        @endif
                     </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                     <div class="mb-3">
                        <label class="form-label">Company</label>
                        <input type="text" name="settings_company" id="settings_company" class="form-control" value="{{ Request()->settings->settings_company }}" autocomplete="off">
                        @if($errors->has('settings_company'))
                        <div>{{ $errors->first('settings_company') }}</div>
                        @endif
                     </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                     <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="text" name="settings_email" id="settings_email" class="form-control" value="{{ Request()->settings->settings_email }}" autocomplete="off">
                        @if($errors->has('settings_email'))
                        <div>{{ $errors->first('settings_email') }}</div>
                        @endif
                     </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                     <div class="mb-3">
                        <label class="form-label d-block">Phone</label>
                        <input type="text" name="settings_phone" id="settings_phone" class="form-control w-100" value="{{ Request()->settings->settings_phone }}" autocomplete="off">
                        @if($errors->has('settings_phone'))
                        <div>{{ $errors->first('settings_phone') }}</div>
                        @endif
                     </div>
                  </div>
                  <div class="col-xl-12 col-md-12">
                     <div class="mb-3">
                        <label class="form-label d-block">Address</label>
                        <textarea name="settings_address" id="settings_address" class="form-control w-100" autocomplete="off">{{ Request()->settings->settings_address }}</textarea>
                        @if($errors->has('settings_address'))
                        <div>{{ $errors->first('settings_address') }}</div>
                        @endif
                     </div>
                  </div>
                  <div class="col-xl-4 col-md-6">
                     <div class="mb-3">
                        <label class="form-label d-block">Logo</label>
                        <div>
                           @if(!empty(Request()->settings->settings_logo))
                           <img src="{{ asset(config('constants.admin_path').'uploads/logo/'.Request()->settings->settings_logo) }}" id="settings_logo_src" class="img-thumbnail" style="height: 150px" alt="">
                           @else
                           <img src="{{ asset(config('constants.admin_path').'img/no_image.jpeg') }}" id="settings_logo_src" class="img-thumbnail" style="height: 150px" alt="">
                           @endif
                        </div>
                        <input type="file" name="settings_logo" id="settings_logo" class="form-control w-100" accept="image/*">
                        @if($errors->has('settings_logo'))
                        <div>{{ $errors->first('settings_logo') }}</div>
                        @endif
                     </div>
                  </div>
                  <div class="col-xl-4 col-md-6">
                     <div class="mb-3">
                        <label class="form-label d-block">White Logo</label>
                        <div>
                           @if(!empty(Request()->settings->settings_wlogo))
                           <img src="{{ asset(config('constants.admin_path').'uploads/logo/'.Request()->settings->settings_wlogo) }}" id="settings_wlogo_src" class="img-thumbnail" style="height: 150px" alt="">
                           @else
                           <img src="{{ asset(config('constants.admin_path').'img/no_image.jpeg') }}" id="settings_wlogo_src" class="img-thumbnail" style="height: 150px" alt="">
                           @endif
                        </div>
                        <input type="file" name="settings_wlogo" id="settings_wlogo" class="form-control w-100" accept="image/*">
                        @if($errors->has('settings_wlogo'))
                        <div>{{ $errors->first('settings_wlogo') }}</div>
                        @endif
                     </div>
                  </div>
                  <div class="col-xl-4 col-md-6">
                     <div class="mb-3">
                        <label class="form-label d-block">Favicon</label>
                        <div>
                           @if(!empty(Request()->settings->settings_favicon))
                           <img src="{{ asset(config('constants.admin_path').'uploads/logo/'.Request()->settings->settings_favicon) }}" id="settings_favicon_src" class="img-thumbnail" style="height: 150px" alt="">
                           @else
                           <img src="{{ asset(config('constants.admin_path').'img/no_image.jpeg') }}" id="settings_favicon_src" class="img-thumbnail" style="height: 150px" alt="">
                           @endif
                        </div>
                        <input type="file" name="settings_favicon" id="settings_favicon" class="form-control w-100" accept="image/*">
                        @if($errors->has('settings_favicon'))
                        <div>{{ $errors->first('settings_favicon') }}</div>
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
settings_logo.onchange = evt => {
  const [file] = settings_logo.files
  if (file) {
    settings_logo_src.src = URL.createObjectURL(file)
  }
}

settings_wlogo.onchange = evt => {
  const [file] = settings_wlogo.files
  if (file) {
    settings_wlogo_src.src = URL.createObjectURL(file)
  }
}

settings_favicon.onchange = evt => {
  const [file] = settings_favicon.files
  if (file) {
    settings_favicon_src.src = URL.createObjectURL(file)
  }
}
</script>
@endsection