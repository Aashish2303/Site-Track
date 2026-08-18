<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>Forgot Password - {{ Request()->settings->settings_title }}</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="{{ asset(config('constants.admin_path').'uploads/logo/'.Request()->settings->settings_favicon) }}">
<link rel="apple-touch-icon" href="{{ asset(config('constants.admin_path').'uploads/logo/'.Request()->settings->settings_favicon) }}">
<link rel="stylesheet" href="{{ asset(config('constants.admin_path').'css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset(config('constants.admin_path').'plugins/simplebar/simplebar.min.css') }}">
<link rel="stylesheet" href="{{ asset(config('constants.admin_path').'plugins/tabler-icons/tabler-icons.min.css') }}">
<link rel="stylesheet" href="{{ asset(config('constants.admin_path').'css/style.css') }}" id="app-style">
</head>
<body>
<div class="main-wrapper">
    <div class="container-fuild position-relative z-1">
        <div class="w-100 overflow-hidden position-relative flex-wrap d-block vh-100 bg-white lock-screen-cover">
            <div class="row">
                <div class="col-lg-6 p-0">
                    <div class="login-backgrounds login-covers auth-hero-panel d-lg-flex align-items-center justify-content-center d-none flex-wrap position-relative h-100 z-0">
                        <span class="auth-hero-shape auth-hero-shape-1"></span>
                        <span class="auth-hero-shape auth-hero-shape-2"></span>
                        <div class="authentication-card auth-hero-content">
                            <div class="authen-overlay-item w-100">
                                <div class="authen-head text-center">
                                    <div class="auth-hero-badge mx-auto">
                                        <span class="auth-hero-dot"></span>
                                        Account Help
                                    </div>
                                    <h1 class="text-white fs-28 fw-bold mb-3">Get Your Account Password</h1>
                                    <p class="text-light fw-normal text-light mb-0">Enter your registered email address to receive your current account password and sign back in to {{ Request()->settings->settings_title }}.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="row justify-content-center align-items-center overflow-auto flex-wrap vh-100">
                        <div class="col-md-8 mx-auto">
                            <form id="forgot_password_form" action="{{ route('user.forgot_password') }}" class="d-flex justify-content-center align-items-center" method="post">
                                @csrf
                                <div class="d-flex flex-column justify-content-lg-center flex-fill">
                                    <div class="card border-1 p-lg-3 shadow-md rounded-3 m-0">
                                    <div class="card-body">
                                        <div class="mb-4">
                                            <a href="javascript:void(0)">
                                            <img src="{{ asset(config('constants.admin_path').'uploads/logo/'.Request()->settings->settings_logo) }}" class="img-fluid logo m-atuo" alt="Logo">
                                            </a>
                                        </div>
                                        <div class="mb-3">
                                            <h5 class="mb-1 fw-bold">Forgot Password?</h5>
                                            <p class="text-muted mb-0">Provide your registered email address and we will send your password directly to your inbox.</p>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email<span class="text-danger ms-1">*</span></label>
                                            <div class="input-group input-group-flat">
                                                <input type="text" name="user_email" value="{{ old('user_email') }}" class="form-control border-end-0" autocomplete="off">
                                                <span class="input-group-text bg-white">
                                                <i class="ti ti-mail fs-14 text-dark"></i>
                                                </span>
                                            </div>
                                            <div id="user_email_error"></div>
                                            @if($errors->has('user_email'))
                                            <span>{{ $errors->first('user_email') }}</span>
                                            @endif
                                        </div>
                                        <div class="mb-2">
                                            <button type="submit" name="submit" class="btn bg-primary text-white w-100" value="submit">Send Password</button>
                                        </div>
                                        <div class="text-center">
                                            <h6 class="fw-normal fs-14 text-dark mb-0">Remember Password?
                                                <a href="{{ route('user.login') }}" class="ms-1 text-primary">Sign In</a>
                                            </h6>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
