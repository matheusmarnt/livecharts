<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Blade;
use Matheusmarnt\LiveCharts\Support\AssetManager;

// ─── flushPendingPushes ───────────────────────────────────────────────────────

it('flushPendingPushes pushes engine script and bootstrap to the Blade stack', function () {
    config()->set('livecharts.assets.mode', 'cdn');
    config()->set('livecharts.assets.auto_inject', true);

    $manager = new AssetManager;
    $manager->registerEngine('apexcharts');
    $manager->flushPendingPushes();

    $output = app('view')->yieldPushContent('livecharts-scripts');

    expect($output)->toContain('apexcharts');
    expect($output)->toContain('<script>'); // inline bootstrap
});

it('flushPendingPushes is idempotent — calling twice does not duplicate scripts', function () {
    config()->set('livecharts.assets.mode', 'cdn');
    config()->set('livecharts.assets.auto_inject', true);

    $manager = new AssetManager;
    $manager->registerEngine('apexcharts');
    $manager->flushPendingPushes();
    $manager->flushPendingPushes(); // second call, same state

    $output = app('view')->yieldPushContent('livecharts-scripts');

    // apexcharts.js appears exactly once
    expect(substr_count($output, 'apexcharts'))->toBe(1);
});

it('flushPendingPushes pushes only new assets on second component render', function () {
    config()->set('livecharts.assets.mode', 'cdn');
    config()->set('livecharts.assets.cdn.chartjs', 'https://cdn.example.com/chartjs.js');
    config()->set('livecharts.assets.auto_inject', true);

    $manager = new AssetManager;

    // First component: apexcharts
    $manager->registerEngine('apexcharts');
    $manager->flushPendingPushes();

    // Second component: chartjs (bootstrap already pushed)
    $manager->registerEngine('chartjs');
    $manager->flushPendingPushes();

    $output = app('view')->yieldPushContent('livecharts-scripts');

    expect($output)->toContain('apexcharts');
    expect($output)->toContain('chartjs.js');
    // inline bootstrap appears exactly once
    expect(substr_count($output, '<script>'))->toBe(1);
});

it('flushPendingPushes omits bootstrap when auto_inject is disabled', function () {
    config()->set('livecharts.assets.mode', 'cdn');
    config()->set('livecharts.assets.auto_inject', false);

    $manager = new AssetManager;
    $manager->registerEngine('apexcharts');
    $manager->flushPendingPushes();

    $output = app('view')->yieldPushContent('livecharts-scripts');

    expect($output)->toContain('apexcharts');
    expect($output)->not->toContain('<script>'); // no inline bootstrap
});

it('flushPendingPushes emits local URL in local mode', function () {
    config()->set('livecharts.assets.mode', 'local');

    $manager = new AssetManager;
    $manager->registerEngine('apexcharts');
    $manager->flushPendingPushes();

    $output = app('view')->yieldPushContent('livecharts-scripts');

    expect($output)->toContain('vendor/livecharts/js/apexcharts.js');
});

it('flushPendingPushes emits onerror fallback in both mode', function () {
    config()->set('livecharts.assets.mode', 'both');
    config()->set('livecharts.assets.cdn.apexcharts', 'https://cdn.example.com/apexcharts.js');

    $manager = new AssetManager;
    $manager->registerEngine('apexcharts');
    $manager->flushPendingPushes();

    $output = app('view')->yieldPushContent('livecharts-scripts');

    expect($output)->toContain('onerror=');
    expect($output)->toContain('vendor/livecharts/js/apexcharts.js');
    expect($output)->toContain('https://cdn.example.com/apexcharts.js');
});

// ─── Directive compiles to yieldPushContent ──────────────────────────────────

it('liveChartsScripts directive compiles to yieldPushContent call', function () {
    $compiled = Blade::compileString('@liveChartsScripts');

    expect($compiled)->toContain("yieldPushContent('livecharts-scripts')");
    expect($compiled)->not->toContain("view('livecharts::scripts')");
});
