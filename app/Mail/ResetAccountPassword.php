<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class ResetAccountPassword extends Mailable
{
    public function __construct(public string $resetUrl) {}

    public function build(): static
    {
        return $this->subject('SiteTrack Pro — Reset your password')->view('user.mail.forgot_password');
    }
}
