import { highlights } from '@/data/site';

export function Highlights() {
    return (
        <section className="bg-gold text-brand">
            <div className="page-width grid gap-6 py-8 sm:grid-cols-3">
                {highlights.map((item) => (
                    <div key={item.label} className="flex items-baseline gap-3 sm:justify-center">
                        <span className="text-3xl font-bold">{item.value}</span>
                        <span className="text-sm font-medium">{item.label}</span>
                    </div>
                ))}
            </div>
        </section>
    );
}
