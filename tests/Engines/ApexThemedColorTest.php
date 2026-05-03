<?php

use Matheusmarnt\LiveCharts\Charts\Dataset;
use Matheusmarnt\LiveCharts\Engines\ApexChartsAdapter;
use Matheusmarnt\LiveCharts\Enums\TwColor;
use Matheusmarnt\LiveCharts\Support\ChartPayload;
use Matheusmarnt\LiveCharts\Support\ColorResolver;

it('titleColor emits initial light hex in title.style.color', function () {
    $payload = new ChartPayload(
        type: 'line',
        engine: 'apexcharts',
        title: 'Revenue',
        datasets: [Dataset::make('S')->data([1])],
        titleColor: ColorResolver::resolvePair(TwColor::Amber300, TwColor::Amber600),
    );

    $options = (new ApexChartsAdapter)->build($payload);

    expect($options['title']['style']['color'])->toBe(TwColor::Amber600->value);
});

it('titleColor sidecar lives at title.style level with key color (Bug 6)', function () {
    $payload = new ChartPayload(
        type: 'line',
        engine: 'apexcharts',
        title: 'Revenue',
        datasets: [Dataset::make('S')->data([1])],
        titleColor: ColorResolver::resolvePair(TwColor::Amber300, TwColor::Amber600),
    );

    $options = (new ApexChartsAdapter)->build($payload);

    expect($options['title']['style'])->toHaveKey('__lc_themed');
    expect($options['title']['style']['__lc_themed']['color']['dark'])->toBe(TwColor::Amber300->value);
    expect($options['title']['style']['__lc_themed']['color']['light'])->toBe(TwColor::Amber600->value);
    expect(isset($options['title']['__lc_themed']))->toBeFalse();
});

it('subtitleColor emits sidecar', function () {
    $payload = new ChartPayload(
        type: 'line',
        engine: 'apexcharts',
        datasets: [Dataset::make('S')->data([1])],
        subtitleColor: ColorResolver::resolvePair(TwColor::Slate300, TwColor::Slate700),
    );

    $options = (new ApexChartsAdapter)->build($payload);

    expect($options['subtitle']['style'])->toHaveKey('__lc_themed');
});

it('legendColor emits sidecar in legend', function () {
    $payload = new ChartPayload(
        type: 'line',
        engine: 'apexcharts',
        datasets: [Dataset::make('S')->data([1])],
        legendColor: ColorResolver::resolvePair(TwColor::Slate200, TwColor::Slate700),
    );

    $options = (new ApexChartsAdapter)->build($payload);

    expect($options['legend']['labels'])->toHaveKey('__lc_themed');
    expect($options['legend']['labels']['colors'])->toBe(TwColor::Slate700->value);
});

it('gridColor emits sidecar and light hex in grid.borderColor', function () {
    $payload = new ChartPayload(
        type: 'line',
        engine: 'apexcharts',
        datasets: [Dataset::make('S')->data([1])],
        gridColor: ColorResolver::resolvePair(TwColor::Slate800, TwColor::Slate200),
    );

    $options = (new ApexChartsAdapter)->build($payload);

    expect($options['grid']['borderColor'])->toBe(TwColor::Slate200->value);
    expect($options['grid'])->toHaveKey('__lc_themed');
});

it('null color slots produce no __lc_themed keys in options', function () {
    $payload = new ChartPayload(
        type: 'line',
        engine: 'apexcharts',
        title: 'T',
        datasets: [Dataset::make('S')->data([1])],
    );

    $options = (new ApexChartsAdapter)->build($payload);

    expect(isset($options['title']['__lc_themed']))->toBeFalse();
    expect(isset($options['legend']['__lc_themed']))->toBeFalse();
});

it('titleFont maps to apex title.style fields', function () {
    $payload = new ChartPayload(
        type: 'line',
        engine: 'apexcharts',
        title: 'T',
        datasets: [Dataset::make('S')->data([1])],
        titleFont: ['size' => 18, 'weight' => 'bold', 'family' => 'Inter'],
    );

    $options = (new ApexChartsAdapter)->build($payload);

    expect($options['title']['style']['fontSize'])->toBe('18px');
    expect($options['title']['style']['fontWeight'])->toBe('bold');
    expect($options['title']['style']['fontFamily'])->toBe('Inter');
});

