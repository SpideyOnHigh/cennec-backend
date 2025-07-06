<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;


class EmailTestService
{
    public function sendEmail($data)
    {
        $firstName = $data['first_name'];
        $email = $data['email'];
        $template = $data['template'];
        $subject = $template === 'simple' ? 'Simple Email' : 'Rich Email';
        $view = $template === 'simple' ? 'backend.email-test.templates.simple' : 'backend.email-test.templates.rich';
        $otp = rand(1000, 9999);
        // Send email
        Mail::send($view, ['firstName' => $firstName, 'otp' => $otp, 'email' => $email], function ($message) use ($email, $subject) {
            $message->to($email)
                ->subject($subject);
        });
    }
}
