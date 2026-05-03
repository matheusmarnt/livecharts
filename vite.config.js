import { defineConfig } from 'vite';

const chartPlugin = (entry, name) => ({
    entry,
    name,
    external: ['chart.js'],
    globals: { 'chart.js': 'Chart' },
});

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
    'chartjs-treemap': chartPlugin('resources/js/plugins/chartjs-treemap.js', 'LiveChartsTreemap'),
    'chartjs-matrix': chartPlugin('resources/js/plugins/chartjs-matrix.js', 'LiveChartsMatrix'),
    'chartjs-sankey': chartPlugin('resources/js/plugins/chartjs-sankey.js', 'LiveChartsSankey'),
    'chartjs-financial': chartPlugin('resources/js/plugins/chartjs-financial.js', 'LiveChartsFinancial'),
    'chartjs-luxon': {
        entry: 'resources/js/plugins/chartjs-luxon.js',
        name: 'LiveChartsLuxon',
        external: [],
        globals: {},
    },
    'chartjs-adapter-luxon': {
        entry: 'resources/js/plugins/chartjs-adapter-luxon.js',
        name: 'LiveChartsLuxonAdapter',
        external: ['chart.js', 'luxon'],
        globals: { 'chart.js': 'Chart', luxon: 'luxon' },
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
