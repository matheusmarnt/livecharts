import { TreemapController, TreemapElement } from 'chartjs-chart-treemap';

if (typeof window !== 'undefined' && window.Chart) {
    window.Chart.register(TreemapController, TreemapElement);
}
