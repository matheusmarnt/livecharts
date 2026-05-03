<?php

use Matheusmarnt\LiveCharts\Charts\Dataset;
use Matheusmarnt\LiveCharts\Engines\ChartJsAdapter;
use Matheusmarnt\LiveCharts\Enums\TwColor;
use Matheusmarnt\LiveCharts\Support\ChartPayload;
use Matheusmarnt\LiveCharts\Support\ColorResolver;

it('titleColor emits light hex in plugins.title.color', function () {
    $payload = new ChartPayload(
        type: 'bar',
        engine: 'chartjs',
        title: 'Revenue',
        datasets: [Dataset::make('S')->data([1])],
        titleColor: ColorResolver::resolvePair(TwColor::Amber300, TwColor::Amber600),
    );

    $options = (new ChartJsAdapter)->build($payload);

    expect($options['options']['plugins']['title']['color'])->toBe(TwColor::Amber600->value);
    expect($options['options']['plugins']['title'])->toHaveKey('__lc_themed');
});

it('legendColor emits light hex in plugins.legend.labels.color', function () {
    $payload = new ChartPayload(
        type: 'bar',
        engine: 'chartjs',
        datasets: [Dataset::make('S')->data([1])],
        legendColor: ColorResolver::resolvePair(TwColor::Slate300, TwColor::Slate700),
    );

    $options = (new ChartJsAdapter)->build($payload);

    expect($options['options']['plugins']['legend']['labels']['color'])->toBe(TwColor::Slate700->value);
    expect($options['options']['plugins']['legend']['labels'])->toHaveKey('__lc_themed');
});

it('tooltipColor emits titleColor + bodyColor in plugins.tooltip', function () {
    $payload = new ChartPayload(
        type: 'bar',
        engine: 'chartjs',
        datasets: [Dataset::make('S')->data([1])],
        tooltipColor: ColorResolver::resolvePair(TwColor::White, TwColor::Slate900),
    );

    $options = (new ChartJsAdapter)->build($payload);

    expect($options['options']['plugins']['tooltip']['titleColor'])->toBe(TwColor::Slate900->value);
    expect($options['options']['plugins']['tooltip']['bodyColor'])->toBe(TwColor::Slate900->value);
});

it('labelsColor emits ticks.color on x and y scales', function () {
    $payload = new ChartPayload(
        type: 'bar',
        engine: 'chartjs',
        datasets: [Dataset::make('S')->data([1])],
        labelsColor: ColorResolver::resolvePair(TwColor::Slate400, TwColor::Slate600),
    );

    $options = (new ChartJsAdapter)->build($payload);

    expect($options['options']['scales']['x']['ticks']['color'])->toBe(TwColor::Slate600->value);
    expect($options['options']['scales']['y']['ticks']['color'])->toBe(TwColor::Slate600->value);
    expect($options['options']['scales']['x']['ticks'])->toHaveKey('__lc_themed');
});

it('gridColor emits grid.color on x and y scales', function () {
    $payload = new ChartPayload(
        type: 'bar',
        engine: 'chartjs',
        datasets: [Dataset::make('S')->data([1])],
        gridColor: ColorResolver::resolvePair(TwColor::Slate800, TwColor::Slate200),
    );

    $options = (new ChartJsAdapter)->build($payload);

    expect($options['options']['scales']['x']['grid']['color'])->toBe(TwColor::Slate200->value);
});

it('dataset backgroundColor split: background lightHex used', function () {
    $dataset = Dataset::make('S1')
        ->data([1, 2])
        ->backgroundColor(dark: TwColor::Emerald400, light: TwColor::Emerald600);

    $payload = new ChartPayload(
        type: 'bar',
        engine: 'chartjs',
        datasets: [$dataset],
    );

    $options = (new ChartJsAdapter)->build($payload);

    expect($options['data']['datasets'][0]['backgroundColor'])->toBe(TwColor::Emerald600->value);
});

it('dataset borderColor split: border lightHex used', function () {
    $dataset = Dataset::make('S1')
        ->data([1, 2])
        ->backgroundColor(dark: TwColor::Sky400, light: TwColor::Sky600)
        ->borderColor(dark: TwColor::Sky300, light: TwColor::Sky700);

    $payload = new ChartPayload(
        type: 'bar',
        engine: 'chartjs',
        datasets: [$dataset],
    );

    $options = (new ChartJsAdapter)->build($payload);

    expect($options['data']['datasets'][0]['borderColor'])->toBe(TwColor::Sky700->value);
});

it('null color slots produce no __lc_themed in chartjs options', function () {
    $payload = new ChartPayload(
        type: 'bar',
        engine: 'chartjs',
        title: 'T',
        datasets: [Dataset::make('S')->data([1])],
    );

    $options = (new ChartJsAdapter)->build($payload);

    expect(isset($options['options']['plugins']['title']['__lc_themed']))->toBeFalse();
    expect(isset($options['options']['plugins']['legend']['labels']['__lc_themed']))->toBeFalse();
});

it('hex string dataset color still works via color() sugar', function () {
    $dataset = Dataset::make('S1')->data([1, 2])->color('#ff0000');

    $payload = new ChartPayload(
        type: 'bar',
        engine: 'chartjs',
        datasets: [$dataset],
    );

    $options = (new ChartJsAdapter)->build($payload);

    expect($options['data']['datasets'][0]['backgroundColor'])->toBe('#ff0000');
});
