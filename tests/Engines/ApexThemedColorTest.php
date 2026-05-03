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

it('titleColor emits __lc_themed sidecar with dark and light hex', function () {
    $payload = new ChartPayload(
        type: 'line',
        engine: 'apexcharts',
        title: 'Revenue',
        datasets: [Dataset::make('S')->data([1])],
        titleColor: ColorResolver::resolvePair(TwColor::Amber300, TwColor::Amber600),
    );

    $options = (new ApexChartsAdapter)->build($payload);

    expect($options['title'])->toHaveKey('__lc_themed');
    expect($options['title']['__lc_themed']['titleColor']['dark'])->toBe(TwColor::Amber300->value);
    expect($options['title']['__lc_themed']['titleColor']['light'])->toBe(TwColor::Amber600->value);
});

it('subtitleColor emits sidecar', function () {
    $payload = new ChartPayload(
        type: 'line',
        engine: 'apexcharts',
        datasets: [Dataset::make('S')->data([1])],
        subtitleColor: ColorResolver::resolvePair(TwColor::Slate300, TwColor::Slate700),
    );

    $options = (new ApexChartsAdapter)->build($payload);

    expect($options['subtitle'])->toHaveKey('__lc_themed');
});

it('legendColor emits sidecar in legend', function () {
    $payload = new ChartPayload(
        type: 'line',
        engine: 'apexcharts',
        datasets: [Dataset::make('S')->data([1])],
        legendColor: ColorResolver::resolvePair(TwColor::Slate200, TwColor::Slate700),
    );

    $options = (new ApexChartsAdapter)->build($payload);

    expect($options['legend'])->toHaveKey('__lc_themed');
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
