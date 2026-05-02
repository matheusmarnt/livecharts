<?php

use Matheusmarnt\LiveCharts\Livewire\LiveChartsComponent;
use Matheusmarnt\LiveCharts\Charts\GenericChart;
use Livewire\Livewire;

it('can render the livecharts component', function () {
    $chart = (new GenericChart())
        ->type('line')
        ->title('Test Chart')
        ->labels(['A', 'B'])
        ->dataset('Series 1', [10, 20]);

    Livewire::test(LiveChartsComponent::class, ['chart' => $chart])
        ->assertStatus(200)
        ->assertSee('Test Chart')
        ->assertSee('apexcharts');
});
