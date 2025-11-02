<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interview Scheduled</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
        }

        .content {
            background: #ffffff;
            padding: 30px;
            border: 1px solid #e5e7eb;
            border-top: none;
        }

        .info-box {
            background: #f9fafb;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .info-item {
            margin: 12px 0;
        }

        .info-label {
            font-weight: 600;
            color: #4b5563;
            display: inline-block;
            min-width: 120px;
        }

        .info-value {
            color: #1f2937;
        }

        .datetime-box {
            background: #eef2ff;
            border: 2px solid #667eea;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            text-align: center;
        }

        .datetime-box .date {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 8px;
        }

        .datetime-box .time {
            font-size: 20px;
            color: #4b5563;
        }

        .notes-box {
            background: #fffbeb;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }

        .button {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: 600;
        }

        .button:hover {
            background: #5568d3;
        }

        .footer {
            background: #f9fafb;
            padding: 20px;
            border: 1px solid #e5e7eb;
            border-top: none;
            border-radius: 0 0 8px 8px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }

        .mode-badge {
            display: inline-block;
            background: #dbeafe;
            color: #1e40af;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>🎉 Interview Scheduled!</h1>
    </div>

    <div class="content">
        <p>Dear {{ $candidate->name }},</p>

        <p>Great news! Your interview has been scheduled for the position of <strong>{{ $jobPosting->title }}</strong>
            at {{ config('app.name') }}.</p>

        <div class="datetime-box">
            <div class="date">📅 {{ $interview->scheduled_at->format('l, F d, Y') }}</div>
            <div class="time">🕐 {{ $interview->scheduled_at->format('h:i A') }}</div>
        </div>

        <div class="info-box">
            <h3 style="margin-top: 0; color: #667eea;">Interview Details</h3>

            <div class="info-item">
                <span class="info-label">Position:</span>
                <span class="info-value">{{ $jobPosting->title }}</span>
            </div>

            <div class="info-item">
                <span class="info-label">Interview Mode:</span>
                <span class="mode-badge">{{ ucfirst($interview->mode) }}</span>
            </div>

            @if ($interview->location)
                <div class="info-item">
                    <span class="info-label">Location:</span>
                    <span class="info-value">{{ $interview->location }}</span>
                </div>
            @endif

            <div class="info-item">
                <span class="info-label">Date:</span>
                <span class="info-value">{{ $interview->scheduled_at->format('l, F d, Y') }}</span>
            </div>

            <div class="info-item">
                <span class="info-label">Time:</span>
                <span class="info-value">{{ $interview->scheduled_at->format('h:i A') }}</span>
            </div>
        </div>

        @if ($interview->notes)
            <div class="notes-box">
                <strong>📝 Additional Notes:</strong>
                <p style="margin: 8px 0 0 0;">{{ $interview->notes }}</p>
            </div>
        @endif

        <h3 style="color: #667eea;">What to Expect</h3>
        <ul style="color: #4b5563;">
            <li>The interview will last approximately 45-60 minutes</li>
            <li>Please bring a copy of your resume</li>
            @if ($interview->mode === 'in-person')
                <li>Arrive 10 minutes early</li>
                <li>Bring a valid ID</li>
            @elseif($interview->mode === 'video')
                <li>Test your camera and microphone beforehand</li>
                <li>Find a quiet, well-lit space</li>
                <li>Meeting link will be sent separately</li>
            @else
                <li>Ensure you're in a quiet location</li>
                <li>Have your phone charged and ready</li>
            @endif
        </ul>

        <h3 style="color: #667eea;">Need to Reschedule?</h3>
        <p>If you need to reschedule this interview, please contact us as soon as possible at
            {{ config('mail.from.address') }}.</p>

        <p>We look forward to meeting you!</p>

        <p style="margin-top: 30px;">
            Best regards,<br>
            <strong>{{ config('app.name') }} Recruitment Team</strong>
        </p>
    </div>

    <div class="footer">
        <p style="margin: 0;">
            This is an automated message from {{ config('app.name') }}.<br>
            Please do not reply to this email.
        </p>
    </div>
</body>

</html>
