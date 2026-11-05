<p>Good day {{ $recipientName ?? ($load->faculties->first()?->full_name ?? 'Faculty') }},</p>

<p>Your teaching load grading sheet submission is now past the deadline.</p>

<ul>
    <li><strong>Subject:</strong> {{ $load->subject->name }} ({{ $load->subject->units }} units)</li>
    <li><strong>Section:</strong> {{ $load->section->code }} - {{ $load->section->course?->code ?? '-' }}</li>
    <li><strong>Academic Year:</strong> {{ $load->year }}</li>
    <li><strong>Semester:</strong> {{ $load->semester->value }}</li>
    <li><strong>Schedule:</strong> {{ $load->schedule ?: 'Not specified' }}</li>
    <li><strong>Deadline:</strong> {{ $load->due_date?->format('F d, Y') }}</li>
</ul>

<p>Please coordinate with the Registrar Office for immediate in-person submission.</p>

<p>Thank you,<br>Registrar Office</p>
