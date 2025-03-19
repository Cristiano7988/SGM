import tailwindcss from '@tailwindcss/vite';
import react from '@vitejs/plugin-react';
import laravel from 'laravel-vite-plugin';
import { resolve } from 'node:path';
import { defineConfig } from 'vite';

export default defineConfig({
    server: {
        watch: {
            usePolling: true,
        },
        host: '0.0.0.0',  // Permite que o Vite seja acessível fora do contêiner
        port: 5173,
        hmr: {
            host: 'localhost',
        }
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.tsx'],
            ssr: 'resources/js/ssr.tsx',
            refresh: true,
        }),
        react(),
        tailwindcss(),
    ],
    esbuild: {
        jsx: 'automatic',
        // jsxInject: `import React from 'react'`,  // Isso injeta automaticamente o React em arquivos JSX/TSX
    },
    resolve: {
        alias: {
            'ziggy-js': resolve(__dirname, 'vendor/tightenco/ziggy'),
        },
    },
});
