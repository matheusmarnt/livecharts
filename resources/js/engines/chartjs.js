import * as ChartModule from 'chart.js';

const { Chart, registerables } = ChartModule;

Chart.register(...registerables);

if (typeof window !== 'undefined' && typeof window.Chart === 'undefined') {
    // Mirror chart.js UMD: expose the Chart class with every named export
    // attached so plugin bundles that import from 'chart.js' (mapped to the
    // global) can resolve sub-controllers and elements.
    window.Chart = Object.assign(Chart, ChartModule);
}
