import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import { nativephpMobile, nativephpHotFile } from './vendor/nativephp/mobile/resources/js/vite-plugin.js';

export default defineConfig({
    plugins: [
        nativephpMobile(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.tsx', 'resources/js/backend.tsx'],
            hotFile: nativephpHotFile(),
            refresh: true,
        }),
        react(),
    ],
});
//  