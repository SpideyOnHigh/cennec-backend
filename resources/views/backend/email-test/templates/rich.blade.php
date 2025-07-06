<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Rich Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        img {
            max-width: 150px;
            height: auto;
            display: block;
            margin: 0 auto 20px;
        }

        h1 {
            color: #333;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }

        p {
            font-size: 16px;
            line-height: 1.5;
            margin: 0 0 15px;
        }

        .code {
            font-size: 18px;
            font-weight: bold;
            background-color: #e9ecef;
            padding: 12px;
            border-radius: 4px;
            display: inline-block;
            margin: 10px 0;
            text-align: center;
            color: #333;
        }

        .footer {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 4px;
            text-align: center;
            font-size: 14px;
        }

        .social-icons {
            margin: 10px 0;
        }

        .social-icons a {
            text-decoration: none;
            color: #555;
            margin: 0 12px;
            font-size: 18px;
            transition: color 0.3s;
        }

        .social-icons a:hover {
            color: #007bff;
        }

        .footer p {
            margin: 5px 0;
        }

        .footer a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <div class="container">
        <img src="{{ URL::asset('build/images/logo.png') }}" alt="Company Logo" />
        <h1>Your Password Reset Request</h1>
        <p>Hello {{ $firstName }},</p>
        <p>We have received a request from you to help you reset your forgotten password. Please use this one-time,
            time-based code to authenticate your request.</p>
        <p class="code">Authentication code: {{ $otp }}</p>
        <p>This is a time-sensitive code. Please enter it immediately to proceed with resetting your password.</p>
        <p>This is a system-generated email, so please keep in mind that we will not receive any replies to this
            message.</p>
        <p>If you did not request a password reset for a {{ env('APP_NAME') }} account, you can safely ignore this
            message.</p>
        <div class="footer">
            <p>CONNECT WITH US</p>
            <div class="social-icons">
                <a href="https://instagram.com" target="_blank">Instagram</a> |
                <a href="https://twitter.com" target="_blank">Twitter</a> |
                <a href="https://tiktok.com" target="_blank">TikTok</a>
            </div>
            <p>This email was sent to {{ $email }}</p>
            <p>This email was sent by {{ env('APP_NAME') }} &copy; {{ date('Y') }}. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
