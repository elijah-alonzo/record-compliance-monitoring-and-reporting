export const navLinks = [
    { label: 'Features', href: '#features' },
    { label: 'About', href: '#about' },
    { label: 'Contact', href: '#contact' },
] as const;

export const signInUrl = '/registrar/login';

export type Tone = 'ok' | 'late' | 'wait';

export interface PreviewStat {
    label: string;
    value: number;
    note: string;
    tone: Tone;
}

export const previewStats: PreviewStat[] = [
    { label: 'On-Time Submissions', value: 86, note: '71.7% of all loads', tone: 'ok' },
    { label: 'Late Submissions', value: 12, note: '10.0% of all loads', tone: 'late' },
    { label: 'Pending Submissions', value: 22, note: '18.3% of all loads', tone: 'wait' },
];

export const features = [
    {
        title: 'Teaching Loads',
        text: 'Assign faculty to subjects and sections, then follow every grading sheet from pending to submitted.',
    },
    {
        title: 'Submission Deadlines',
        text: 'Set deadlines per semester and let the system flag what is on time, late, or still missing.',
    },
    {
        title: 'Compliance Reports',
        text: 'Print late, missing, and per-faculty reports without reviewing individual records by hand.',
    },
    {
        title: 'Email Reminders',
        text: 'Faculty are notified when a load is assigned, when a deadline is near, and when it is overdue.',
    },
    {
        title: 'Roles and Permissions',
        text: 'Control what each registrar account can see and change, down to individual resources.',
    },
    {
        title: 'Activity Logs',
        text: 'Every change is recorded, so submission history stays transparent and easy to audit.',
    },
] as const;

export const contactLinks = [
    { label: 'St. Paul University Philippines Facebook', href: 'https://www.facebook.com/spupofficial' },
    { label: 'St. Paul University Philippines', href: 'https://spup.edu.ph' },
    { label: 'School of Information Technology and Engineering', href: 'https://www.facebook.com/spupsite' },
] as const;
