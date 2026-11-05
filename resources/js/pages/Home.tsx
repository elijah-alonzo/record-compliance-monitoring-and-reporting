import { Head } from '@inertiajs/react';

import { About } from '@/components/sections/about';
import { Features } from '@/components/sections/features';
import { Hero } from '@/components/sections/hero';
import { SiteFooter } from '@/components/sections/site-footer';
import { SiteHeader } from '@/components/shared/site-header';

export default function Home() {
    return (
        <>
            <Head title="Faculty Submission Compliance Tracking System" />
            <SiteHeader />
            <main>
                <Hero />
                <Features />
                <About />
            </main>
            <SiteFooter />
        </>
    );
}
