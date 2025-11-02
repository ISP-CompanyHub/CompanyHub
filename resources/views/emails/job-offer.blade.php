<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #4F46E5;
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }

        .header h1 {
            margin: 0;
            font-size: 28px;
        }

        .content {
            background-color: #ffffff;
            padding: 30px;
            border: 1px solid #E5E7EB;
            border-top: none;
        }

        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
        }

        .highlight-box {
            background-color: #F0FDF4;
            border-left: 4px solid #10B981;
            padding: 20px;
            margin: 20px 0;
        }

        .position-title {
            font-size: 20px;
            font-weight: bold;
            color: #4F46E5;
            margin-bottom: 10px;
        }

        .salary {
            font-size: 24px;
            font-weight: bold;
            color: #10B981;
            margin: 10px 0;
        }

        .info-row {
            margin: 10px 0;
        }

        .info-label {
            font-weight: bold;
            color: #666;
        }

        .cta-button {
            display: inline-block;
            background-color: #4F46E5;
            color: white;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: bold;
        }

        .footer {
            background-color: #F9FAFB;
            padding: 20px;
            text-align: center;
            border-radius: 0 0 8px 8px;
            border: 1px solid #E5E7EB;
            border-top: none;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>🎉 Congratulations!</h1>
        <p>We're excited to extend you an offer of employment</p>
    </div>

    <div class="content">
        <div class="greeting">
            Dear {{ $jobOffer->candidate->name }},
        </div>

        <p>
            We are delighted to offer you the position at {{ config('app.name') }}!
            After careful consideration of your qualifications and interview performance,
            we believe you will be an excellent addition to our team.
        </p>

        <div class="highlight-box">
            <div class="position-title">{{ $jobOffer->candidate->jobPosting->title }}</div>

            <div class="info-row">
                <span class="info-label">Annual Salary:</span>
                <div class="salary">${{ number_format($jobOffer->salary) }}</div>
            </div>

            @if ($jobOffer->start_date)
                <div class="info-row">
                    <span class="info-label">Start Date:</span>
                    <span>{{ $jobOffer->start_date->format('F d, Y') }}</span>
                </div>
            @endif

            @if ($jobOffer->candidate->jobPosting->location)
                <div class="info-row">
                    <span class="info-label">Location:</span>
                    <span>{{ $jobOffer->candidate->jobPosting->location }}</span>
                </div>
            @endif

            @if ($jobOffer->candidate->jobPosting->employment_type)
                <div class="info-row">
                    <span class="info-label">Employment Type:</span>
                    <span>{{ $jobOffer->candidate->jobPosting->employment_type }}</span>
                </div>
            @endif
        </div>

        @if ($jobOffer->benefits)
            <h3 style="color: #4F46E5;">Benefits & Perks</h3>
            <p style="white-space: pre-wrap;">{{ $jobOffer->benefits }}</p>
        @endif

        <p>
            Please find attached the complete offer letter (PDF) with all details, terms, and conditions.
            We kindly ask you to review the document carefully and return the signed copy by
            <strong>{{ now()->addDays(7)->format('F d, Y') }}</strong>.
        </p>

        <p>
            If you have any questions or would like to discuss any aspect of this offer,
            please don't hesitate to reach out to us.
        </p>

        <p>
            We look forward to welcoming you to our team!
        </p>

        <p style="margin-top: 30px;">
            Best regards,<br>
            <strong>{{ config('app.name') }}</strong><br>
            Recruitment Department
        </p>
    </div>

    <div class="footer">
        <p>
            <strong>Offer Number:</strong> {{ $jobOffer->offer_number }}<br>
            <strong>Date Sent:</strong> {{ now()->format('F d, Y') }}
        </p>
        <p style="margin-top: 15px; color: #999; font-size: 11px;">
            This is an automated message from {{ config('app.name') }} recruitment system.<br>
            Please review the attached PDF document for the complete offer details.
        </p>
    </div>
</body>

</html>
