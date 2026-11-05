export function SectionHeading({ eyebrow, title, text }: { eyebrow: string; title: string; text?: string }) {
    return (
        <div className="max-w-2xl">
            <p className="text-xs font-semibold tracking-widest text-brand uppercase">{eyebrow}</p>
            <h2 className="mt-3 text-3xl font-semibold tracking-tight text-ink">{title}</h2>
            {text && <p className="mt-4 leading-relaxed text-muted">{text}</p>}
        </div>
    );
}
