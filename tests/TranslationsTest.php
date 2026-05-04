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

it('translates exception messages to pt_BR when locale switches', function () {
    Lang::setLocale('pt_BR');

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
    foreach (['en', 'pt_BR', 'es'] as $locale) {
        Lang::setLocale($locale);
        expect(__('livecharts::livecharts.install.completed'))
            ->not->toBe('livecharts::livecharts.install.completed');
    }
});

it('resolves pt_BR translations when APP_LOCALE uses underscore convention', function () {
    Lang::setLocale('pt_BR');

    expect(__('livecharts::livecharts.install.starting'))
        ->toBe('Instalando o LiveCharts...');
});

it('falls back to en when locale is unknown and fallback is en', function () {
    config()->set('app.fallback_locale', 'en');
    Lang::setLocale('fr');

    expect(__('livecharts::livecharts.install.completed'))
        ->toBe('LiveCharts installed successfully.');
});

it('lang source directory uses pt_BR not pt-BR', function () {
    $langBase = __DIR__.'/../resources/lang';

    expect(is_dir($langBase.'/pt_BR'))->toBeTrue();
    expect(is_dir($langBase.'/pt-BR'))->toBeFalse();
});

it('en and es locales resolve after pt-BR directory rename', function () {
    Lang::setLocale('en');
    expect(__('livecharts::livecharts.install.completed'))
        ->not->toBe('livecharts::livecharts.install.completed');

    Lang::setLocale('es');
    expect(__('livecharts::livecharts.install.completed'))
        ->not->toBe('livecharts::livecharts.install.completed');
});
