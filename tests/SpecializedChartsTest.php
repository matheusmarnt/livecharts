<?php

declare(strict_types=1);

use Matheusmarnt\LiveCharts\Charts\AreaChart;
use Matheusmarnt\LiveCharts\Charts\BarChart;
use Matheusmarnt\LiveCharts\Charts\BoxPlotChart;
use Matheusmarnt\LiveCharts\Charts\BubbleChart;
use Matheusmarnt\LiveCharts\Charts\CandlestickChart;
use Matheusmarnt\LiveCharts\Charts\Chart;
use Matheusmarnt\LiveCharts\Charts\DonutChart;
use Matheusmarnt\LiveCharts\Charts\HeatmapChart;
use Matheusmarnt\LiveCharts\Charts\LineChart;
use Matheusmarnt\LiveCharts\Charts\MatrixChart;
use Matheusmarnt\LiveCharts\Charts\PieChart;
use Matheusmarnt\LiveCharts\Charts\PolarAreaChart;
use Matheusmarnt\LiveCharts\Charts\RadarChart;
use Matheusmarnt\LiveCharts\Charts\RadialBarChart;
use Matheusmarnt\LiveCharts\Charts\RangeBarChart;
use Matheusmarnt\LiveCharts\Charts\SankeyChart;
use Matheusmarnt\LiveCharts\Charts\ScatterChart;
use Matheusmarnt\LiveCharts\Charts\TreemapChart;
use Matheusmarnt\LiveCharts\Engines\ApexChartsAdapter;
use Matheusmarnt\LiveCharts\Engines\ChartJsAdapter;
use Matheusmarnt\LiveCharts\Exceptions\InvalidChartTypeException;
use Matheusmarnt\LiveCharts\Facades\LiveCharts;

dataset('specialized_factories', [
    ['line', LineChart::class, 'line'],
    ['bar', BarChart::class, 'bar'],
    ['area', AreaChart::class, 'area'],
    ['pie', PieChart::class, 'pie'],
    ['donut', DonutChart::class, 'donut'],
    ['radar', RadarChart::class, 'radar'],
    ['scatter', ScatterChart::class, 'scatter'],
    ['bubble', BubbleChart::class, 'bubble'],
    ['heatmap', HeatmapChart::class, 'heatmap'],
    ['rangeBar', RangeBarChart::class, 'rangeBar'],
    ['radialBar', RadialBarChart::class, 'radialBar'],
    ['polarArea', PolarAreaChart::class, 'polarArea'],
    ['boxPlot', BoxPlotChart::class, 'boxPlot'],
    ['treemap', TreemapChart::class, 'treemap'],
    ['candlestick', CandlestickChart::class, 'candlestick'],
    ['matrix', MatrixChart::class, 'matrix'],
    ['sankey', SankeyChart::class, 'sankey'],
]);

it('every specialized factory returns the matching class and chart type', function (string $factory, string $class, string $type) {
    $chart = LiveCharts::{$factory}();

    expect($chart)->toBeInstanceOf($class);
    expect($chart)->toBeInstanceOf(Chart::class);

    $payload = $chart->labels(['A'])->dataset('S', [1])->toPayload();

    expect($payload['type'])->toBe($type);
})->with('specialized_factories');

it('Chart::TYPES list mirrors every specialized chart type', function () {
    $factoryTypes = collect([
        LineChart::class, BarChart::class, AreaChart::class, PieChart::class,
        DonutChart::class, RadarChart::class, ScatterChart::class, BubbleChart::class,
        HeatmapChart::class, RangeBarChart::class, RadialBarChart::class, PolarAreaChart::class,
        BoxPlotChart::class, TreemapChart::class, CandlestickChart::class, MatrixChart::class,
        SankeyChart::class,
    ])
        ->map(fn ($class) => (new $class)->labels(['A'])->dataset('S', [1])->toPayload()['type'])
        ->sort()
        ->values()
        ->all();

    $declared = collect(Chart::TYPES)->sort()->values()->all();

    expect($factoryTypes)->toBe($declared);
});

dataset('apex_supported_types', [
    ['line'], ['bar'], ['area'], ['pie'], ['donut'], ['radialBar'], ['polarArea'],
    ['scatter'], ['heatmap'], ['radar'], ['candlestick'], ['boxPlot'], ['rangeBar'],
    ['treemap'], ['bubble'],
]);

