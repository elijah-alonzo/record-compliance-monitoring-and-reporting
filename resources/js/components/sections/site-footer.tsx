import { college, navLinks, signInUrl, systemName } from '@/data/site';

export function SiteFooter() {
    return (
        <footer id="contact" className="border-t-[3px] border-gold bg-brand text-white">
            <div className="page-width grid gap-10 py-14 md:grid-cols-[auto_1.4fr_1fr]">
                <div className="h-20 w-20 rounded-full bg-white p-1">
                    <img src="/logo-01.png" alt={`${college.name} seal`} className="h-full w-full" />
                </div>
                <div>
                    <p className="font-semibold">{college.name}</p>
                    <p className="mt-3 max-w-sm text-sm leading-relaxed text-white/70">{college.address}</p>
                </div>
                <div>
                    <p className="font-semibold text-gold">{systemName}</p>
                    <ul className="mt-3 space-y-2 text-sm text-white/70">
                        {navLinks.map((link) => (
                            <li key={link.href}>
                                <a href={link.href} className="transition hover:text-white">
                                    {link.label}
                                </a>
                            </li>
                        ))}
                        <li>
                            <a href={signInUrl} className="transition hover:text-white">
                                Sign in
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div className="border-t border-white/10">
                <p className="page-width py-5 text-xs text-white/50">
                    &copy; {new Date().getFullYear()} {college.name}. All rights reserved.
                </p>
            </div>
        </footer>
    );
}
