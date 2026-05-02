<?php

use Matheusmarnt\LiveCharts\Charts\GenericChart;

it('can set specialized configuration helpers', function () {
    $chart = GenericChart::make()
        ->xaxis(['categories' => ['A', 'B']])
        ->yaxis(['title' => ['text' => 'Y']])
        ->grid(['show' => false])
        ->stroke(['curve' => 'smooth'])
        ->markers(['size' => 5])
        ->dataLabels(['enabled' => true]);

    $payload = $chart->toPayload();

    expect($payload['xaxis'])->toBe(['categories' => ['A', 'B']]);
    expect($payload['yaxis'])->toBe(['title' => ['text' => 'Y']]);
    expect($payload['grid'])->toBe(['show' => false]);
    expect($payload['stroke'])->toBe(['curve' => 'smooth']);
    expect($payload['markers'])->toBe(['size' => 5]);
    expect($payload['dataLabels'])->toBe(['enabled' => true]);
});
