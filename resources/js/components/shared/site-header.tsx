import { navLinks, signInUrl } from '@/data/site';

export function SiteHeader() {
    return (
        <header className="sticky top-0 z-20 border-b border-line bg-white">
            <div className="page-width flex h-16 items-center justify-between gap-6">
                <a href="/" className="flex items-center gap-3">
                    <img src="/sys-logo.png" alt="Faculty Submission Compliance Tracking System" className="h-9 w-auto" />
                </a>
                <nav className="hidden items-center gap-8 text-sm text-ink md:flex">
                    {navLinks.map((link) => (
                        <a key={link.href} href={link.href} className="transition hover:text-brand">
                            {link.label}
                        </a>
                    ))}
                </nav>
                <a
                    href={signInUrl}
                    className="bg-brand px-5 py-2 text-sm font-medium text-white transition hover:bg-brand-dark"
                >
                    Sign in
                </a>
            </div>
        </header>
    );
}
