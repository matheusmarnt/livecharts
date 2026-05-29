<?php

declare(strict_types=1);

use Matheusmarnt\LiveCharts\Support\AssetManager;

// ─── engineScriptsHtml ────────────────────────────────────────────────────────

it('engineScriptsHtml returns script tag for registered engine', function () {
    config()->set('livecharts.assets.mode', 'cdn');
    config()->set('livecharts.assets.cdn.apexcharts', 'https://cdn.example.com/apexcharts.js');

    $manager = new AssetManager;
    $manager->registerEngine('apexcharts');

    expect($manager->engineScriptsHtml())->toContain('apexcharts');
});

it('engineScriptsHtml returns empty string when no engines registered', function () {
    $manager = new AssetManager;

    expect($manager->engineScriptsHtml())->toBe('');
});

it('engineScriptsHtml includes all registered engines', function () {
    config()->set('livecharts.assets.mode', 'cdn');
    config()->set('livecharts.assets.cdn.apexcharts', 'https://cdn.example.com/apexcharts.js');
    config()->set('livecharts.assets.cdn.chartjs', 'https://cdn.example.com/chartjs.js');

    $manager = new AssetManager;
    $manager->registerEngine('apexcharts');
    $manager->registerEngine('chartjs');

    $html = $manager->engineScriptsHtml();

    expect($html)->toContain('apexcharts');
    expect($html)->toContain('chartjs');
});

// ─── bootstrapScriptHtml ─────────────────────────────────────────────────────

it('bootstrapScriptHtml returns script tag with bootstrap code and config bridge', function () {
    config()->set('livecharts.assets.auto_inject', true);
    config()->set('livecharts.theme.auto_detect', 'class');

    $manager = new AssetManager;
    $html = $manager->bootstrapScriptHtml();

    expect($html)->toContain('<script>');
    expect($html)->toContain('LiveChartsConfig');
    expect($html)->toContain('themeStrategy');
    expect($html)->toContain('"class"');
});

it('bootstrapScriptHtml returns empty string when auto_inject is false', function () {
    config()->set('livecharts.assets.auto_inject', false);

    $manager = new AssetManager;

    expect($manager->bootstrapScriptHtml())->toBe('');
});

it('bootstrapScriptHtml embeds themeStrategy from config', function () {
    config()->set('livecharts.assets.auto_inject', true);
    config()->set('livecharts.theme.auto_detect', 'media');

    $manager = new AssetManager;
    $html = $manager->bootstrapScriptHtml();

    expect($html)->toContain('"media"');
});

// ─── strategy=navigate does not push to Blade stack ──────────────────────────

it('navigate strategy leaves livecharts-scripts stack empty', function () {
    config()->set('livecharts.assets.strategy', 'navigate');
    config()->set('livecharts.assets.mode', 'cdn');

    $manager = new AssetManager;
    $manager->registerEngine('apexcharts');
    // navigate strategy: flushPendingPushes is NOT called

    $stackOutput = app('view')->yieldPushContent('livecharts-scripts');

    expect($stackOutput)->toBe('');
});

// ─── strategy=stack still pushes to Blade stack ──────────────────────────────

it('stack strategy still pushes engine script and bootstrap to Blade stack', function () {
    config()->set('livecharts.assets.strategy', 'stack');
    config()->set('livecharts.assets.mode', 'cdn');
    config()->set('livecharts.assets.auto_inject', true);

    $manager = new AssetManager;
    $manager->registerEngine('apexcharts');
    $manager->flushPendingPushes();

    $output = app('view')->yieldPushContent('livecharts-scripts');

    expect($output)->toContain('apexcharts');
    expect($output)->toContain('<script>');
});

// ─── navigate strategy output is complete ────────────────────────────────────

it('navigate strategy produces engine + bootstrap html ready for @assets injection', function () {
    config()->set('livecharts.assets.mode', 'cdn');
    config()->set('livecharts.assets.cdn.apexcharts', 'https://cdn.example.com/apexcharts.js');
    config()->set('livecharts.assets.auto_inject', true);
    config()->set('livecharts.theme.auto_detect', 'class');

    $manager = new AssetManager;
    $manager->registerEngine('apexcharts');

    $engineHtml = $manager->engineScriptsHtml();
    $bootstrapHtml = $manager->bootstrapScriptHtml();

    expect($engineHtml)->toContain('<script');
    expect($engineHtml)->toContain('apexcharts');
    expect($bootstrapHtml)->toContain('<script>');
    expect($bootstrapHtml)->toContain('LiveChartsConfig');
    // combined output has no livecharts-scripts stack side-effect
    expect(app('view')->yieldPushContent('livecharts-scripts'))->toBe('');
});
