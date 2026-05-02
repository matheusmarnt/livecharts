<?php

declare(strict_types=1);

use Matheusmarnt\LiveCharts\Charts\Dataset;
use Matheusmarnt\LiveCharts\Charts\GenericChart;
use Matheusmarnt\LiveCharts\Charts\LineChart;
use Matheusmarnt\LiveCharts\Engines\ApexChartsAdapter;
use Matheusmarnt\LiveCharts\Engines\ChartJsAdapter;
use Matheusmarnt\LiveCharts\Exceptions\DataShapeMismatchException;
use Matheusmarnt\LiveCharts\Exceptions\EmptyDatasetException;
use Matheusmarnt\LiveCharts\Exceptions\InvalidChartTypeException;
use Matheusmarnt\LiveCharts\Support\ChartPayload;

it('throws InvalidChartTypeException for unsupported ApexCharts type', function () {
    $payload = new ChartPayload(type: 'bogus', engine: 'apexcharts', datasets: [new Dataset('s', [1])]);

    (new ApexChartsAdapter)->build($payload);
})->throws(InvalidChartTypeException::class, 'apexcharts');

it('throws InvalidChartTypeException for unsupported Chart.js type', function () {
    $payload = new ChartPayload(type: 'boxPlot', engine: 'chartjs', datasets: [new Dataset('s', [1])]);

    (new ChartJsAdapter)->build($payload);
})->throws(InvalidChartTypeException::class, 'chartjs');

it('throws EmptyDatasetException when chart has no datasets', function () {
    LineChart::make()->labels(['Jan'])->toPayload();
})->throws(EmptyDatasetException::class, 'no datasets');

it('throws EmptyDatasetException when a dataset has empty data', function () {
    LineChart::make()
        ->labels(['Jan', 'Feb'])
        ->dataset('Sales', [])
        ->toPayload();
})->throws(EmptyDatasetException::class, 'no data points');

it('throws DataShapeMismatchException when label count differs from data count', function () {
    LineChart::make()
        ->labels(['Jan', 'Feb', 'Mar'])
        ->dataset('Sales', [10, 20])
        ->toPayload();
})->throws(DataShapeMismatchException::class, 'mismatch');

it('skips shape check for chart types with structural data', function () {
    $payload = GenericChart::make()
        ->type('heatmap')
        ->labels(['A', 'B'])
        ->dataset('Heat', [['x' => 1, 'y' => 2, 'value' => 3]])
        ->toPayload();

    expect($payload['type'])->toBe('heatmap');
});

it('accepts matching label and data shape', function () {
    $payload = LineChart::make()
        ->labels(['Jan', 'Feb', 'Mar'])
        ->dataset('Sales', [10, 20, 30])
        ->toPayload();

    expect($payload['datasets'][0]['data'])->toHaveCount(3);
});
