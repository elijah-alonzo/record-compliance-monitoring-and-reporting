import type { Tone } from '@/data/site';

const stroke: Record<Tone, string> = {
    ok: '#16a34a',
    late: '#dc2626',
    wait: '#d97706',
};

export function Sparkline({ tone }: { tone: Tone }) {
    const color = stroke[tone];
    const line = 'M0 38 C40 38 70 36 110 28 C150 20 170 14 200 22 C230 30 260 34 300 30';

    return (
        <svg viewBox="0 0 300 44" preserveAspectRatio="none" className="h-10 w-full" aria-hidden="true">
            <path d={`${line} L300 44 L0 44 Z`} fill={color} fillOpacity="0.08" />
            <path d={line} fill="none" stroke={color} strokeWidth="2" vectorEffect="non-scaling-stroke" />
        </svg>
    );
}
