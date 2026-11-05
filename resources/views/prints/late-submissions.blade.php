<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Late Submissions Report</title>
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
    <h1>Late Submissions Report</h1>
    <p class="meta">Semester: {{ $semester }} | School Year: {{ $year }} | Department: {{ $department ?? 'All Departments' }}</p>

    <table>
        <thead>
            <tr>
                <th>Semester</th>
                <th>School Year</th>
                <th>Subject Code</th>
                <th>Subject</th>
                <th>Faculty Name</th>
                <th>Faculty Department</th>
                <th>Section</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($loads as $load)
                <tr>
                    <td>{{ $load->semester->value }}</td>
                    <td>{{ $load->year }}</td>
                    <td>{{ $load->subject?->subject_code ?? '-' }}</td>
                    <td>{{ $load->subject?->name ?? '-' }}</td>
                    <td>{{ $load->faculties->pluck('full_name')->filter()->implode(', ') ?: '-' }}</td>
                    <td>{{ $load->faculties->pluck('department.name')->filter()->unique()->implode(', ') ?: '-' }}</td>
                    <td>{{ $load->section?->code ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No records found for the selected filters.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
