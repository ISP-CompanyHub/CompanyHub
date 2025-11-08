<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Company Structure</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        .org-chart,
        .org-chart ul {
            list-style-type: none;
            position: relative;
        }

        .org-chart {
            padding-left: 10px;
        }

        .org-chart ul {
            padding-left: 25px;
            margin-left: 10px;
        }

        .org-chart li {
            position: relative;
            padding-top: 4px;
            padding-bottom: 4px;
        }

        .node {
            display: inline-block;
            border: 1px solid #aaa;
            padding: 6px 10px;
            border-radius: 4px;
            background-color: #f9f9f9;
            min-width: 180px;
            line-height: 1.4;
        }

        .node-department {
            background-color: #eaeaea;
            font-weight: bold;
        }

        .node-employee {
            font-size: 11px;
        }

        .node .lead {
            font-style: italic;
            font-weight: normal;
            color: #333;
            display: block;
            font-size: 11px;
        }

        .node .job-title {
            color: #333;
            display: block;
        }

        .org-chart li::before,
        .org-chart li::after {
            content: '';
            position: absolute;
            left: -15px;
        }

        .org-chart li::before {
            border-top: 1px solid #999;
            top: 18px;
            width: 15px;
            height: 0;
        }

        .org-chart li::after {
            border-left: 1px solid #999;
            top: 0;
            width: 0;
            height: 100%;
        }

        .org-chart li:first-child::after {
            top: 18px;
            height: 100%;
        }

        .org-chart li:last-child::after {
            height: 18px;
        }

        .org-chart li:only-child::after {
            display: none;
        }
    </style>
</head>
<body>
<h1>CompanyHub structure</h1>

<ul class="org-chart">
    @foreach($departments as $department)
        <li>
            <div class="node node-department">
                {{ $department->name }}
                @if($department->lead)
                    <span class="lead">({{ $department->lead->name }})</span>
                @endif
            </div>

            @if($department->employees->isNotEmpty())
                <ul>
                    @foreach($department->employees as $employee)
                        <li>
                            <div class="node node-employee">
                                {{ $employee->name }} {{ $employee->surname }}
                                <small class="job-title">{{ $employee->job_title ?? '—' }}</small>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </li>
    @endforeach
</ul>
</body>
</html>
