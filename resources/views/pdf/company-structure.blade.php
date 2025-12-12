<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ __('CompanyHub Structure') }}</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        h1 {
            font-size: 20px;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .org-chart {
            border: 1px solid #ccc;
            padding: 15px;
            border-radius: 6px;
            background: #fff;
        }

        ul {
            list-style: none;
            padding-left: 10px;
            margin-left: 0;
        }

        /* Department Node */
        .node-department {
            width: 200px; /* narrower width for departments */
            background: #e8eefc;
            font-weight: bold;
        }

        /* Employee Node */
        .node-employee {
            width: 260px; /* employees "stick out" farther */
            margin-left: 30px; /* extra indentation beyond departments */
            background: #f3f3f3;
        }

        /* Shared node styling */
        .node {
            border: 1px solid #bbb;
            border-radius: 4px;
            padding: 8px 10px;
            margin-bottom: 8px;
        }

        .subtext {
            font-size: 11px;
            color: #555;
            margin-top: 2px;
            display: block;
        }
    </style>
</head>

<body>
<h1>{{ __('CompanyHub Structure') }}</h1>

<div class="org-chart">

    @if($departments->isEmpty())
        <p>{{ __('No departments found.') }}</p>
    @else
        <ul>
            @foreach($departments as $department)
                <li>
                    <div class="node node-department">
                        {{ $department->name }}
                        @if($department->lead)
                            <span class="subtext">
                                    {{ __('Lead') }}: {{ strtoupper($department->lead->name[0]) }}. {{ ucfirst($department->lead->surname) }}
                                </span>
                        @endif
                    </div>

                    @if($department->employees->isNotEmpty())
                        <ul>
                            @foreach($department->employees as $employee)
                                <li>
                                    <div class="node node-employee">
                                        {{ $employee->name }} {{ $employee->surname }}
                                        <span class="subtext">
                                                {{ $employee->job_title ?? '—' }}
                                            </span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                </li>
            @endforeach
        </ul>
    @endif

</div>
</body>
</html>
