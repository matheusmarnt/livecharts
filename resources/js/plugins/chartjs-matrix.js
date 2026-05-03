import { MatrixController, MatrixElement } from 'chartjs-chart-matrix';

if (typeof window !== 'undefined' && window.Chart) {
    window.Chart.register(MatrixController, MatrixElement);
}
