<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Faculty Report</title>
    <style>
        :root {
            color-scheme: light;
        }
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 24px;
            color: #111827;
        }
        h1 {
            font-size: 20px;
            margin: 0 0 4px;
        }
        .meta {
            margin: 0 0 16px;
            font-size: 12px;
            color: #4b5563;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #d1d5db;
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background: #f3f4f6;
        }
        @media print {
            body {
                margin: 12px;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <h1>Faculty Report</h1>
    <p class="meta">Faculty: {{ $facultyName }} | Semester: {{ $semester }} | School Year: {{ $year }}</p>

    <table>
        <thead>
            <tr>
                <th>Subject Code</th>
                <th>Subject</th>
                <th>Section</th>
                <th>Submission Status</th>
                <th>Submission Date</th>
                <th>Remarks</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($loads as $load)
                <tr>
                    <td>{{ $load->subject?->subject_code ?? '-' }}</td>
                    <td>{{ $load->subject?->name ?? '-' }}</td>
                    <td>{{ $load->section?->code ?? '-' }}</td>
                    <td>{{ $load->submission_status }}</td>
                    <td>{{ $load->submitted_at?->format('M d, Y h:i A') ?? 'Not submitted' }}</td>
                    <td>-</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No records found for the selected filters.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
