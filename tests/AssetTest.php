<?php

use Illuminate\Support\Facades\Blade;
use Matheusmarnt\LiveCharts\Support\AssetManager;

it('can register and retrieve required scripts', function () {
    config()->set('livecharts.assets.mode', 'cdn');

    $manager = new AssetManager;
    $manager->registerEngine('apexcharts');

    $scripts = $manager->getRequiredScripts();

    expect($scripts)->toHaveCount(1);
    expect($scripts[0]['src'])->toBe(config('livecharts.assets.cdn.apexcharts'));
});

it('can register multiple assets', function () {
    config()->set('livecharts.assets.mode', 'cdn');
    config()->set('livecharts.assets.cdn.chartjs-treemap', 'https://example.com/treemap.js');
    config()->set('livecharts.assets.cdn.chartjs', 'https://example.com/chartjs.js');

    $manager = new AssetManager;
    $manager->registerAsset('chartjs');
    $manager->registerAsset('chartjs-treemap');

    $scripts = $manager->getRequiredScripts();

    expect($scripts)->toHaveCount(2);
    expect($scripts[0]['src'])->toBe('https://example.com/chartjs.js');
    expect($scripts[1]['src'])->toBe('https://example.com/treemap.js');
});

it('serves local assets first with CDN fallback in both mode', function () {
    config()->set('livecharts.assets.mode', 'both');

    $manager = new AssetManager;
    $manager->registerEngine('apexcharts');

    $scripts = $manager->getRequiredScripts();

    expect($scripts)->toHaveCount(1);
    expect($scripts[0]['src'])->toBe(asset('vendor/livecharts/js/apexcharts.js'));
    expect($scripts[0]['fallback'])->toBe(config('livecharts.assets.cdn.apexcharts'));
});

it('marks scripts as rendered', function () {
    $manager = new AssetManager;
    expect($manager->hasBeenRendered())->toBeFalse();

    $manager->markAsRendered();
    expect($manager->hasBeenRendered())->toBeTrue();
});

it('renders the blade directive correctly', function () {
    $directive = Blade::compileString('@liveChartsScripts');

    expect($directive)->toContain("echo view('livecharts::scripts')->render()");
});
