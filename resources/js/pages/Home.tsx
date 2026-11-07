import { Head } from '@inertiajs/react';

import { About } from '@/components/sections/about';
import { Features } from '@/components/sections/features';
import { Hero } from '@/components/sections/hero';
import { Highlights } from '@/components/sections/highlights';
import { SiteFooter } from '@/components/sections/site-footer';
import { SiteHeader } from '@/components/shared/site-header';
import { college, systemName } from '@/data/site';

export default function Home() {
    return (
        <>
            <Head title={`${systemName} | ${college.name}`} />
            <SiteHeader />
            <main>
                <Hero />
                <Highlights />
                <Features />
                <About />
            </main>
            <SiteFooter />
        </>
    );
}
