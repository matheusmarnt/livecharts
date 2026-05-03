import {
    CandlestickController,
    CandlestickElement,
    OhlcController,
    OhlcElement,
} from 'chartjs-chart-financial';

if (typeof window !== 'undefined' && window.Chart) {
    window.Chart.register(
        CandlestickController,
        CandlestickElement,
        OhlcController,
        OhlcElement,
    );
}
