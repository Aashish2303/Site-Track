@extends('user.layouts.app')
@section('title', 'Change Password - ' . Request()->settings->settings_title)
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
         <a href="{{ route('user.change_password') }}" class="btn btn-icon btn-white" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
      </div>
   </div>
   <ul class="nav nav-tabs nav-item-primary mb-3 border-bottom pb-4 mb-4 d-flex align-items-center gap-2">
      <li class="nav-item">
         <a href="{{ route('user.profile') }}" class="nav-link border rounded fw-semibold">Profile</a>
      </li>
      <li class="nav-item">
         <a href="{{ route('user.change_password') }}" class="nav-link border rounded fw-semibold active">Change Password</a>
      </li>
   </ul>
   <form id="change_password_form" action="{{ route('user.change_password') }}" method="post">
      @csrf
      <div class="card mb-0">
         <div class="card-header border-0 pb-1">
            <h5 class="mb-0 pt-2">Change Password</h5>
         </div>
         <div class="card-body">
            <div class="border-bottom mb-3 pb-3 justify-content-center">
               <div class="row">
                  <div class="col-xl-4 col-md-6">
                     <div class="mb-3 mb-lg-0">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" id="current_password" class="form-control" autocomplete="off">
                        @if($errors->has('current_password'))
                        <div>{{ $errors->first('current_password') }}</div>
                        @endif
                     </div>
                  </div>
                  <div class="col-xl-4 col-md-6">
                     <div class="mb-3 mb-lg-0">
                        <label class="form-label">New Password</label>
                        <input type="password" name="new_password" id="new_password" class="form-control" autocomplete="new-password" minlength="12">
                        @if($errors->has('new_password'))
                        <div>{{ $errors->first('new_password') }}</div>
                        @endif
                     </div>
                  </div>
                  <div class="col-xl-4 col-md-6">
                     <div class="mb-0 w-100">
                        <label class="form-label d-block">Confirm Password</label>
                        <input type="password" name="confirm_password" id="confirm_password" class="form-control w-100" autocomplete="off">
                        @if($errors->has('confirm_password'))
                        <div>{{ $errors->first('confirm_password') }}</div>
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
