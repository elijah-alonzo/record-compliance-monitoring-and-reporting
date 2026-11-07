import { StatCard } from '@/components/shared/stat-card';
import { college, previewStats, signInUrl, systemName } from '@/data/site';

const stackPosition = ['top-0 left-0 z-10', 'top-44 right-0 z-20', 'top-[22rem] left-4 z-30'];

export function Hero() {
    return (
        <section className="relative overflow-hidden bg-brand text-white">
            <img
                src="/logo-01.png"
                alt=""
                aria-hidden="true"
                className="pointer-events-none absolute -right-40 -bottom-40 hidden w-[44rem] opacity-[0.06] grayscale lg:block"
            />
            <div
                className="absolute inset-0 bg-[radial-gradient(ellipse_at_top_left,rgba(245,190,34,0.12),transparent_55%)]"
                aria-hidden="true"
            />
            <div className="page-width relative grid items-center gap-12 py-16 md:py-24 lg:grid-cols-[1.15fr_1fr]">
                <div>
                    <span className="inline-flex items-center gap-2 border border-gold/40 bg-white/5 px-3 py-1 text-xs font-medium tracking-widest text-gold uppercase">
                        <span className="h-1.5 w-1.5 bg-gold" aria-hidden="true" />
                        {college.name}
                    </span>
                    <h1 className="mt-6 text-4xl font-semibold tracking-tight md:text-5xl md:leading-[1.15]">
                        Know which grading sheets are in, <span className="text-gold">late, or missing.</span>
                    </h1>
                    <p className="mt-6 max-w-xl leading-relaxed text-white/75">
                        The {systemName} helps {college.name} monitor and audit the punctuality of grading sheet
                        submissions, so the registrar and deans can follow up with faculty before deadlines slip.
                    </p>
                    <div className="mt-10 flex flex-wrap gap-3">
                        <a
                            href={signInUrl}
                            className="bg-gold px-6 py-3 text-sm font-semibold text-brand transition hover:bg-gold-dark"
                        >
                            Access dashboard
                        </a>
                        <a
                            href="#features"
                            className="border border-white/30 px-6 py-3 text-sm font-medium text-white transition hover:border-white hover:bg-white/5"
                        >
                            Explore features
                        </a>
                    </div>
                </div>

                <div className="relative mx-auto hidden h-[36rem] w-full max-w-md lg:block">
                    {previewStats.map((stat, index) => (
                        <StatCard
                            key={stat.label}
                            {...stat}
                            className={`absolute w-[85%] shadow-2xl shadow-black/30 ${stackPosition[index]}`}
                        />
                    ))}
                </div>
                <div className="grid gap-3 lg:hidden">
                    {previewStats.map((stat) => (
                        <StatCard key={stat.label} {...stat} />
                    ))}
                </div>
            </div>
        </section>
    );
}
