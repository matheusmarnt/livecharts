<?php

declare(strict_types=1);

use Matheusmarnt\LiveCharts\Charts\HeatmapChart;
use Matheusmarnt\LiveCharts\Charts\LineChart;
use Matheusmarnt\LiveCharts\Charts\MatrixChart;
use Matheusmarnt\LiveCharts\Engines\EngineFactory;
use Matheusmarnt\LiveCharts\Exceptions\InvalidChartTypeException;

it('routes heatmap (apex-only) to apexcharts automatically', function () {
    $chart = HeatmapChart::make()
        ->dataset('Series', [1, 2, 3]);

    $payload = $chart->toPayload();

    expect($payload['engine'])->toBe('apexcharts');
});

it('routes matrix (chartjs-only) to chartjs automatically', function () {
    $chart = MatrixChart::make()
        ->dataset('Series', [1, 2, 3]);

    $payload = $chart->toPayload();

    expect($payload['engine'])->toBe('chartjs');
});

it('routes line (both engines) to apexcharts by preference', function () {
    $chart = LineChart::make()
        ->labels(['A', 'B', 'C'])
        ->dataset('Series', [1, 2, 3]);

    $payload = $chart->toPayload();

    expect($payload['engine'])->toBe('apexcharts');
});

it('respects explicit engine override even when auto-routing would pick differently', function () {
    $chart = LineChart::make()
        ->engine('chartjs')
        ->labels(['A', 'B', 'C'])
        ->dataset('Series', [1, 2, 3]);

    $payload = $chart->toPayload();

    expect($payload['engine'])->toBe('chartjs');
});

it('throws InvalidChartTypeException for type unsupported by any engine', function () {
    $factory = app(EngineFactory::class);

    expect(fn () => $factory->engineForType('unknown-type'))
        ->toThrow(InvalidChartTypeException::class);
});

it('engineForType returns apexcharts for shared types', function () {
    $factory = app(EngineFactory::class);

    foreach (['line', 'bar', 'area', 'scatter', 'bubble', 'treemap'] as $type) {
        expect($factory->engineForType($type))->toBe('apexcharts', "type '$type' should prefer apexcharts");
    }
});

it('engineForType returns chartjs for chartjs-exclusive types', function () {
    $factory = app(EngineFactory::class);

    foreach (['matrix', 'sankey'] as $type) {
        expect($factory->engineForType($type))->toBe('chartjs', "type '$type' should route to chartjs");
    }
});

it('engineForType returns apexcharts for apex-exclusive types', function () {
    $factory = app(EngineFactory::class);

    foreach (['heatmap', 'radialBar', 'rangeBar', 'boxPlot'] as $type) {
        expect($factory->engineForType($type))->toBe('apexcharts', "type '$type' should route to apexcharts");
    }
});
