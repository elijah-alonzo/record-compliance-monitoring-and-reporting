import { contactLinks } from '@/data/site';

export function SiteFooter() {
    return (
        <footer id="contact" className="bg-brand text-white">
            <div className="page-width grid gap-10 py-14 md:grid-cols-[1.2fr_1.4fr_auto]">
                <div>
                    <p className="font-semibold">St. Paul University Philippines</p>
                    <p className="mt-3 text-sm leading-relaxed text-white/70">
                        Mabini Street, Ugac Norte, Tuguegarao City, Cagayan, 3500 Philippines
                    </p>
                </div>
                <div>
                    <p className="font-semibold">Contact Links</p>
                    <ul className="mt-3 space-y-2 text-sm text-white/70">
                        {contactLinks.map((link) => (
                            <li key={link.href}>
                                <a href={link.href} target="_blank" rel="noreferrer" className="transition hover:text-white">
                                    {link.label}
                                </a>
                            </li>
                        ))}
                    </ul>
                </div>
                <div className="self-start bg-white p-3 md:justify-self-end">
                    <img src="/sys-footer.png" alt="Making a difference globally" className="h-14 w-auto" />
                </div>
            </div>
        </footer>
    );
}
