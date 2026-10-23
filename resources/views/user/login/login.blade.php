<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign in - {{ Request()->settings->settings_title }}</title>
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
        <form id="login_form" action="{{ route('user.login') }}" method="post" class="st-auth-form">
            @csrf
            <span class="st-eyebrow">Your workspace awaits</span>
            <h2>Welcome back.</h2>
            <p class="st-auth-form-intro">A little clarity goes a long way.<br>Sign in to pick up where you left off.</p>
            <div class="st-auth-field">
                <label class="form-label" for="user_lname">Username</label>
                <input id="user_lname" type="text" name="user_lname" value="{{ old('user_lname') }}" class="form-control" autocomplete="username" placeholder="Enter your username" aria-describedby="user_lname_error" required>
                <div id="user_lname_error" aria-live="polite">@error('user_lname')<span class="st-field-error">{{ $message }}</span>@enderror</div>
            </div>
            <div class="st-auth-field">
                <label class="form-label" for="user_password">Password</label>
                <div class="input-group pass-group">
                    <input id="user_password" type="password" name="user_password" class="form-control pass-input" autocomplete="current-password" placeholder="Enter your password" aria-describedby="user_password_error" required>
                    <button type="button" class="toggle-password" aria-label="Toggle password visibility"><i class="ti ti-eye-off" aria-hidden="true"></i></button>
                </div>
                <div id="user_password_error" aria-live="polite">@error('user_password')<span class="st-field-error">{{ $message }}</span>@enderror</div>
            </div>
            <div class="st-auth-options"><a href="{{ route('user.forgot_password') }}">Forgot your password?</a></div>
            <button type="submit" name="submit" value="submit" class="btn btn-primary">Sign in to your workspace <i class="ti ti-arrow-right" aria-hidden="true"></i></button>
            <p class="st-auth-legal">Projects. People. Progress.<br>All in one place with {{ Request()->settings->settings_title }}.</p>
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
