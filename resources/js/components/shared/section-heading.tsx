export function SectionHeading({ eyebrow, title, text }: { eyebrow: string; title: string; text?: string }) {
    return (
        <div className="max-w-2xl">
            <p className="flex items-center gap-3 text-xs font-semibold tracking-widest text-gold-dark uppercase">
                <span className="h-0.5 w-8 bg-gold" aria-hidden="true" />
                {eyebrow}
            </p>
            <h2 className="mt-3 text-3xl font-semibold tracking-tight text-brand">{title}</h2>
            {text && <p className="mt-4 leading-relaxed text-muted">{text}</p>}
        </div>
    );
}
