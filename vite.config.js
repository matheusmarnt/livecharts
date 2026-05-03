import { defineConfig } from 'vite';

const targets = {
    livecharts: {
        entry: 'resources/js/livecharts.js',
        name: 'LiveCharts',
        external: ['ApexCharts', 'Chart'],
        globals: { ApexCharts: 'ApexCharts', Chart: 'Chart' },
    },
    apexcharts: {
        entry: 'resources/js/engines/apexcharts.js',
        name: 'LiveChartsApex',
        external: [],
        globals: {},
    },
    chartjs: {
        entry: 'resources/js/engines/chartjs.js',
        name: 'LiveChartsChart',
        external: [],
        globals: {},
    },
};

export default defineConfig(({ mode }) => {
    const target = targets[mode] ?? targets.livecharts;
    const fileName = mode in targets ? mode : 'livecharts';

    return {
        build: {
            lib: {
                entry: target.entry,
                name: target.name,
                fileName: () => `${fileName}.js`,
                formats: ['iife'],
            },
            outDir: 'resources/dist',
            emptyOutDir: mode === 'livecharts',
            rollupOptions: {
                external: target.external,
                output: {
                    globals: target.globals,
                    extend: true,
                },
            },
        },
    };
});
