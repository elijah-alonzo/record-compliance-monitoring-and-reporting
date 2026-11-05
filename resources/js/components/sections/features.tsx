import { SectionHeading } from '@/components/shared/section-heading';
import { features } from '@/data/site';

export function Features() {
    return (
        <section id="features" className="border-y border-line bg-white">
            <div className="page-width py-20">
                <SectionHeading eyebrow="Features" title="Everything the registrar needs to follow up" />
                <div className="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                    {features.map((feature) => (
                        <div key={feature.title} className="card border-l-[3px] border-l-brand p-6">
                            <h3 className="font-semibold text-ink">{feature.title}</h3>
                            <p className="mt-2 text-sm leading-relaxed text-muted">{feature.text}</p>
                        </div>
                    ))}
                </div>
            </div>
        </section>
    );
}
