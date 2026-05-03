<?php

use Matheusmarnt\LiveCharts\Charts\Dataset;
use Matheusmarnt\LiveCharts\Charts\GenericChart;

it('dataset can set all properties', function () {
    $dataset = Dataset::make('Series')
        ->data([1, 2, 3])
        ->color('#000')
        ->type('bar')
        ->meta(['foo' => 'bar']);

    expect($dataset->name)->toBe('Series');
    expect($dataset->data)->toBe([1, 2, 3]);
    // color() now sets both background and border as a theme-aware ColorValue
    expect($dataset->background)->not->toBeNull();
    expect($dataset->background->lightHex())->toBe('#000');
    expect($dataset->border)->not->toBeNull();
    expect($dataset->type)->toBe('bar');
    expect($dataset->meta)->toBe(['foo' => 'bar']);
    expect($dataset->toArray())->toBeArray();
});

it('chart can set all common properties', function () {
    $chart = GenericChart::make()
        ->dataset('Series', [1])
        ->title('Title')
        ->subtitle('Subtitle')
        ->height(400)
        ->width('50%')
        ->colors(['#111'])
        ->theme('dark')
        ->stacked()
        ->sparkline()
        ->zoom()
        ->toolbar()
        ->legend(false)
        ->tooltip(false)
        ->options(['custom' => 'opt']);

    $payload = $chart->toPayload();

    expect($payload['title'])->toBe('Title');
    expect($payload['subtitle'])->toBe('Subtitle');
    expect($payload['height'])->toBe(400);
    expect($payload['width'])->toBe('50%');
    // colors() now resolves to theme-aware ColorValue pairs; hex strings use same hex for dark+light
    expect($payload['colors'])->toBe([['dark' => '#111', 'light' => '#111']]);
    expect($payload['theme'])->toBe('dark');
    expect($payload['stacked'])->toBeTrue();
    expect($payload['sparkline'])->toBeTrue();
    expect($payload['zoom'])->toBeTrue();
    expect($payload['toolbar'])->toBeTrue();
    expect($payload['legend'])->toBeFalse();
    expect($payload['tooltip'])->toBeFalse();
    expect($payload['options'])->toBe(['custom' => 'opt']);
});

it('chart handles raw array datasets', function () {
    $chart = GenericChart::make()
        ->datasets([
            ['name' => 'S1', 'data' => [1, 2]],
        ]);

    expect($chart->toPayload()['datasets'][0]['name'])->toBe('S1');
});
