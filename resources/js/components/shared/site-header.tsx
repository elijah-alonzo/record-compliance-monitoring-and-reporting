import { college, navLinks, signInUrl, systemName } from '@/data/site';

export function SiteHeader() {
    return (
        <header className="sticky top-0 z-30">
            <div className="hidden bg-brand-dark text-xs text-white/70 sm:block">
                <div className="page-width flex h-9 items-center justify-between gap-6">
                    <p>
                        {college.name} <span className="text-gold">·</span> {college.city}
                    </p>
                    <p className="italic">&ldquo;{college.motto}&rdquo;</p>
                </div>
            </div>
            <div className="border-b-[3px] border-gold bg-white">
                <div className="page-width flex h-18 items-center justify-between gap-6">
                    <a href="/" className="flex items-center">
                        <img src="/logo-02.png" alt={`${college.name} ${systemName}`} className="h-9 w-auto sm:h-12" />
                    </a>
                    <nav className="hidden items-center gap-8 text-sm font-medium text-brand md:flex">
                        {navLinks.map((link) => (
                            <a key={link.href} href={link.href} className="transition hover:text-gold-dark">
                                {link.label}
                            </a>
                        ))}
                    </nav>
                    <a
                        href={signInUrl}
                        className="bg-brand px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-brand-light"
                    >
                        Sign in
                    </a>
                </div>
            </div>
        </header>
    );
}
