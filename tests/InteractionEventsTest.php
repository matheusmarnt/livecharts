<?php

use Matheusmarnt\LiveCharts\Charts\GenericChart;

it('includes all interaction events in the payload', function () {
    $chart = (new GenericChart)
        ->dataset('Series', [1])
        ->onDataPointClick('clicked')
        ->onZoom('zoomed')
        ->onSelection('selected')
        ->onScroll('scrolled');

    $payload = $chart->toPayload();

    expect($payload['onDataPointClick'])->toBe('clicked');
    expect($payload['onZoom'])->toBe('zoomed');
    expect($payload['onSelection'])->toBe('selected');
    expect($payload['onScroll'])->toBe('scrolled');
});
