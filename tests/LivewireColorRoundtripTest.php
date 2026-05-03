<?php

declare(strict_types=1);

use Livewire\Livewire;
use Matheusmarnt\LiveCharts\Enums\TwColor;
use Matheusmarnt\LiveCharts\Facades\LiveCharts;
use Matheusmarnt\LiveCharts\Livewire\LiveChartsComponent;

/**
 * Bug 1 — colors array serialized as plain arrays by Livewire; render() must
 * reconstruct ColorValue objects to avoid TypeError in normalizeColors().
 */
it('render() does not throw TypeError when payload colors are serialized arrays (Bug 1)', function () {
    $chart = LiveCharts::line()
        ->labels(['A', 'B'])
        ->dataset('S', [1, 2])
        ->colors([TwColor::Blue500, TwColor::Red500]);

    Livewire::test(LiveChartsComponent::class, ['chart' => $chart])
        ->assertStatus(200)
        ->call('refresh')
        ->assertStatus(200);
});

it('colors roundtrip: payload stores arrays, render() reconstructs ColorValues (Bug 1)', function () {
    $chart = LiveCharts::bar()
        ->labels(['Jan', 'Feb'])
        ->dataset('S', [10, 20])
        ->colors([TwColor::Amber300, TwColor::Amber600]);

    $component = Livewire::test(LiveChartsComponent::class, ['chart' => $chart]);

    // After mount, payload stores serialized arrays (not ColorValue objects)
    expect($component->payload['colors'][0])->toBeArray();
    expect($component->payload['colors'][0])->toHaveKey('dark');
    expect($component->payload['colors'][0])->toHaveKey('light');

    // Re-render via refresh must not throw
    $component->call('refresh')->assertStatus(200);
});

/**
 * Bug 5 — 9 individual color slots (titleColor…backgroundColor) were not
 * reconstructed in render(), causing null to reach the adapter for each slot.
 */
it('render() reconstructs all 9 color slots from serialized arrays (Bug 5)', function () {
    $chart = LiveCharts::line()
        ->labels(['A'])
        ->dataset('S', [1])
        ->titleColor(TwColor::Slate100, TwColor::Slate900)
        ->subtitleColor(TwColor::Blue200, TwColor::Blue800)
        ->legendColor(TwColor::Green300, TwColor::Green700)
        ->labelsColor(TwColor::Gray400, TwColor::Gray600)
        ->tooltipColor(TwColor::Slate300, TwColor::Slate700)
        ->axisColor(TwColor::Zinc300, TwColor::Zinc700)
        ->gridColor(TwColor::Slate200, TwColor::Slate800)
        ->dataLabelsColor(TwColor::White, TwColor::Slate900)
        ->backgroundColor(TwColor::White, TwColor::Slate900);

    Livewire::test(LiveChartsComponent::class, ['chart' => $chart])
        ->assertStatus(200)
        ->call('refresh')
        ->assertStatus(200);
});

it('null color slots do not cause errors in render() after serialization (Bug 5)', function () {
    $chart = LiveCharts::line()
        ->labels(['A'])
        ->dataset('S', [1])
        ->titleColor(TwColor::Blue500, TwColor::Blue900);
    // all other slots intentionally null

    Livewire::test(LiveChartsComponent::class, ['chart' => $chart])
        ->assertStatus(200)
        ->call('refresh')
        ->assertStatus(200);
});
