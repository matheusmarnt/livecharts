<?php

use Matheusmarnt\LiveCharts\Engines\EngineFactory;
use Matheusmarnt\LiveCharts\Engines\ApexChartsAdapter;
use Matheusmarnt\LiveCharts\Support\ChartPayload;
use Matheusmarnt\LiveCharts\Exceptions\UnknownEngineException;

it('can register and resolve engines', function () {
    EngineFactory::register('test', ApexChartsAdapter::class);

    expect(EngineFactory::resolve('test'))->toBeInstanceOf(ApexChartsAdapter::class);
});

it('throws exception for unknown engine', function () {
    EngineFactory::resolve('non-existent');
})->throws(UnknownEngineException::class);

it('apexcharts adapter builds correct options', function () {
    $payload = new ChartPayload(
        type: 'line',
        engine: 'apexcharts',
        title: 'Test Chart',
        datasets: [
            ['name' => 'Series 1', 'data' => [10, 20, 30]]
        ],
        labels: ['A', 'B', 'C']
    );

    $adapter = new ApexChartsAdapter();
    $options = $adapter->build($payload);

    expect($options['chart']['type'])->toBe('line');
    expect($options['title']['text'])->toBe('Test Chart');
    expect($options['series'][0]['name'])->toBe('Series 1');
    expect($options['series'][0]['data'])->toBe([10, 20, 30]);
    expect($options['labels'])->toBe(['A', 'B', 'C']);
});
