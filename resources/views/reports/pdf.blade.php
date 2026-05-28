<!DOCTYPE html>
<html>
<head>
    <title>Attendance Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
        }

        .meta {
            margin-bottom: 20px;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background: #f2f2f2;
        }

        .summary {
            margin: 10px 0 20px 0;
        }
    </style>
</head>

<body>

<div class="header">
    <h2>Attendance Report</h2>
    <p>Generated Report</p>
</div>

<div class="meta">
    <p><strong>Teacher:</strong> {{ $teacherName }}</p>
    <p><strong>Date Range:</strong> {{ $startDate }} - {{ $endDate }}</p>
</div>

<div class="summary">
    <p><strong>Total Records:</strong> {{ count($attendances) }}</p>
</div>

<table>
    <thead>
        <tr>
            <th>Student</th>
            <th>Course</th>
            <th>Subject</th>
            <th>Status</th>
            <th>Date</th>
        </tr>
    </thead>

    <tbody>
        @foreach($attendances as $a)
            <tr>
                <td>{{ $a->student->fullname ?? '' }}</td>
                <td>{{ $a->classAssignment->course->course_name ?? '' }}</td>
                <td>{{ $a->classAssignment->subject->subject_name ?? '' }}</td>
                <td>{{ ucfirst($a->status) }}</td>
                <td>{{ $a->date }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>