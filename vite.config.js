import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [tailwindcss()],
    build: {
        outDir: 'resources/dist',
        emptyOutDir: false,
        cssCodeSplit: false,
        rollupOptions: {
            input: 'resources/css/index.css',
            output: {
                assetFileNames: 'filament-laravel-tribute-template.[ext]',
            },
        },
    },
});
