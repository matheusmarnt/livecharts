---
title: Configuration
description: Tune LiveCharts via the published config file and environment variables.
---

After running `php artisan livecharts:install`, you'll find `config/livecharts.php` in your app. The most common knobs are documented here. For the full reference, see the [Configuration File](../../reference/configuration/) page.

## Environment variables

| Variable | Default | Purpose |
|---|---|---|
| `LIVECHARTS_DEFAULT_ENGINE` | `apexcharts` | Engine used when a chart does not call `engine()`. |
| `LIVECHARTS_ASSETS_MODE` | `both` | How `@liveChartsScripts` resolves engine assets. |
| `LIVECHARTS_THEME` | `auto` | Default theme resolution: `auto`, `light`, `dark`. |

## Asset modes

| Mode | Behaviour |
|---|---|
| `local` | Serve only the bundles published under `public/vendor/livecharts`. |
| `cdn` | Serve only the public CDN URLs. |
| `both` | Serve local first, fall back to CDN via the `<script>` `onerror` handler. |

`both` is the default — and recommended for production. It keeps charts working even when the local copy hasn't been re-published after a deployment.

## Default engine

Switch the global default without touching every chart:

```php
// config/livecharts.php
return [
    'default_engine' => env('LIVECHARTS_DEFAULT_ENGINE', 'chartjs'),
    // ...
];
```

Per-chart override stays available:

```php
LiveCharts::bar()->engine('apexcharts');
```

## Engine registry

Each engine is registered with its adapter, the local asset path, and the CDN URL:

```php
'engines' => [
    'apexcharts' => [
        'adapter' => Matheusmarnt\LiveCharts\Engines\ApexChartsAdapter::class,
        'assets' => [
            'local' => '/vendor/livecharts/apexcharts.js',
            'cdn'   => 'https://cdn.jsdelivr.net/npm/apexcharts@5.10.6/dist/apexcharts.min.js',
        ],
    ],
    // ...
],
```

You can register additional engines at runtime — see [Custom Engines](../../engines/custom/).
