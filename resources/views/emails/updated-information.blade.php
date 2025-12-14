<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Information Updated</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f6f8;
            font-family: Arial, Helvetica, sans-serif;
            color: #333333;
        }
        .container {
            width: 100%;
            padding: 24px 0;
        }
        .card {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }
        .header {
            padding: 20px 24px;
            background-color: #0f172a;
            color: #ffffff;
        }
        .content {
            padding: 24px;
            line-height: 1.6;
        }
        .content p {
            margin: 0 0 16px;
        }
        .footer {
            padding: 16px 24px;
            background-color: #f8fafc;
            font-size: 12px;
            color: #6b7280;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="header">
            <h2 style="margin: 0; font-size: 18px;">Account Information Updated</h2>
        </div>

        <div class="content">
            <p>Hello {{ $employee->name ?? 'there' }},</p>

            <p>
                This is a confirmation that your account information was successfully updated
                on {{ $employee->updated_at ?? now()->format('F j, Y') }}.
            </p>

            <p>
                If you did not make this change or believe this was done in error, please contact
                our support team as soon as possible.
            </p>

            <p>
                Thank you,<br>
                {{ config('app.name') }} Team
            </p>
        </div>

        <div class="footer">
            <p>
                This email was sent to {{ $employee->email ?? 'your registered email address' }}.
                Please do not reply directly to this message.
            </p>
        </div>
    </div>
</div>
</body>
</html>
