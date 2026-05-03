import { defineConfig } from 'vite';

export default defineConfig({
    build: {
        lib: {
            entry: 'resources/js/livecharts.js',
            name: 'LiveCharts',
            fileName: () => 'livecharts.js',
            formats: ['iife'],
        },
        outDir: 'resources/dist',
        emptyOutDir: true,
        rollupOptions: {
            external: ['ApexCharts', 'Chart'],
            output: {
                globals: {
                    ApexCharts: 'ApexCharts',
                    Chart: 'Chart',
                },
                extend: true,
            },
        },
    },
});