it('ApexChartsAdapter routes specialized payloads to the matching chart.type', function (string $type) {
    $payload = (new \Matheusmarnt\LiveCharts\Charts\GenericChart)
        ->type($type)
        ->engine('apexcharts')
        ->labels(['A'])
        ->dataset('S', [1])
        ->toPayloadObject();

    $built = (new ApexChartsAdapter)->build($payload);

    expect($built)
        ->toHaveKey('chart')
        ->toHaveKey('series')
        ->toHaveKey('labels')
        ->toHaveKey('legend')
        ->toHaveKey('tooltip');

    expect($built['chart'])->toHaveKey('type');
    expect($built['chart']['type'])->toBe($type);
})->with('apex_supported_types');

it('ApexChartsAdapter collapses single-series chart types into a flat series array', function (string $type) {
    $payload = (new \Matheusmarnt\LiveCharts\Charts\GenericChart)
        ->type($type)
        ->engine('apexcharts')
        ->labels(['A', 'B', 'C'])
        ->dataset('S', [1, 2, 3])
        ->toPayloadObject();

    $built = (new ApexChartsAdapter)->build($payload);

    expect($built['series'])->toBe([1, 2, 3]);
})->with([['pie'], ['donut'], ['radialBar'], ['polarArea']]);

it('ApexChartsAdapter wraps multi-series payloads as name+data+type triples', function () {
    $payload = (new \Matheusmarnt\LiveCharts\Charts\GenericChart)
        ->type('line')
        ->engine('apexcharts')
        ->labels(['A'])
        ->dataset('Series A', [10])
        ->dataset('Series B', [20])
        ->toPayloadObject();

    $built = (new ApexChartsAdapter)->build($payload);

    expect($built['series'])->toHaveCount(2);
    expect($built['series'][0])->toMatchArray([
        'name' => 'Series A',
        'data' => [10],
    ]);
    expect($built['series'][1])->toMatchArray([
        'name' => 'Series B',
        'data' => [20],
    ]);
});

it('ApexChartsAdapter rejects chart-js-only types via InvalidChartTypeException', function (string $type) {
    $payload = (new \Matheusmarnt\LiveCharts\Charts\GenericChart)
        ->type($type)
        ->engine('apexcharts')
        ->dataset('S', [1])
        ->toPayloadObject();

    (new ApexChartsAdapter)->build($payload);
})
    ->with([['matrix'], ['sankey']])
    ->throws(InvalidChartTypeException::class);

dataset('chartjs_supported_types', [
    ['line'], ['bar'], ['area'], ['pie'], ['donut'], ['polarArea'], ['scatter'],
    ['radar'], ['bubble'], ['matrix'], ['sankey'], ['treemap'],
]);

it('ChartJsAdapter routes specialized payloads to the matching top-level type', function (string $type) {
    $payload = (new \Matheusmarnt\LiveCharts\Charts\GenericChart)
        ->type($type)
        ->engine('chartjs')
        ->labels(['A'])
        ->dataset('S', [1])
        ->toPayloadObject();

    $built = (new ChartJsAdapter)->build($payload);

    expect($built)
        ->toHaveKey('type')
        ->toHaveKey('data')
        ->toHaveKey('options');

    expect($built['data'])->toHaveKeys(['labels', 'datasets']);
    expect($built['options'])->toHaveKey('plugins');
})->with('chartjs_supported_types');

it('ChartJsAdapter aliases donut to doughnut at the top-level type', function () {
    $payload = (new \Matheusmarnt\LiveCharts\Charts\GenericChart)
        ->type('donut')
        ->engine('chartjs')
        ->labels(['A'])
        ->dataset('S', [1])
        ->toPayloadObject();

    $built = (new ChartJsAdapter)->build($payload);

    expect($built['type'])->toBe('doughnut');
});

it('ChartJsAdapter rejects apex-only types via InvalidChartTypeException', function (string $type) {
    $payload = (new \Matheusmarnt\LiveCharts\Charts\GenericChart)
        ->type($type)
        ->engine('chartjs')
        ->dataset('S', [1])
        ->toPayloadObject();

    (new ChartJsAdapter)->build($payload);
})
    ->with([['heatmap'], ['boxPlot'], ['radialBar'], ['rangeBar']])
    ->throws(InvalidChartTypeException::class);
