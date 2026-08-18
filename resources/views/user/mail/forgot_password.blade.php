<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Forgot Password?</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; color: #333; }
        .container { max-width: 600px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; }
        .header { background: #08BDC9; color: white; text-align: center; padding: 15px; border-radius: 8px 8px 0 0; }
        .button { background: #08BDC9; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin-top: 15px; }
        .footer { text-align: center; color: #777; font-size: 13px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>
                <img src="{{ asset(config('constants.admin_path').'uploads/logo/'.Request()->settings->settings_wlogo) }}" style="height: 50px" alt="">
            </h2>
        </div>
        <p>Hi {{ $name }},</p>
        <p>Your account password below to log in:</p>
        <p style="font-size: 18px; font-weight: bold; color: #08BDC9;">{{ $password }}</p>
        <a href="{{ route('user.login') }}" class="button" style="color: white">Login Now</a>
        <p class="footer">© {{ date('Y') }} {{ Request()->settings->settings_title }}. All rights reserved.</p>
    </div>
</body>
</html>