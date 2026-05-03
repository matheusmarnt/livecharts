---
title: Configuration File
description: Full reference for config/livecharts.php.
---

The config file is published by `livecharts:install` (or `vendor:publish --tag=livecharts-config`).

## Full file

```php
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Engine
    |--------------------------------------------------------------------------
    |
    | The engine used when a chart does not call ->engine() explicitly.
    |
    */

    'default_engine' => env('LIVECHARTS_DEFAULT_ENGINE', 'apexcharts'),

    /*
    |--------------------------------------------------------------------------
    | Asset Mode
    |--------------------------------------------------------------------------
    |
    | How @liveChartsScripts resolves engine assets:
    |   - "local"  — only the published copy under public/vendor/livecharts
    |   - "cdn"    — only the CDN URL
    |   - "both"   — local first, CDN fallback via <script> onerror
    |
    */

    'assets_mode' => env('LIVECHARTS_ASSETS_MODE', 'both'),

    /*
    |--------------------------------------------------------------------------
    | Theme
    |--------------------------------------------------------------------------
    |
    | Default theme resolution: "auto", "light", "dark".
    |
    */

    'theme' => env('LIVECHARTS_THEME', 'auto'),

    /*
    |--------------------------------------------------------------------------
    | Polling
    |--------------------------------------------------------------------------
    |
    | Default interval (in seconds) for Chart::poll(). Override per-chart with
    | Chart::pollEvery($seconds).
    |
    */

    'poll_interval' => env('LIVECHARTS_POLL_INTERVAL', 30),

    /*
    |--------------------------------------------------------------------------
    | Engines
    |--------------------------------------------------------------------------
    |
    | Map of engine slug → adapter class + asset URLs. Add your own engines
    | here, or register them at runtime via LiveCharts::registerEngine().
    |
    */

    'engines' => [
        'apexcharts' => [
            'adapter' => Matheusmarnt\LiveCharts\Engines\ApexChartsAdapter::class,
            'assets'  => [
                'local' => '/vendor/livecharts/apexcharts.js',
                'cdn'   => 'https://cdn.jsdelivr.net/npm/apexcharts@5.10.6/dist/apexcharts.min.js',
            ],
        ],

        'chartjs' => [
            'adapter' => Matheusmarnt\LiveCharts\Engines\ChartJsAdapter::class,
            'assets'  => [
                'local' => '/vendor/livecharts/chartjs.js',
                'cdn'   => 'https://cdn.jsdelivr.net/npm/chart.js@4.5.1/dist/chart.umd.js',
            ],
            'plugins' => [
                'treemap' => [
                    'local' => '/vendor/livecharts/chartjs-treemap.js',
                    'cdn'   => 'https://cdn.jsdelivr.net/npm/chartjs-chart-treemap@3/dist/chartjs-chart-treemap.min.js',
                ],
                // matrix, sankey, financial, luxon, adapter-luxon …
            ],
        ],
    ],

];
```

## Environment variables summary

| Variable | Default |
|---|---|
| `LIVECHARTS_DEFAULT_ENGINE` | `apexcharts` |
| `LIVECHARTS_ASSETS_MODE` | `both` |
| `LIVECHARTS_THEME` | `auto` |
| `LIVECHARTS_POLL_INTERVAL` | `30` |

## Hydration

The service provider hydrates `EngineFactory` from `config('livecharts.engines')` in `packageBooted()`. If you mutate the config at runtime (e.g. in a test), you'll need to re-resolve the factory from the container to pick up the change.
