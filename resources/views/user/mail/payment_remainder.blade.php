<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Remainder</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; color: #333; }
        .container { max-width: 600px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; }
        .header { background: #08BDC9; color: white; text-align: center; padding: 15px; border-radius: 8px 8px 0 0; }
        .footer { color: #333; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>
                <img src="{{ asset(config('constants.admin_path').'uploads/logo/'.Request()->settings->settings_wlogo) }}" style="height: 50px" alt="">
            </h2>
        </div>
        <p>Dear {{ $customer_name }},</p>
        <p>Your Payment of Rs. {{ $amount }} has been successfully submitted.</p>
        <p><strong>Due Date:</strong> {{ $due_date }}</p>
        <p>Thanks for Choosing us.</p>
        <p class="footer">
            Regards,<br>
            Soft Perfect Infotech
        </p>
    </div>
</body>
</html>
