<?php

use Matheusmarnt\LiveCharts\Charts\GenericChart;
use Matheusmarnt\LiveCharts\Enums\ThemeMode;
use Matheusmarnt\LiveCharts\Enums\TwColor;
use Matheusmarnt\LiveCharts\Enums\TwPalette;

it('titleColor named-arg pair flows to payload', function () {
    $payload = GenericChart::make()
        ->dataset('S', [1])
        ->titleColor(dark: TwColor::Amber300, light: TwColor::Amber600)
        ->toPayload();

    expect($payload['titleColor'])->toBe([
        'dark' => TwColor::Amber300->value,
        'light' => TwColor::Amber600->value,
    ]);
});

it('titleColor single-value sets both themes to same hex', function () {
    $payload = GenericChart::make()
        ->dataset('S', [1])
        ->titleColor(TwColor::Slate500)
        ->toPayload();

    expect($payload['titleColor']['dark'])->toBe(TwColor::Slate500->value);
    expect($payload['titleColor']['light'])->toBe(TwColor::Slate500->value);
});

it('subtitleColor flows to payload', function () {
    $payload = GenericChart::make()
        ->dataset('S', [1])
        ->subtitleColor(dark: TwColor::Gray300, light: TwColor::Gray700)
        ->toPayload();

    expect($payload['subtitleColor']['dark'])->toBe(TwColor::Gray300->value);
    expect($payload['subtitleColor']['light'])->toBe(TwColor::Gray700->value);
});

it('legendColor flows to payload', function () {
    $payload = GenericChart::make()
        ->dataset('S', [1])
        ->legendColor(dark: TwColor::Slate200, light: TwColor::Slate700)
        ->toPayload();

    expect($payload['legendColor'])->not->toBeNull();
});

it('gridColor flows to payload', function () {
    $payload = GenericChart::make()
        ->dataset('S', [1])
        ->gridColor(dark: TwColor::Slate800, light: TwColor::Slate200)
        ->toPayload();

    expect($payload['gridColor'])->not->toBeNull();
});

it('axisColor flows to payload', function () {
    $payload = GenericChart::make()
        ->dataset('S', [1])
        ->axisColor(TwColor::Slate500)
        ->toPayload();

    expect($payload['axisColor']['dark'])->toBe(TwColor::Slate500->value);
});

it('tooltipColor flows to payload', function () {
    $payload = GenericChart::make()
        ->dataset('S', [1])
        ->tooltipColor(dark: TwColor::White, light: TwColor::Slate900)
        ->toPayload();

    expect($payload['tooltipColor']['dark'])->toBe(TwColor::White->value);
    expect($payload['tooltipColor']['light'])->toBe(TwColor::Slate900->value);
});

it('backgroundColor flows to payload', function () {
    $payload = GenericChart::make()
        ->dataset('S', [1])
        ->backgroundColor(dark: TwColor::Slate900, light: TwColor::White)
        ->toPayload();

    expect($payload['backgroundColor'])->not->toBeNull();
});

it('dataLabelsColor flows to payload', function () {
    $payload = GenericChart::make()
        ->dataset('S', [1])
        ->dataLabelsColor(TwColor::Slate100)
        ->toPayload();

    expect($payload['dataLabelsColor'])->not->toBeNull();
});

it('unset color slots are null in payload', function () {
    $payload = GenericChart::make()
        ->dataset('S', [1])
        ->toPayload();

    expect($payload['titleColor'])->toBeNull();
    expect($payload['gridColor'])->toBeNull();
    expect($payload['legendColor'])->toBeNull();
});

it('theme() accepts ThemeMode enum', function () {
    $payload = GenericChart::make()
        ->dataset('S', [1])
        ->theme(ThemeMode::Dark)
        ->toPayload();

    expect($payload['theme'])->toBe('dark');
});

it('theme() accepts string for backward compatibility', function () {
    $payload = GenericChart::make()
        ->dataset('S', [1])
        ->theme('light')
        ->toPayload();

    expect($payload['theme'])->toBe('light');
});

it('palette() fills colors with theme-aware pairs', function () {
    $payload = GenericChart::make()
        ->dataset('S', [1])
        ->palette(TwPalette::Vibrant)
        ->toPayload();

    expect($payload['colors'])->not->toBeEmpty();
    expect($payload['colors'][0])->toHaveKey('dark');
    expect($payload['colors'][0])->toHaveKey('light');
});

it('titleFont flows to payload', function () {
    $payload = GenericChart::make()
        ->dataset('S', [1])
        ->titleFont(size: 18, weight: 'bold', family: 'Inter')
        ->toPayload();

    expect($payload['titleFont'])->toBe(['size' => 18, 'weight' => 'bold', 'family' => 'Inter']);
});

it('legendFont partial values flow to payload', function () {
    $payload = GenericChart::make()
        ->dataset('S', [1])
        ->legendFont(size: 12)
        ->toPayload();

    expect($payload['legendFont'])->toBe(['size' => 12]);
});

it('tooltipFont flows to payload', function () {
    $payload = GenericChart::make()
        ->dataset('S', [1])
        ->tooltipFont(family: 'Inter')
        ->toPayload();

    expect($payload['tooltipFont'])->toBe(['family' => 'Inter']);
});

it('hex string in colors() BC: resolves to same-hex ColorValue pair', function () {
    $payload = GenericChart::make()
        ->dataset('S', [1])
        ->colors(['#ff0000'])
        ->toPayload();

    expect($payload['colors'][0])->toBe(['dark' => '#ff0000', 'light' => '#ff0000']);
});

it('TwColor in colors() resolves correctly', function () {
    $payload = GenericChart::make()
        ->dataset('S', [1])
        ->colors([TwColor::Emerald500])
        ->toPayload();

    expect($payload['colors'][0]['dark'])->toBe(TwColor::Emerald500->value);
});
