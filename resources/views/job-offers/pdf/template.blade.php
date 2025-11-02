<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Job Offer - {{ $jobOffer->offer_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #4F46E5;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #4F46E5;
            margin-bottom: 5px;
        }

        .document-title {
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
        }

        .offer-number {
            font-size: 10px;
            color: #666;
            margin-top: 5px;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #4F46E5;
            margin-bottom: 10px;
            border-bottom: 1px solid #E5E7EB;
            padding-bottom: 5px;
        }

        .info-row {
            margin-bottom: 8px;
        }

        .label {
            font-weight: bold;
            display: inline-block;
            width: 150px;
        }

        .value {
            display: inline;
        }

        .salary-highlight {
            background-color: #F0FDF4;
            padding: 15px;
            border-left: 4px solid #10B981;
            margin: 20px 0;
        }

        .salary-amount {
            font-size: 20px;
            font-weight: bold;
            color: #10B981;
        }

        .terms-box {
            background-color: #F9FAFB;
            padding: 15px;
            border: 1px solid #E5E7EB;
            margin: 10px 0;
            white-space: pre-wrap;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
            font-size: 10px;
            color: #666;
            text-align: center;
        }

        .signature-section {
            margin-top: 50px;
        }

        .signature-line {
            margin-top: 40px;
            border-top: 1px solid #333;
            width: 300px;
            padding-top: 5px;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="company-name">{{ config('app.name') }}</div>
        <div class="document-title">EMPLOYMENT OFFER LETTER</div>
        <div class="offer-number">Offer Number: {{ $jobOffer->offer_number }}</div>
        <div class="offer-number">Date: {{ now()->format('F d, Y') }}</div>
    </div>

    <div class="section">
        <div class="section-title">Candidate Information</div>
        <div class="info-row">
            <span class="label">Name:</span>
            <span class="value">{{ $jobOffer->candidate->name }}</span>
        </div>
        <div class="info-row">
            <span class="label">Email:</span>
            <span class="value">{{ $jobOffer->candidate->email }}</span>
        </div>
        @if ($jobOffer->candidate->phone)
            <div class="info-row">
                <span class="label">Phone:</span>
                <span class="value">{{ $jobOffer->candidate->phone }}</span>
            </div>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Position Details</div>
        <div class="info-row">
            <span class="label">Position:</span>
            <span class="value">{{ $jobOffer->candidate->jobPosting->title }}</span>
        </div>
        @if ($jobOffer->candidate->jobPosting->location)
            <div class="info-row">
                <span class="label">Location:</span>
                <span class="value">{{ $jobOffer->candidate->jobPosting->location }}</span>
            </div>
        @endif
        @if ($jobOffer->candidate->jobPosting->employment_type)
            <div class="info-row">
                <span class="label">Employment Type:</span>
                <span class="value">{{ $jobOffer->candidate->jobPosting->employment_type }}</span>
            </div>
        @endif
        @if ($jobOffer->start_date)
            <div class="info-row">
                <span class="label">Start Date:</span>
                <span class="value">{{ $jobOffer->start_date->format('F d, Y') }}</span>
            </div>
        @endif
    </div>

    <div class="salary-highlight">
        <div class="info-row">
            <span class="label">Annual Salary:</span>
            <span class="salary-amount">${{ number_format($jobOffer->salary) }}</span>
        </div>
    </div>

    @if ($jobOffer->benefits)
        <div class="section">
            <div class="section-title">Benefits & Perks</div>
            <div class="terms-box">{{ $jobOffer->benefits }}</div>
        </div>
    @endif

    @if ($jobOffer->additional_terms)
        <div class="section">
            <div class="section-title">Additional Terms & Conditions</div>
            <div class="terms-box">{{ $jobOffer->additional_terms }}</div>
        </div>
    @endif

    <div class="section">
        <div class="section-title">Offer Acceptance</div>
        <p>
            We are pleased to extend this offer of employment to you. This offer is contingent upon the successful
            completion
            of any background checks and reference verifications that may be required.
        </p>
        <p>
            Please indicate your acceptance of this offer by signing below and returning this document to us by
            <strong>{{ now()->addDays(7)->format('F d, Y') }}</strong>.
        </p>

        <div class="signature-section">
            <div style="margin-bottom: 60px;">
                <div class="signature-line">
                    <div>Candidate Signature</div>
                    <div style="margin-top: 5px;">{{ $jobOffer->candidate->name }}</div>
                </div>
            </div>

            <div>
                <div class="signature-line">
                    <div>Company Representative</div>
                    <div style="margin-top: 5px;">{{ config('app.name') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>{{ config('app.name') }} - Recruitment Department</p>
        <p>This is an official employment offer document generated on {{ now()->format('F d, Y \a\t H:i') }}</p>
    </div>
</body>

</html>
