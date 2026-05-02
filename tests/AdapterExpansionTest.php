<?php

use Matheusmarnt\LiveCharts\Engines\ApexChartsAdapter;
use Matheusmarnt\LiveCharts\Engines\ChartJsAdapter;
use Matheusmarnt\LiveCharts\Support\ChartPayload;

it('apexcharts adapter handles single-series charts', function () {
    $payload = new ChartPayload(
        type: 'pie',
        engine: 'apexcharts',
        datasets: [
            ['name' => 'Data', 'data' => [10, 20, 30]],
        ]
    );

    $adapter = new ApexChartsAdapter;
    $options = $adapter->build($payload);

    expect($options['series'])->toBe([10, 20, 30]);
});

it('chartjs adapter handles donut as doughnut', function () {
    $payload = new ChartPayload(
        type: 'donut',
        engine: 'chartjs',
        datasets: [
            ['name' => 'Data', 'data' => [10, 20, 30]],
        ]
    );

    $adapter = new ChartJsAdapter;
    $options = $adapter->build($payload);

    expect($options['type'])->toBe('doughnut');
});

it('chartjs adapter applies colors to datasets', function () {
    $payload = new ChartPayload(
        type: 'bar',
        engine: 'chartjs',
        datasets: [
            ['name' => 'S1', 'data' => [1, 2], 'color' => '#ff0000'],
        ]
    );

    $adapter = new ChartJsAdapter;
    $options = $adapter->build($payload);

    expect($options['data']['datasets'][0]['backgroundColor'])->toBe('#ff0000');
});
