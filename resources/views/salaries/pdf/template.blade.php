<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Salary Slip - {{ \Carbon\Carbon::parse($month)->format('F Y') }}</title>
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
            /* Removed text-transform: uppercase; */
        }

        .document-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-top: 20px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .slip-number {
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

        .salary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        .salary-table th {
            text-align: left;
            padding: 8px;
            background-color: #F9FAFB;
            border-bottom: 1px solid #E5E7EB;
            font-weight: bold;
            color: #333;
        }

        .salary-table td {
            padding: 8px;
            border-bottom: 1px solid #E5E7EB;
        }

        .text-right {
            text-align: right;
        }

        .text-green {
            color: #10B981;
        }

        .text-red {
            color: #EF4444;
        }

        .summary-box {
            background-color: #F0FDF4;
            padding: 15px;
            border-left: 4px solid #10B981;
            margin: 20px 0;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .summary-label {
            font-weight: bold;
            font-size: 14px;
        }

        .summary-value {
            font-size: 16px;
            font-weight: bold;
            color: #10B981;
            float: right;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #E5E7EB;
            font-size: 10px;
            color: #666;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="company-name">{{ config('app.name') }}</div>
        <div class="document-title">Salary Slip</div>
        <div class="slip-number">Slip Number: SLIP-{{ \Carbon\Carbon::parse($month)->format('Ym') }}-{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</div>
        <div class="slip-number">Date: {{ now()->format('F d, Y') }}</div>
    </div>

    <div class="section">
        <div class="section-title">Employee Information</div>
        <div class="info-row">
            <span class="label">Name:</span>
            <span class="value">{{ $user->name }} {{ $user->surname }}</span>
        </div>
        <div class="info-row">
            <span class="label">Email:</span>
            <span class="value">{{ $user->email }}</span>
        </div>
        <div class="info-row">
            <span class="label">Department:</span>
            <span class="value">{{ $user->department->name ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="label">Job Title:</span>
            <span class="value">{{ $user->job_title }}</span>
        </div>
        <div class="info-row">
            <span class="label">Pay Period:</span>
            <span class="value">{{ \Carbon\Carbon::parse($month)->format('F Y') }}</span>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Salary Details</div>
        @foreach($reportData['salaryLogs'] as $log)
            <div style="margin-bottom: 15px;">
                <div style="font-weight: bold; font-size: 12px; color: #4F46E5; margin-bottom: 5px;">
                    Period: {{ $log->period_from->format('d M Y') }} - {{ $log->period_until->format('d M Y') }}
                </div>
                <table class="salary-table">
                    <thead>
                        <tr>
                            <th>Component</th>
                            <th class="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($log->salaryComponents as $component)
                            <tr>
                                <td>{{ $component->name }}</td>
                                <td class="text-right {{ $component->sum < 0 ? 'text-red' : 'text-green' }}">
                                    {{ number_format($component->sum, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    </div>

    <div class="summary-box">
        <div style="margin-bottom: 10px;">
            <span class="summary-label">Gross Salary:</span>
            <span class="summary-value" style="color: #333;">${{ number_format($reportData['grossSalary'], 2) }}</span>
            <div style="clear: both;"></div>
        </div>
        <div>
            <span class="summary-label">Net Salary:</span>
            <span class="summary-value">${{ number_format($reportData['netSalary'], 2) }}</span>
            <div style="clear: both;"></div>
        </div>
    </div>

    <div class="footer">
        <p>{{ config('app.name') }} - Salary Department</p>
        <p>This is an official salary slip document generated on {{ now()->format('F d, Y \a\t H:i') }}</p>
    </div>
</body>

</html>