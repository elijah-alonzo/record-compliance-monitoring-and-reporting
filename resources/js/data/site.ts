export const college = {
    name: 'John Wesley College',
    city: 'Tuguegarao City, Cagayan',
    founded: 1989,
    motto: 'The world is my parish',
    address: '57 College Avenue, San Gabriel, Tuguegarao City, Cagayan 3500, Philippines',
} as const;

export const systemName = 'Compliance Monitoring and Reporting System';

export const navLinks = [
    { label: 'Features', href: '#features' },
    { label: 'About', href: '#about' },
    { label: 'Contact', href: '#contact' },
] as const;

export const signInUrl = '/registrar/login';

export const highlights = [
    { value: '3', label: 'Submission states tracked' },
    { value: '4', label: 'Printable compliance reports' },
    { value: '3', label: 'Automatic email reminders' },
] as const;

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
