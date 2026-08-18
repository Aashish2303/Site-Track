<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Complaint Status Update</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; color: #333; }
        .container { max-width: 600px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; }
        .header { background: #08BDC9; color: white; text-align: center; padding: 15px; border-radius: 8px 8px 0 0; }
        .status { font-size: 18px; font-weight: bold; color: #08BDC9; }
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
        <p>Hi {{ $customer_name }},</p>
        <p>Your complaint status has been updated.</p>
        <p><strong>Complaint Date:</strong> {{ $complaint_date }}</p>
        <p><strong>Assigned Staff:</strong> {{ $staff_name }}</p>
        <p><strong>Reason:</strong> {{ $complaint_reason }}</p>
        <p><strong>Current Status:</strong> <span class="status">{{ $status_name }}</span></p>
        <p class="footer">© {{ date('Y') }} {{ Request()->settings->settings_title }}. All rights reserved.</p>
    </div>
</body>
</html>