it('backgroundColor sets chart.background with light hex', function () {
    $payload = new ChartPayload(
        type: 'line',
        engine: 'apexcharts',
        datasets: [Dataset::make('S')->data([1])],
        backgroundColor: ColorResolver::resolvePair(TwColor::Slate900, TwColor::White),
    );

    $options = (new ApexChartsAdapter)->build($payload);

    expect($options['chart']['background'])->toBe(TwColor::White->value);
    expect($options['chart'])->toHaveKey('__lc_themed');
});

it('labelsColor sidecar at xaxis.labels.style and yaxis.labels.style with key colors (Bug 6)', function () {
    $payload = new ChartPayload(
        type: 'line',
        engine: 'apexcharts',
        datasets: [Dataset::make('S')->data([1])],
        labelsColor: ColorResolver::resolvePair(TwColor::Slate300, TwColor::Slate700),
    );

    $options = (new ApexChartsAdapter)->build($payload);

    expect($options['xaxis']['labels']['style'])->toHaveKey('__lc_themed');
    expect($options['xaxis']['labels']['style']['__lc_themed']['colors']['dark'])->toBe(TwColor::Slate300->value);
    expect($options['xaxis']['labels']['style']['__lc_themed']['colors']['light'])->toBe(TwColor::Slate700->value);
    expect($options['yaxis']['labels']['style'])->toHaveKey('__lc_themed');
});

it('axisColor sidecar at xaxis.axisBorder, xaxis.axisTicks, yaxis.axisBorder with key color (Bug 6)', function () {
    $payload = new ChartPayload(
        type: 'line',
        engine: 'apexcharts',
        datasets: [Dataset::make('S')->data([1])],
        axisColor: ColorResolver::resolvePair(TwColor::Slate400, TwColor::Slate600),
    );

    $options = (new ApexChartsAdapter)->build($payload);

    expect($options['xaxis']['axisBorder'])->toHaveKey('__lc_themed');
    expect($options['xaxis']['axisBorder']['__lc_themed']['color']['dark'])->toBe(TwColor::Slate400->value);
    expect($options['xaxis']['axisTicks'])->toHaveKey('__lc_themed');
    expect($options['yaxis']['axisBorder'])->toHaveKey('__lc_themed');
});

it('dataLabelsColor sidecar at dataLabels.style with key colors (Bug 6)', function () {
    $payload = new ChartPayload(
        type: 'line',
        engine: 'apexcharts',
        datasets: [Dataset::make('S')->data([1])],
        dataLabelsColor: ColorResolver::resolvePair(TwColor::White, TwColor::Slate900),
    );

    $options = (new ApexChartsAdapter)->build($payload);

    expect($options['dataLabels']['style'])->toHaveKey('__lc_themed');
    expect($options['dataLabels']['style']['__lc_themed']['colors']['dark'])->toBe(TwColor::White->value);
    expect($options['dataLabels']['style']['__lc_themed']['colors']['light'])->toBe(TwColor::Slate900->value);
});

it('tooltipColor emits theme sidecar and style.color sidecar for CSS injection (Bug 7)', function () {
    $payload = new ChartPayload(
        type: 'line',
        engine: 'apexcharts',
        datasets: [Dataset::make('S')->data([1])],
        tooltipColor: ColorResolver::resolvePair(TwColor::Slate200, TwColor::Slate800),
    );

    $options = (new ApexChartsAdapter)->build($payload);

    expect($options['tooltip']['theme'])->toBe('light');
    expect($options['tooltip']['__lc_themed']['theme'])->toBe(['dark' => 'dark', 'light' => 'light']);
    expect($options['tooltip']['style'])->toHaveKey('__lc_themed');
    expect($options['tooltip']['style']['__lc_themed']['color']['dark'])->toBe(TwColor::Slate200->value);
    expect($options['tooltip']['style']['__lc_themed']['color']['light'])->toBe(TwColor::Slate800->value);
});
