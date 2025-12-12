<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Leave Balance Report</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; color: #333; line-height: 1.5; }
        .header { margin-bottom: 30px; text-align: center; border-bottom: 2px solid #e2e8f0; padding-bottom: 20px; }
        .header h1 { margin: 0; font-size: 24px; color: #1a202c; }
        .header p { margin: 5px 0 0; color: #718096; font-size: 12px; }

        .meta { margin-bottom: 30px; }
        .meta table { width: 100%; border: none; }
        .meta td { padding: 5px 0; vertical-align: top; }
        .meta-label { font-weight: bold; color: #4a5568; width: 100px; }

        .summary { width: 100%; margin-bottom: 30px; border-collapse: separate; border-spacing: 10px; }
        .card {
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            width: 30%;
        }
        .card-title { font-size: 11px; text-transform: uppercase; color: #718096; font-weight: bold; margin-bottom: 5px; }
        .card-value { font-size: 24px; font-weight: bold; color: #2d3748; }
        .card-sub { font-size: 10px; color: #a0aec0; }

        .table-section h3 { border-bottom: 2px solid #e2e8f0; padding-bottom: 10px; margin-bottom: 15px; font-size: 16px; }
        table.data { width: 100%; border-collapse: collapse; }
        table.data th { background: #f7fafc; padding: 10px; text-align: left; font-size: 11px; text-transform: uppercase; color: #718096; border-bottom: 1px solid #cbd5e0; }
        table.data td { padding: 10px; border-bottom: 1px solid #edf2f7; font-size: 12px; }
        table.data tr:last-child td { border-bottom: none; }

        .badge { padding: 2px 6px; border-radius: 4px; font-size: 10px; font-weight: bold; display: inline-block; }
        .bg-vacation { background: #ebf4ff; color: #4c51bf; }
        .bg-paid { background: #f0fff4; color: #276749; }
        .bg-sick { background: #fff5f5; color: #c53030; }
        .bg-default { background: #edf2f7; color: #4a5568; }
    </style>
</head>
<body>
<div class="header">
    <h1>Leave Balance Report</h1>
    <p>Generated on {{ now()->format('F j, Y H:i') }}</p>
</div>

<div class="meta">
    <table>
        <tr>
            <td width="60%">
                <div class="meta-label">Employee:</div> {{ $results['user']->name }}<br>
                <div class="meta-label">Email:</div> {{ $results['user']->email }}
            </td>
            <td width="40%" style="text-align: right;">
                <div class="meta-label" style="display:inline-block;">From:</div> {{ $results['start']->format('M j, Y') }}<br>
                <div class="meta-label" style="display:inline-block;">To:</div> {{ $results['end']->format('M j, Y') }}
            </td>
        </tr>
    </table>
</div>

<table class="summary">
    <tr>
        <td class="card" style="background-color: #ebf8ff; border-color: #bee3f8;">
            <div class="card-title" style="color: #2b6cb0;">Earned (Accrued)</div>
            <div class="card-value">{{ $results['accrued_days'] }}</div>
            <div class="card-sub">days</div>
        </td>
        <td class="card" style="background-color: #fffaf0; border-color: #feebc8;">
            <div class="card-title" style="color: #c05621;">Vacation Taken</div>
            <div class="card-value">{{ $results['taken_days'] }}</div>
            <div class="card-sub">days</div>
        </td>
        <td class="card" style="background-color: #f0fff4; border-color: #c6f6d5;">
            <div class="card-title" style="color: #2f855a;">Net Balance</div>
            <div class="card-value">{{ $results['net_balance'] }}</div>
            <div class="card-sub">days</div>
        </td>
    </tr>
</table>

<div class="table-section">
    <h3>Full Leave History in Period</h3>
    <table class="data">
        <thead>
        <tr>
            <th>Type</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Days</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        @forelse($results['vacations'] as $vacation)
            <tr>
                <td>
                    @php
                        $class = match(strtolower($vacation->type)) {
                            'vacation' => 'bg-vacation',
                            'paid', 'paid time off' => 'bg-paid',
                            'sick' => 'bg-sick',
                            default => 'bg-default'
                        };
                    @endphp
                    <span class="badge {{ $class }}">
                                {{ ucfirst($vacation->type) }}
                            </span>
                </td>
                <td>{{ \Carbon\Carbon::parse($vacation->vacation_start)->format('Y-m-d H:i') }}</td>
                <td>{{ \Carbon\Carbon::parse($vacation->vacation_end)->format('Y-m-d H:i') }}</td>
                <td>{{ \Carbon\Carbon::parse($vacation->vacation_start)->diffInDays(\Carbon\Carbon::parse($vacation->vacation_end)) + 1 }}</td>
                <td>{{ ucfirst($vacation->status) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" style="text-align: center; color: #a0aec0;">No leave records found in this period.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
</body>
</html>
