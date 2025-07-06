<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $type;
    public $name;

    /**
     * Create a new message instance.
     *
     * @param  int  $otp
     *
     * @return void
     */
    public function __construct($otp, $type, $name)
    {
        $this->otp = $otp;
        $this->type = $type;
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return \Illuminate\Contracts\Mail\Mailable
     */
    public function build()
    {
        $subject = $this->type === 1 ? 'Cennec App - Forgot Password' : 'Cennec System - OTP Verification';
        return $this->subject($subject)
            ->html($this->getHtmlContent());
    }

    /**
     * Get the HTML content for the email.
     *
     * @return string
     */
    protected function getHtmlContent()
    {
        $name = $this->name;
        $appName = env('APP_NAME');
        $otp = $this->otp;

        $contentForForgot = "
            <p>Hello $name,</p>
            <p>Welcome to $appName.</p>
            <p>We have received a password reset request from your account. In order to reset your password, please enter the following number on your activation page:</p>
            <p style='font-size: 18px; font-weight: bold; color: #333;'>$otp</p>
            <p>Please note that this code & link will remain active for 10 minutes.</p>
            <p>Best Wishes,<br>Team $appName</p>
        ";

        $contentForSignUp = "
            <p>Hello $name,</p>
            <p>Welcome to $appName.</p>
            <p>We have received your registration request. In order to verify your user, please enter the following number on your activation page.</p>
            <p style='font-size: 18px; font-weight: bold; color: #333;'>$otp</p>
            <p>Please note that this code & link will remain active for 10 minutes.</p>
            <p>Best Wishes,<br>Team $appName</p>
        ";

        $content = $this->type === 1 ? $contentForForgot : $contentForSignUp;

        $html = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <title>$appName - Forgot Password</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f4f4f4;
                    color: #333;
                    margin: 0;
                    padding: 0;
                }
                .container {
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                    background-color: #ffffff;
                    border-radius: 8px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
                h1 {
                    color: #333;
                }
                p {
                    line-height: 1.6;
                }
                .otp {
                    font-size: 18px;
                    font-weight: bold;
                    color: #333;
                }
            </style>
        </head>
        <body>
            <div class='container'>
                $content
            </div>
        </body>
        </html>
        ";

        return $html;
    }
}
