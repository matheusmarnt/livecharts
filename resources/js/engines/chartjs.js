import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

if (typeof window !== 'undefined' && typeof window.Chart === 'undefined') {
    window.Chart = Chart;
}
