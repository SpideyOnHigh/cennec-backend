<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
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
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            width: 150px;
            margin-bottom: 20px;
        }
        .content {
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ $logoPath }}" alt="Your Logo" class="logo">
            <h1>Welcome!</h1>
        </div>
        <div class="content">
            {!! $emailContent !!}
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ $companyName }} All rights reserved.</p>
        </div>
    </div>
</body>
</html>
