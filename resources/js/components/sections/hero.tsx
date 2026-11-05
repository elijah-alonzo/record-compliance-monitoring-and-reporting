import { StatCard } from '@/components/shared/stat-card';
import { previewStats, signInUrl } from '@/data/site';

const stackPosition = ['top-0 left-0 z-10', 'top-44 right-0 z-20', 'top-[22rem] left-4 z-30'];

export function Hero() {
    return (
        <section className="relative overflow-hidden border-b border-line bg-white">
            <div
                className="absolute inset-0 bg-cover bg-center grayscale"
                style={{ backgroundImage: "url('/sys-bg.jpg')" }}
                aria-hidden="true"
            />
            <div className="absolute inset-0 bg-white/95" aria-hidden="true" />
            <div className="page-width relative grid items-center gap-12 py-16 md:py-24 lg:grid-cols-[1.15fr_1fr]">
                <div>
                    <span className="inline-block border border-line bg-surface px-3 py-1 text-xs font-medium tracking-widest text-brand uppercase">
                        School of Information Technology and Engineering
                    </span>
                    <h1 className="mt-6 text-4xl font-semibold tracking-tight text-ink md:text-5xl md:leading-[1.15]">
                        Know which grading sheets are in, late, or missing.
                    </h1>
                    <p className="mt-6 max-w-xl leading-relaxed text-muted">
                        The Faculty Submission Compliance Tracking System is a management dashboard for the SPUP
                        School of Information Technology and Engineering (SITE) to monitor and audit the punctuality
                        of grading sheet submissions.
                    </p>
                    <div className="mt-10 flex flex-wrap gap-3">
                        <a
                            href={signInUrl}
                            className="bg-brand px-6 py-3 text-sm font-medium text-white transition hover:bg-brand-dark"
                        >
                            Access dashboard
                        </a>
                        <a
                            href="#features"
                            className="border border-line bg-white px-6 py-3 text-sm font-medium text-ink transition hover:bg-surface"
                        >
                            Explore insights
                        </a>
                    </div>
                </div>

                <div className="relative mx-auto hidden h-[36rem] w-full max-w-md lg:block">
                    {previewStats.map((stat, index) => (
                        <StatCard
                            key={stat.label}
                            {...stat}
                            className={`absolute w-[85%] shadow-xl ${stackPosition[index]}`}
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
