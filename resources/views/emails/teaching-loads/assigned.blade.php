<p>Good day {{ $recipientName ?? ($load->faculties->first()?->full_name ?? 'Faculty') }},</p>

<p>You have been assigned a new teaching load. Please prepare and submit your grading sheet on or before the deadline below.</p>

<ul>
    <li><strong>Subject:</strong> {{ $load->subject->name }} ({{ $load->subject->units }} units)</li>
    <li><strong>Section:</strong> {{ $load->section->code }} - {{ $load->section->course?->code ?? '-' }}</li>
    <li><strong>Academic Year:</strong> {{ $load->year }}</li>
    <li><strong>Semester:</strong> {{ $load->semester->value }}</li>
    <li><strong>Schedule:</strong> {{ $load->schedule ?: 'Not specified' }}</li>
    <li><strong>Submission Deadline:</strong> {{ $load->due_date?->format('F d, Y') }}</li>
</ul>

<p>This is a reminder for physical (in-person) submission only.</p>

<p>Thank you,<br>Registrar Office</p>
