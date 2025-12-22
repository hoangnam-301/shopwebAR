<?php

// app/Mail/PasswordOtpMail.php
namespace App\Mail;

use Illuminate\Mail\Mailable;

class PasswordOtpMail extends Mailable
{
    public string $code;

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function build()
    {
        return $this->subject('Mã xác nhận đổi mật khẩu')
            ->view('emails.password_otp');
    }
}
