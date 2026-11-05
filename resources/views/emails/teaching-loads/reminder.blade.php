<p>Good day {{ $recipientName ?? ($load->faculties->first()?->full_name ?? 'Faculty') }},</p>

<p>This is a manual reminder regarding your teaching load grading sheet submission.</p>

<ul>
    <li><strong>Subject:</strong> {{ $load->subject->name }} ({{ $load->subject->units }} units)</li>
    <li><strong>Section:</strong> {{ $load->section->code }} - {{ $load->section->course?->code ?? '-' }}</li>
    <li><strong>Academic Year:</strong> {{ $load->year }}</li>
    <li><strong>Semester:</strong> {{ $load->semester->value }}</li>
    <li><strong>Schedule:</strong> {{ $load->schedule ?: 'Not specified' }}</li>
    <li><strong>Submission Deadline:</strong> {{ $load->due_date?->format('F d, Y') }}</li>
</ul>

@if ($sentBy)
    <p>Sent by: {{ $sentBy }} (Registrar Office)</p>
@endif

<p>Please submit physically (in person) through the Registrar Office.</p>

<p>Thank you,<br>Registrar Office</p>
