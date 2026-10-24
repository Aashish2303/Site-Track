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
         <a href="{{ route('user.email_config_settings') }}" class="btn btn-icon btn-white" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
      </div>
   </div>
   <ul class="nav nav-tabs nav-item-primary mb-3 border-bottom pb-4 mb-4 d-flex align-items-center gap-2">
      <li class="nav-item">
         <a href="{{ route('user.settings') }}" class="nav-link border rounded fw-semibold">Settings</a>
      </li>
      <li class="nav-item">
         <a href="{{ route('user.email_config_settings') }}" class="nav-link border rounded fw-semibold active">Email Settings</a>
      </li>
   </ul>
   <form id="email_config_form" action="{{ route('user.email_config_settings') }}" method="post">
      @csrf
      <div class="card mb-0">
         <div class="card-header border-0 pb-1">
            <h5 class="mb-0 pt-2">Email Settings</h5>
         </div>
         <div class="card-body">
            <div class="border-bottom mb-3 pb-3 justify-content-center">
               <div class="row">
                  <div class="col-xl-6 col-md-6">
                     <div class="mb-3">
                        <label class="form-label">Mailer</label>
                        <input type="text" name="settings_mailer" id="settings_mailer" class="form-control" value="{{ Request()->settings->settings_mailer }}" autocomplete="off">
                        @if($errors->has('settings_mailer'))
                        <div>{{ $errors->first('settings_mailer') }}</div>
                        @endif
                     </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                     <div class="mb-3">
                        <label class="form-label">Host</label>
                        <input type="text" name="settings_host" id="settings_host" class="form-control" value="{{ Request()->settings->settings_host }}" autocomplete="off">
                        @if($errors->has('settings_host'))
                        <div>{{ $errors->first('settings_host') }}</div>
                        @endif
                     </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                     <div class="mb-3">
                        <label class="form-label">Port</label>
                        <input type="text" name="settings_port" id="settings_port" class="form-control" value="{{ Request()->settings->settings_port }}" autocomplete="off">
                        @if($errors->has('settings_port'))
                        <div>{{ $errors->first('settings_port') }}</div>
                        @endif
                     </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                     <div class="mb-3">
                        <label class="form-label d-block">Username</label>
                        <input type="text" name="settings_uname" id="settings_uname" class="form-control w-100" value="{{ Request()->settings->settings_uname }}" autocomplete="off">
                        @if($errors->has('settings_uname'))
                        <div>{{ $errors->first('settings_uname') }}</div>
                        @endif
                     </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                     <div class="mb-3">
                        <label class="form-label d-block">Password</label>
                        <input type="password" name="settings_pwd" autocomplete="new-password" id="settings_pwd" class="form-control w-100">
                        @if($errors->has('settings_pwd'))
                        <div>{{ $errors->first('settings_pwd') }}</div>
                        @endif
                     </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                     <div class="mb-3">
                        <label class="form-label d-block">Encryption</label>
                        <input type="text" name="settings_encryption" id="settings_encryption" class="form-control w-100" value="{{ Request()->settings->settings_encryption }}" autocomplete="off">
                        @if($errors->has('settings_encryption'))
                        <div>{{ $errors->first('settings_encryption') }}</div>
                        @endif
                     </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                     <div class="mb-3">
                        <label class="form-label d-block">From Address</label>
                        <input type="text" name="settings_faddress" id="settings_faddress" class="form-control w-100" value="{{ Request()->settings->settings_faddress }}" autocomplete="off">
                        @if($errors->has('settings_faddress'))
                        <div>{{ $errors->first('settings_faddress') }}</div>
                        @endif
                     </div>
                  </div>
                  <div class="col-xl-6 col-md-6">
                     <div class="mb-3">
                        <label class="form-label d-block">From Name</label>
                        <input type="text" name="settings_fname" id="settings_fname" class="form-control w-100" value="{{ Request()->settings->settings_fname }}" autocomplete="off">
                        @if($errors->has('settings_fname'))
                        <div>{{ $errors->first('settings_fname') }}</div>
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
