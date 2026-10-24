<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Account help - {{ Request()->settings->settings_title }}</title>
<link rel="shortcut icon" href="{{ asset(config('constants.admin_path').'uploads/logo/'.Request()->settings->settings_favicon) }}">
<link rel="stylesheet" href="{{ asset(config('constants.admin_path').'css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset(config('constants.admin_path').'plugins/simplebar/simplebar.min.css') }}">
<link rel="stylesheet" href="{{ asset(config('constants.admin_path').'plugins/tabler-icons/tabler-icons.min.css') }}">
<link rel="stylesheet" href="{{ asset(config('constants.admin_path').'css/style.css') }}" id="app-style">
@include('user.partials.core-style')
</head>
<body class="st-auth">
<div class="main-wrapper st-auth-shell">
    @include('user.partials.auth-story')
    <main class="st-auth-form-area">
        <a class="st-brand st-auth-mobile-brand" href="{{ route('user.login') }}">@include('user.partials.brand')</a>
        <form action="{{ route('user.reset_password.submit') }}" method="post" class="st-auth-form">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <span class="st-eyebrow">Account recovery</span><h2>Choose a new password.</h2>
            <p class="st-auth-form-intro">Use at least 12 characters, including a letter and a number.</p>
            <div class="st-auth-field"><label class="form-label" for="user_email">Email address</label><input id="user_email" type="email" name="user_email" value="{{ $email }}" class="form-control" autocomplete="email" required></div>
            <div class="st-auth-field"><label class="form-label" for="password">New password</label><input id="password" name="password" type="password" class="form-control" autocomplete="new-password" minlength="12" required></div>
            <div class="st-auth-field"><label class="form-label" for="password_confirmation">Confirm password</label><input id="password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password" minlength="12" required></div>
            @foreach($errors->all() as $error)<p class="st-field-error">{{ $error }}</p>@endforeach
            <button type="submit" class="btn btn-primary">Update password</button>
            <a href="{{ route('user.forgot_password') }}" class="st-auth-back">Request a new link</a>
        </form>
    </main>
</div>
<script src="{{ asset(config('constants.admin_path').'js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'plugins/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'js/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'js/jquery.validate.min.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'js/form_validations.js') }}"></script>
<script src="{{ asset(config('constants.admin_path').'js/script.js') }}"></script>
<script>
@if(Session::has('success'))
Swal.fire({
    title: "Success!",
    text: "{{ Session::get('success') }}",
    type: "success",
    confirmButtonClass: "btn btn-primary",
    buttonsStyling: !1,
    icon: "success"
});
@endif
@if(Session::has('error'))
Swal.fire({
    title: "Warning!",
    text: "{{ Session::get('error') }}",
    type: "warning",
    confirmButtonClass: "btn btn-primary",
    buttonsStyling: !1,
    icon: "warning"
});
@endif
</script>
</body>
</html>
