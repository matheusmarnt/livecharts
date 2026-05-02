<?php

use Livewire\Livewire;
use Matheusmarnt\LiveCharts\Charts\GenericChart;
use Matheusmarnt\LiveCharts\Livewire\LiveChartsComponent;

it('sets pollEvery via poll() alias', function () {
    $chart = (new GenericChart)
        ->type('line')
        ->labels(['A', 'B'])
        ->dataset('Series 1', [10, 20])
        ->poll(5000);

    expect($chart->toPayload()['pollEvery'])->toBe(5000);
});

it('sets pollEvery via pollEvery() method', function () {
    $chart = (new GenericChart)
        ->type('line')
        ->labels(['A', 'B'])
        ->dataset('Series 1', [10, 20])
        ->pollEvery(2500);

    expect($chart->toPayload()['pollEvery'])->toBe(2500);
});

it('defaults pollEvery to 0 when not configured', function () {
    $chart = (new GenericChart)
        ->type('line')
        ->labels(['A', 'B'])
        ->dataset('Series 1', [10, 20]);

    expect($chart->toPayload()['pollEvery'])->toBe(0);
});

it('renders wire:poll directive when pollEvery > 0', function () {
    $chart = (new GenericChart)
        ->type('line')
        ->labels(['A', 'B'])
        ->dataset('Series 1', [10, 20])
        ->poll(3000);

    Livewire::test(LiveChartsComponent::class, ['chart' => $chart])
        ->assertStatus(200)
        ->assertSee('wire:poll.3000ms="refresh"', escape: false);
});

it('omits wire:poll directive when pollEvery is 0', function () {
    $chart = (new GenericChart)
        ->type('line')
        ->labels(['A', 'B'])
        ->dataset('Series 1', [10, 20]);

    Livewire::test(LiveChartsComponent::class, ['chart' => $chart])
        ->assertStatus(200)
        ->assertDontSee('wire:poll', escape: false);
});

it('dispatches livecharts:refreshed event on refresh()', function () {
    $chart = (new GenericChart)
        ->type('line')
        ->labels(['A', 'B'])
        ->dataset('Series 1', [10, 20])
        ->poll(1000);

    Livewire::test(LiveChartsComponent::class, ['chart' => $chart, 'id' => 'chart-test'])
        ->call('refresh')
        ->assertDispatched('livecharts:refreshed', id: 'chart-test');
});
