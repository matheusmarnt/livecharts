<?php

declare(strict_types=1);

use Livewire\Livewire;
use Matheusmarnt\LiveCharts\Charts\Chart;
use Matheusmarnt\LiveCharts\Facades\LiveCharts;
use Matheusmarnt\LiveCharts\Livewire\LiveChartsComponent;

/**
 * UC-01 — Analytics Dashboard
 *
 * Six independent chart classes pass through the Livewire component, each
 * mounting with a unique id, producing the expected payload type, and reaching
 * a 200 render.
 */
it('UC-01: renders six dashboard charts with distinct ids and matching types', function () {
    $charts = [
        ['line', LiveCharts::line()->title('Revenue')],
        ['bar', LiveCharts::bar()->title('Acquisition')],
        ['area', LiveCharts::area()->title('Churn')],
        ['donut', LiveCharts::donut()->title('Mix')],
        ['line', LiveCharts::line()->title('NPS')],
        ['heatmap', LiveCharts::heatmap()->title('Cohort')],
    ];

    $ids = [];

    foreach ($charts as [$type, $chart]) {
        /** @var Chart $chart */
        $chart->labels(['Jan', 'Feb'])->dataset('S', [1, 2]);

        $component = Livewire::test(LiveChartsComponent::class, ['chart' => $chart])
            ->assertStatus(200)
            ->assertSee($chart->toPayload()['title']);

        expect($component->payload['type'])->toBe($type);
        $ids[] = $component->id;
    }

    expect($ids)->toHaveCount(6);
    expect(array_unique($ids))->toHaveCount(6);
});

/**
 * UC-02 — Admin Panel with Drill-Down
 *
 * onDataPointClick event name propagates from the fluent builder into the
 * payload and reaches the rendered Alpine x-data attribute, where livecharts.js
 * uses it to dispatch the matching Livewire event on click.
 */
it('UC-02: onDataPointClick event flows through payload into rendered Alpine data', function () {
    $chart = LiveCharts::bar()
        ->title('Sales by Region')
        ->labels(['NA', 'EU', 'APAC'])
        ->dataset('Total', [100, 200, 150])
        ->onDataPointClick('region-clicked');

    expect($chart->toPayload()['onDataPointClick'])->toBe('region-clicked');

    $component = Livewire::test(LiveChartsComponent::class, ['chart' => $chart]);

    expect($component->payload['onDataPointClick'])->toBe('region-clicked');

    $component
        ->assertStatus(200)
        ->assertSeeHtml('region-clicked');
});

/**
 * UC-03 — Real-Time Metrics Monitor
 *
 * pollEvery(5000) renders wire:poll.5000ms="refresh" and the refresh action
 * dispatches livecharts:refreshed scoped to the chart id, allowing userland
 *
 * @on listeners to hydrate fresh data.
 */
it('UC-03: 5s polling renders directive and refresh dispatches scoped event', function () {
    $chart = LiveCharts::line()
        ->title('CPU Load')
        ->labels(['t-2', 't-1', 't'])
        ->dataset('CPU', [40, 55, 70])
        ->pollEvery(5000);

    Livewire::test(LiveChartsComponent::class, ['chart' => $chart, 'id' => 'cpu-monitor'])
        ->assertStatus(200)
        ->assertSee('wire:poll.5000ms="refresh"', escape: false)
        ->call('refresh')
        ->assertDispatched('livecharts:refreshed', id: 'cpu-monitor');
});

/**
 * UC-04 — Multi-Tenant SaaS Metrics
 *
 * The same chart class fed two different tenant datasets renders two
 * components with distinct ids and distinct payload data — package code
 * stays tenancy-agnostic.
 */
it('UC-04: same chart class with tenant-scoped data yields distinct payloads and ids', function () {
    $tenantA = LiveCharts::bar()
        ->title('Tenant A: MRR')
        ->labels(['Jan', 'Feb', 'Mar'])
        ->dataset('MRR', [1000, 1200, 1450]);

    $tenantB = LiveCharts::bar()
        ->title('Tenant B: MRR')
        ->labels(['Jan', 'Feb', 'Mar'])
        ->dataset('MRR', [340, 360, 420]);

    $a = Livewire::test(LiveChartsComponent::class, ['chart' => $tenantA])
        ->assertStatus(200)
        ->assertSee('Tenant A: MRR');

    $b = Livewire::test(LiveChartsComponent::class, ['chart' => $tenantB])
        ->assertStatus(200)
        ->assertSee('Tenant B: MRR');

    expect($a->id)->not->toBe($b->id);
    expect($a->payload['datasets'][0]['data'])->toBe([1000, 1200, 1450]);
    expect($b->payload['datasets'][0]['data'])->toBe([340, 360, 420]);
    expect($a->payload['type'])->toBe($b->payload['type']);
});
