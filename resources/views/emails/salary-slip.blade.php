<!DOCTYPE html>
<html>
<head>
    <title>Salary Slip</title>
</head>
<body>
    <p>Dear {{ $user->name }},</p>
    
    <p>Please find attached your salary slip for the month of {{ \Carbon\Carbon::parse($month)->format('F Y') }}.</p>
    
    <p>
        <strong>Gross Salary:</strong> See attached.<br>
        <strong>Net Salary:</strong> See attached.
    </p>
    
    <p>Best regards,<br>
    {{ config('app.name') }}</p>
</body>
</html>
