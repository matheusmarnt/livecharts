<?php

use Matheusmarnt\LiveCharts\Charts\GenericChart;

it('includes onDataPointClick in the payload', function () {
    $chart = (new GenericChart())
        ->onDataPointClick('my-event');

    $payload = $chart->toPayload();

    expect($payload['onDataPointClick'])->toBe('my-event');
});
