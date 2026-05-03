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

it('renders the blade directive using the livecharts-scripts push stack', function () {
    $directive = Blade::compileString('@liveChartsScripts');

    expect($directive)->toContain("yieldPushContent('livecharts-scripts')");
});

it('returns the dist bootstrap script when present', function () {
    $root = sys_get_temp_dir().'/livecharts-bootstrap-'.uniqid();
    mkdir($root.'/resources/dist', 0777, true);
    mkdir($root.'/resources/js', 0777, true);
    file_put_contents($root.'/resources/dist/livecharts.js', 'DIST');
    file_put_contents($root.'/resources/js/livecharts.js', 'SOURCE');

    $manager = new AssetManager($root);

    expect($manager->getBootstrapScript())->toBe('DIST');

    unlink($root.'/resources/dist/livecharts.js');
    unlink($root.'/resources/js/livecharts.js');
    rmdir($root.'/resources/dist');
    rmdir($root.'/resources/js');
    rmdir($root.'/resources');
    rmdir($root);
});

it('falls back to the source bundle when dist is missing', function () {
    $root = sys_get_temp_dir().'/livecharts-bootstrap-'.uniqid();
    mkdir($root.'/resources/js', 0777, true);
    file_put_contents($root.'/resources/js/livecharts.js', 'SOURCE');

    $manager = new AssetManager($root);

    expect($manager->getBootstrapScript())->toBe('SOURCE');

    unlink($root.'/resources/js/livecharts.js');
    rmdir($root.'/resources/js');
    rmdir($root.'/resources');
    rmdir($root);
});

it('returns null when neither bundle exists', function () {
    $root = sys_get_temp_dir().'/livecharts-bootstrap-'.uniqid();
    mkdir($root, 0777, true);

    $manager = new AssetManager($root);

    expect($manager->getBootstrapScript())->toBeNull();

    rmdir($root);
});

it('resolves the package root by default and ships a bootstrap script', function () {
    $manager = new AssetManager;

    $bootstrap = $manager->getBootstrapScript();

    expect($bootstrap)->toBeString();
    expect($bootstrap)->not->toBe('');
});
