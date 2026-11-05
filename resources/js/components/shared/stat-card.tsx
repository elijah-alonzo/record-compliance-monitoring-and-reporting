import type { PreviewStat, Tone } from '@/data/site';

import { Sparkline } from './sparkline';

const noteColor: Record<Tone, string> = {
    ok: 'text-ok',
    late: 'text-late',
    wait: 'text-wait',
};

export function StatCard({ label, value, note, tone, className = '' }: PreviewStat & { className?: string }) {
    return (
        <div className={`card flex flex-col overflow-hidden ${className}`}>
            <div className="px-5 pt-5">
                <p className="text-sm text-muted">{label}</p>
                <p className="mt-2 text-3xl font-semibold text-ink">{value}</p>
                <p className={`mt-2 text-sm ${noteColor[tone]}`}>{note}</p>
            </div>
            <div className="mt-3">
                <Sparkline tone={tone} />
            </div>
        </div>
    );
}
