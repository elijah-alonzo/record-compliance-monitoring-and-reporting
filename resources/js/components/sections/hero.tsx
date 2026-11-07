import { college, signInUrl, systemName } from '@/data/site';

export function Hero() {
    return (
        <section className="relative overflow-hidden bg-brand text-white">
            <img
                src="/hero-img.jpg"
                alt=""
                aria-hidden="true"
                className="absolute inset-0 h-full w-full object-cover object-[70%_center]"
            />
            <div
                className="absolute inset-0 bg-brand/85 lg:bg-transparent lg:bg-linear-to-r lg:from-brand lg:from-30% lg:via-brand/80 lg:via-55% lg:to-brand/0"
                aria-hidden="true"
            />
            <div className="page-width relative flex min-h-[34rem] items-center py-20 md:min-h-[38rem] md:py-28">
                <div className="max-w-xl">
                    <span className="inline-flex items-center gap-2 border border-gold/40 bg-white/5 px-3 py-1 text-xs font-medium tracking-widest text-gold uppercase">
                        <span className="h-1.5 w-1.5 bg-gold" aria-hidden="true" />
                        {college.name}
                    </span>
                    <h1 className="mt-6 text-4xl font-semibold tracking-tight md:text-5xl md:leading-[1.15]">
                        Know which grading sheets are in, <span className="text-gold">late, or missing.</span>
                    </h1>
                    <p className="mt-6 leading-relaxed text-white/80">
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
                            className="border border-white/40 px-6 py-3 text-sm font-medium text-white transition hover:border-white hover:bg-white/10"
                        >
                            Explore features
                        </a>
                    </div>
                </div>
            </div>
        </section>
    );
}
