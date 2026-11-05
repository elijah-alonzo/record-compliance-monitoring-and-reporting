import { createInertiaApp } from '@inertiajs/react';
import type { ComponentType } from 'react';

import '../css/public/app.css';

const pages = import.meta.glob<{ default: ComponentType }>('./pages/**/*.tsx');

createInertiaApp({
    title: (title) => (title ? `${title} - Grading Sheet Monitoring System` : 'Grading Sheet Monitoring System'),
    resolve: async (name) => {
        const page = pages[`./pages/${name}.tsx`];

        if (!page) {
            throw new Error(`Page not found: ${name}`);
        }

        return (await page()).default;
    },
});
