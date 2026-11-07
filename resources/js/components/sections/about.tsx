import { SectionHeading } from '@/components/shared/section-heading';
import { college } from '@/data/site';

export function About() {
    return (
        <section id="about" className="border-t border-line bg-surface">
            <div className="page-width grid items-center gap-12 py-20 md:grid-cols-[1.3fr_1fr]">
                <div>
                    <SectionHeading eyebrow="About" title={college.name} />
                    <p className="mt-4 leading-relaxed text-muted">
                        Founded in {college.founded}, {college.name} is a United Methodist institution in{' '}
                        {college.city}, guided by John Wesley&rsquo;s words, &ldquo;{college.motto}.&rdquo; This
                        platform supports its academic mission by keeping grading sheet submissions visible, timely,
                        and easy to audit.
                    </p>
                    <p className="mt-4 leading-relaxed text-muted">
                        Built for the registrar and college deans, it centralizes deadlines, compliance status, and
                        submission history, so departments can act quickly, uphold standards, and keep academic
                        reporting transparent.
                    </p>
                </div>
                <div className="relative flex items-center justify-center bg-brand p-10">
                    <div className="absolute inset-3 border border-gold/40" aria-hidden="true" />
                    <div className="relative rounded-full bg-white p-2 shadow-2xl shadow-black/40">
                        <img src="/logo-01.png" alt={`${college.name} seal`} className="w-56 max-w-full" />
                    </div>
                </div>
            </div>
        </section>
    );
}
