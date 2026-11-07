import { SectionHeading } from '@/components/shared/section-heading';
import { features } from '@/data/site';

export function Features() {
    return (
        <section id="features" className="bg-white">
            <div className="page-width py-20">
                <SectionHeading
                    eyebrow="Features"
                    title="Everything the registrar needs to follow up"
                    text="One place to assign teaching loads, set deadlines, and see which grading sheets still need attention."
                />
                <div className="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    {features.map((feature, index) => (
                        <div
                            key={feature.title}
                            className="card group border-t-[3px] border-t-brand p-6 transition hover:border-t-gold hover:shadow-lg"
                        >
                            <span className="text-sm font-bold text-gold-dark">{String(index + 1).padStart(2, '0')}</span>
                            <h3 className="mt-3 font-semibold text-brand">{feature.title}</h3>
                            <p className="mt-2 text-sm leading-relaxed text-muted">{feature.text}</p>
                        </div>
                    ))}
                </div>
            </div>
        </section>
    );
}
