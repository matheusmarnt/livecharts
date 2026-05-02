<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Lang;
use Matheusmarnt\LiveCharts\Charts\LineChart;
use Matheusmarnt\LiveCharts\Engines\ApexChartsAdapter;
use Matheusmarnt\LiveCharts\Exceptions\EmptyDatasetException;
use Matheusmarnt\LiveCharts\Exceptions\InvalidChartTypeException;
use Matheusmarnt\LiveCharts\Support\ChartPayload;

it('loads en translations by default', function () {
    expect(__('livecharts::livecharts.exceptions.unknown_engine', ['name' => 'x', 'registered' => 'y']))
        ->toContain('not registered')
        ->toContain('[x]');
});

it('translates exception messages to pt-BR when locale switches', function () {
    Lang::setLocale('pt-BR');

    try {
        LineChart::make()->labels(['Jan'])->toPayload();
    } catch (EmptyDatasetException $e) {
        expect($e->getMessage())->toContain('não possui datasets');
    }
});

it('translates exception messages to es when locale switches', function () {
    Lang::setLocale('es');

    try {
        $payload = new ChartPayload(type: 'bogus', engine: 'apexcharts');
        (new ApexChartsAdapter)->build($payload);
    } catch (InvalidChartTypeException $e) {
        expect($e->getMessage())->toContain('no es compatible');
    }
});

it('exposes all three locales', function () {
    foreach (['en', 'pt-BR', 'es'] as $locale) {
        Lang::setLocale($locale);
        expect(__('livecharts::livecharts.install.completed'))
            ->not->toBe('livecharts::livecharts.install.completed');
    }
});
