<?php

use Matheusmarnt\LiveCharts\Support\AssetManager;
use Illuminate\Support\Facades\Blade;

it('can register and retrieve required scripts', function () {
    $manager = new AssetManager();
    $manager->registerEngine('apexcharts');

    $scripts = $manager->getRequiredScripts();

    expect($scripts)->toHaveCount(1);
    expect($scripts[0])->toBe(config('livecharts.assets.cdn.apexcharts'));
});

it('marks scripts as rendered', function () {
    $manager = new AssetManager();
    expect($manager->hasBeenRendered())->toBeFalse();

    $manager->markAsRendered();
    expect($manager->hasBeenRendered())->toBeTrue();
});

it('renders the blade directive correctly', function () {
    $directive = Blade::compileString('@liveChartsScripts');
    
    expect($directive)->toContain("echo view('livecharts::scripts')->render()");
});
