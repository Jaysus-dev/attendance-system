```php
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Attendance Report</title>

    <style>
        @page {
            margin: 70px;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9px;
            color: #222;
        }

        .header-table {
            width: 100%;
            margin-bottom: 10px;
        }

        .header-table td {
            vertical-align: top;
        }

        .title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
        }

        .subtitle {
            text-align: center;
            font-size: 9px;
            margin-top: 2px;
        }

        .meta {
            margin-top: 8px;
            margin-bottom: 12px;
            font-size: 9px;
        }

        .section-title {
            margin-top: 16px;
            margin-bottom: 6px;
            font-size: 11px;
            font-weight: bold;
            padding: 4px;
        }

        .present-title {
            background: #e8f5e9;
            color: #1b5e20;
        }

        .absent-title {
            background: #ffebee;
            color: #b71c1c;
        }

        .late-title {
            background: #fff8e1;
            color: #e65100;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f2f2f2;
            border: 1px solid #000;
            padding: 4px;
            font-size: 8px;
        }

        td {
            border: 1px solid #000;
            padding: 3px;
            font-size: 8px;
        }

        .summary-wrapper {
            width: 100%;
            margin-top: 18px;
        }

        .summary-table {
            width: 220px;
            float: right;
        }

        .summary-table th {
            text-align: center;
        }

        .summary-table td {
            padding: 5px;
        }

        .signature-section {
            margin-top: 90px;
            width: 250px;
        }

        .signature-line {
            border-top: 1px solid #000;
            text-align: center;
            padding-top: 5px;
            font-size: 9px;
        }

        .page-break {
            page-break-after: always;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>

@php
    $presentRecords = $attendances->filter(fn($a) => strtolower($a->status) === 'present');
    $absentRecords = $attendances->filter(fn($a) => strtolower($a->status) === 'absent');
    $lateRecords = $attendances->filter(fn($a) => strtolower($a->status) === 'late');
@endphp

<!-- HEADER -->
<table class="header-table">
    <tr>
        <td width="30%">
            <strong>Date Generated:</strong><br>
            {{ now()->format('F d, Y h:i A') }}
        </td>

        <td width="40%">
            <div class="title">
                ATTENDANCE REPORT
            </div>

            <div class="subtitle">
                Student Attendance Monitoring System
            </div>
        </td>

        <td width="30%" class="text-right">
            <strong>Exported By:</strong><br>
            {{ $teacherName }}
        </td>
    </tr>
</table>

<!-- META -->
<div class="meta">
    <strong>Date Range:</strong>
    {{ $startDate }} to {{ $endDate }}
</div>

<!-- PRESENT TABLE -->
<div class="section-title present-title">
    PRESENT STUDENTS
</div>

<table>
    <thead>
        <tr>
            <th width="4%">#</th>
            <th width="24%">Student</th>
            <th width="18%">Course</th>
            <th width="18%">Subject</th>
            <th width="18%">Teacher</th>
            <th width="18%">Date</th>
        </tr>
    </thead>

    <tbody>
        @forelse($presentRecords as $index => $a)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>

                <td>
                    {{ $a->student->fullname ?? 'N/A' }}
                </td>

                <td>
                    {{ $a->classAssignment->course->course_name ?? 'N/A' }}
                </td>

                <td>
                    {{ $a->classAssignment->subject->subject_name ?? 'N/A' }}
                </td>

                <td>
                    {{ $a->teacher->name ?? 'N/A' }}
                </td>

                <td class="text-center">
                    {{ $a->date }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">
                    No Present Records
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<!-- ABSENT TABLE -->
<div class="section-title absent-title">
    ABSENT STUDENTS
</div>

<table>
    <thead>
        <tr>
            <th width="4%">#</th>
            <th width="24%">Student</th>
            <th width="18%">Course</th>
            <th width="18%">Subject</th>
            <th width="18%">Teacher</th>
            <th width="18%">Date</th>
        </tr>
    </thead>

    <tbody>
        @forelse($absentRecords as $index => $a)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>

                <td>
                    {{ $a->student->fullname ?? 'N/A' }}
                </td>

                <td>
                    {{ $a->classAssignment->course->course_name ?? 'N/A' }}
                </td>

                <td>
                    {{ $a->classAssignment->subject->subject_name ?? 'N/A' }}
                </td>

                <td>
                    {{ $a->teacher->name ?? 'N/A' }}
                </td>

                <td class="text-center">
                    {{ $a->date }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">
                    No Absent Records
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<!-- LATE TABLE -->
<div class="section-title late-title">
    LATE STUDENTS
</div>

<table>
    <thead>
        <tr>
            <th width="4%">#</th>
            <th width="24%">Student</th>
            <th width="18%">Course</th>
            <th width="18%">Subject</th>
            <th width="18%">Teacher</th>
            <th width="18%">Date</th>
        </tr>
    </thead>

    <tbody>
        @forelse($lateRecords as $index => $a)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>

                <td>
                    {{ $a->student->fullname ?? 'N/A' }}
                </td>

                <td>
                    {{ $a->classAssignment->course->course_name ?? 'N/A' }}
                </td>

                <td>
                    {{ $a->classAssignment->subject->subject_name ?? 'N/A' }}
                </td>

                <td>
                    {{ $a->teacher->name ?? 'N/A' }}
                </td>

                <td class="text-center">
                    {{ $a->date }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">
                    No Late Records
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

<!-- SUMMARY -->
<div class="summary-wrapper">

    <table class="summary-table">
        <thead>
            <tr>
                <th colspan="2">
                    ATTENDANCE SUMMARY
                </th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>Total Present</td>
                <td class="text-center">
                    {{ count($presentRecords) }}
                </td>
            </tr>

            <tr>
                <td>Total Absent</td>
                <td class="text-center">
                    {{ count($absentRecords) }}
                </td>
            </tr>

            <tr>
                <td>Total Late</td>
                <td class="text-center">
                    {{ count($lateRecords) }}
                </td>
            </tr>

            <tr>
                <td>
                    <strong>Total Records</strong>
                </td>

                <td class="text-center">
                    <strong>{{ count($attendances) }}</strong>
                </td>
            </tr>
        </tbody>
    </table>

</div>

<!-- SIGNATURE -->
<div class="signature-section">

    <div class="signature-line">
        {{ $teacherName }}<br>
        Authorized Signature
    </div>

</div>

</body>
</html>
