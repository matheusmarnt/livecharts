<?php

use Matheusmarnt\LiveCharts\Charts\GenericChart;
use Matheusmarnt\LiveCharts\Charts\Dataset;
use Matheusmarnt\LiveCharts\Engines\ApexChartsAdapter;
use Matheusmarnt\LiveCharts\Engines\ChartJsAdapter;

it('apexcharts adapter includes dataset type for mixed charts', function () {
    $chart = GenericChart::make()
        ->type('bar')
        ->datasets([
            Dataset::make('Bar Series')->data([10, 20]),
            Dataset::make('Line Series')->data([15, 25])->type('line'),
        ]);

    $adapter = new ApexChartsAdapter();
    $options = $adapter->build($chart->toPayloadObject());

    expect($options['series'][0]['type'])->toBeNull();
    expect($options['series'][1]['type'])->toBe('line');
});

it('chartjs adapter includes dataset type for mixed charts', function () {
    $chart = GenericChart::make()
        ->type('bar')
        ->datasets([
            Dataset::make('Bar Series')->data([10, 20]),
            Dataset::make('Line Series')->data([15, 25])->type('line'),
        ]);

    $adapter = new ChartJsAdapter();
    $options = $adapter->build($chart->toPayloadObject());

    expect($options['data']['datasets'][0]['type'])->toBeNull();
    expect($options['data']['datasets'][1]['type'])->toBe('line');
});
