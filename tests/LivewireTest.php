<?php

use Livewire\Livewire;
use Matheusmarnt\LiveCharts\Charts\GenericChart;
use Matheusmarnt\LiveCharts\Livewire\LiveChartsComponent;

it('can render the livecharts component', function () {
    $chart = (new GenericChart)
        ->type('line')
        ->title('Test Chart')
        ->labels(['A', 'B'])
        ->dataset('Series 1', [10, 20]);

    Livewire::test(LiveChartsComponent::class, ['chart' => $chart])
        ->assertStatus(200)
        ->assertSee('Test Chart')
        ->assertSee('apexcharts');
});
