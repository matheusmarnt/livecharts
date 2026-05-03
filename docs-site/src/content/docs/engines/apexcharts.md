---
title: ApexCharts
description: Use ApexCharts as the rendering engine.
---

ApexCharts is the default engine. It supports a wide range of chart types including line, area, bar, column, pie, donut, radar, scatter, heatmap, treemap, candlestick, boxplot, and radial bar.

## Selecting the Engine

ApexCharts is selected by default. To make it explicit, call `engine()` on the chart:

```php
use Matheusmarnt\LiveCharts\Chart;

return Chart::make()
    ->engine('apexcharts')
    ->type('line')
    ->labels(['Jan', 'Feb', 'Mar', 'Apr'])
    ->dataset('Revenue', [10, 20, 30, 40]);
```

## Bundled Assets

The package ships a pre-built IIFE bundle of ApexCharts (`^5.10.6`) at `resources/dist/apexcharts.js`. The `@liveChartsScripts` directive serves the local copy first, with the CDN URL wired as `onerror` fallback.

Configure the asset mode in `config/livecharts.php`:

```php
'assets_mode' => env('LIVECHARTS_ASSETS_MODE', 'both'),
```

Valid values: `local`, `cdn`, `both`.

## Native Options

Pass any ApexCharts-native option via `options()`:

```php
Chart::make()
    ->engine('apexcharts')
    ->type('area')
    ->options([
        'stroke' => ['curve' => 'smooth'],
        'fill' => ['type' => 'gradient'],
    ]);
```

The PHP array maps directly to the JavaScript configuration object passed to `new ApexCharts(el, options)`.
