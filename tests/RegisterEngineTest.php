<?php

declare(strict_types=1);

use Matheusmarnt\LiveCharts\Engines\ApexChartsAdapter;
use Matheusmarnt\LiveCharts\Engines\EngineFactory;
use Matheusmarnt\LiveCharts\Facades\LiveCharts;

it('registers a custom engine via the facade', function () {
    LiveCharts::registerEngine('custom-apex', ApexChartsAdapter::class);

    expect(EngineFactory::resolve('custom-apex'))->toBeInstanceOf(ApexChartsAdapter::class);
});

it('overrides an existing engine binding', function () {
    LiveCharts::registerEngine('apexcharts', ApexChartsAdapter::class);

    expect(EngineFactory::resolve('apexcharts'))->toBeInstanceOf(ApexChartsAdapter::class);
});
