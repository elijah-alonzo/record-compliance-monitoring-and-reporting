import { SectionHeading } from '@/components/shared/section-heading';

export function About() {
    return (
        <section id="about" className="bg-surface">
            <div className="page-width grid items-center gap-12 py-20 md:grid-cols-2">
                <div>
                    <SectionHeading eyebrow="About" title="School of Information Technology and Engineering" />
                    <p className="mt-4 leading-relaxed text-muted">
                        The School of Information Technology and Engineering (SITE) at St. Paul University
                        Philippines is committed to academic excellence, industry-ready curricula, and
                        outcomes-based learning. This platform supports that mission by keeping grading submissions
                        visible, timely, and easy to audit.
                    </p>
                    <p className="mt-4 leading-relaxed text-muted">
                        Designed for SITE leadership and faculty coordination, it centralizes deadlines, compliance
                        status, and submission history so departments can act quickly, uphold standards, and
                        maintain transparent academic reporting.
                    </p>
                </div>
                <div className="card p-2">
                    <img src="/sys-bg.jpg" alt="St. Paul University Philippines campus" className="h-72 w-full object-cover" />
                </div>
            </div>
        </section>
    );
}
