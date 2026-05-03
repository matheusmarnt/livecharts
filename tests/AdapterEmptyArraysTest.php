<?php

declare(strict_types=1);

use Matheusmarnt\LiveCharts\Charts\GenericChart;
use Matheusmarnt\LiveCharts\Engines\ApexChartsAdapter;
use Matheusmarnt\LiveCharts\Engines\ChartJsAdapter;

// ─── ApexChartsAdapter ───────────────────────────────────────────────────────

it('ApexChartsAdapter omits optional keys when payload fields are empty', function () {
    $payload = (new GenericChart)
        ->type('line')
        ->engine('apexcharts')
        ->labels(['A'])
        ->dataset('S', [1])
        ->toPayloadObject();

    $built = (new ApexChartsAdapter)->build($payload);

    expect($built)->not->toHaveKey('xaxis');
    expect($built)->not->toHaveKey('yaxis');
    expect($built)->not->toHaveKey('grid');
    expect($built)->not->toHaveKey('stroke');
    expect($built)->not->toHaveKey('markers');
    expect($built)->not->toHaveKey('dataLabels');
});

it('ApexChartsAdapter includes xaxis when explicitly configured', function () {
    $payload = (new GenericChart)
        ->type('line')
        ->engine('apexcharts')
        ->labels(['Jan', 'Feb', 'Mar'])
        ->dataset('S', [1, 2, 3])
        ->xaxis(['categories' => ['Jan', 'Feb', 'Mar']])
        ->toPayloadObject();

    $built = (new ApexChartsAdapter)->build($payload);

    expect($built)->toHaveKey('xaxis');
    expect($built['xaxis'])->toBe(['categories' => ['Jan', 'Feb', 'Mar']]);
});

it('ApexChartsAdapter includes all configured optional fields', function () {
    $payload = (new GenericChart)
        ->type('line')
        ->engine('apexcharts')
        ->labels(['A'])
        ->dataset('S', [1])
        ->xaxis(['categories' => ['A']])
        ->yaxis(['min' => 0])
        ->grid(['show' => true])
        ->stroke(['curve' => 'smooth'])
        ->markers(['size' => 4])
        ->dataLabels(['enabled' => true])
        ->toPayloadObject();

    $built = (new ApexChartsAdapter)->build($payload);

    expect($built)->toHaveKey('xaxis');
    expect($built)->toHaveKey('yaxis');
    expect($built)->toHaveKey('grid');
    expect($built)->toHaveKey('stroke');
    expect($built)->toHaveKey('markers');
    expect($built)->toHaveKey('dataLabels');
});

it('ApexChartsAdapter payload JSON does not contain empty array fields', function () {
    $payload = (new GenericChart)
        ->type('line')
        ->engine('apexcharts')
        ->labels(['A'])
        ->dataset('S', [1])
        ->toPayloadObject();

    $built = (new ApexChartsAdapter)->build($payload);
    $json = json_encode($built);

    expect($json)->not->toContain('"dataLabels":[]');
    expect($json)->not->toContain('"xaxis":[]');
    expect($json)->not->toContain('"yaxis":[]');
    expect($json)->not->toContain('"grid":[]');
    expect($json)->not->toContain('"stroke":[]');
    expect($json)->not->toContain('"markers":[]');
});

// ─── ChartJsAdapter ──────────────────────────────────────────────────────────

it('ChartJsAdapter omits datalabels plugin key when dataLabels is empty', function () {
    $payload = (new GenericChart)
        ->type('line')
        ->engine('chartjs')
        ->labels(['A'])
        ->dataset('S', [1])
        ->toPayloadObject();

    $built = (new ChartJsAdapter)->build($payload);

    expect($built['options']['plugins'])->not->toHaveKey('datalabels');
});

it('ChartJsAdapter includes datalabels plugin when explicitly configured', function () {
    $payload = (new GenericChart)
        ->type('line')
        ->engine('chartjs')
        ->labels(['A'])
        ->dataset('S', [1])
        ->dataLabels(['enabled' => true])
        ->toPayloadObject();

    $built = (new ChartJsAdapter)->build($payload);

    expect($built['options']['plugins'])->toHaveKey('datalabels');
    expect($built['options']['plugins']['datalabels'])->toBe(['enabled' => true]);
});

it('ChartJsAdapter omits scale overrides when xaxis and yaxis are empty', function () {
    $payload = (new GenericChart)
        ->type('line')
        ->engine('chartjs')
        ->labels(['A'])
        ->dataset('S', [1])
        ->toPayloadObject();

    $built = (new ChartJsAdapter)->build($payload);

    // Scales from buildScales() should still exist; no empty-array corruption
    expect($built['options']['scales'])->toHaveKey('y');
    expect($built['options']['scales']['y'])->toHaveKey('beginAtZero');
});
