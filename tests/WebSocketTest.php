<?php

use Matheusmarnt\LiveCharts\Charts\GenericChart;

it('includes broadcast configuration in the payload', function () {
    $chart = (new GenericChart)
        ->broadcastOn('my-channel')
        ->broadcastAs('my-event');

    $payload = $chart->toPayload();

    expect($payload['broadcastOn'])->toBe('my-channel');
    expect($payload['broadcastAs'])->toBe('my-event');
});
