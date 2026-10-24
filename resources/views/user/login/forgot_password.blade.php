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
        <form id="forgot_password_form" action="{{ route('user.forgot_password') }}" method="post" class="st-auth-form">
            @csrf
            <span class="st-eyebrow">Let's get you back</span>
            <h2>Account help.</h2>
            <p class="st-auth-form-intro">Enter your registered email address to receive a password reset link.</p>
            <div class="st-auth-field">
                <label class="form-label" for="user_email">Email address</label>
                <input id="user_email" type="email" name="user_email" value="{{ old('user_email') }}" class="form-control" autocomplete="email" placeholder="you@company.com" aria-describedby="user_email_error" required>
                <div id="user_email_error" aria-live="polite">@error('user_email')<span class="st-field-error">{{ $message }}</span>@enderror</div>
            </div>
            <button type="submit" name="submit" value="submit" class="btn btn-primary">Send reset link <i class="ti ti-arrow-right" aria-hidden="true"></i></button>
            <a href="{{ route('user.login') }}" class="st-auth-back"><i class="ti ti-arrow-left" aria-hidden="true"></i>Back to sign in</a>
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
<script nonce="{{ app(\App\Security\SecurityPolicy::class)->nonce() }}">
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
