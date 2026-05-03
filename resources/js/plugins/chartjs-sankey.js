import { Flow, SankeyController } from 'chartjs-chart-sankey';

if (typeof window !== 'undefined' && window.Chart) {
    window.Chart.register(SankeyController, Flow);
}
