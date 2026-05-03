---
title: Chart.js
description: Use Chart.js as the rendering engine.
---

Chart.js is the alternate engine. It supports line, bar, pie, doughnut, radar, polar area, scatter, and bubble charts. Specialized types are available via plugin bundles: treemap, matrix, sankey, financial (candlestick/OHLC), and time-axis charts via the Luxon adapter.

## Selecting the Engine

```php
use Matheusmarnt\LiveCharts\Chart;

return Chart::make()
    ->engine('chartjs')
    ->type('bar')
    ->labels(['Q1', 'Q2', 'Q3', 'Q4'])
    ->dataset('Sales', [100, 200, 150, 300]);
```

## Bundled Assets

Chart.js core (`^4.5.1`) ships at `resources/dist/chartjs.js`. Plugin bundles ship alongside:

| Plugin | File |
|---|---|
| `chartjs-chart-treemap` | `resources/dist/chartjs-treemap.js` |
| `chartjs-chart-matrix` | `resources/dist/chartjs-matrix.js` |
| `chartjs-chart-sankey` | `resources/dist/chartjs-sankey.js` |
| `chartjs-chart-financial` | `resources/dist/chartjs-financial.js` |
| `luxon` | `resources/dist/luxon.js` |
| `chartjs-adapter-luxon` | `resources/dist/chartjs-adapter-luxon.js` |

The `ChartJsAdapter` registers the engine asset before any plugin asset to guarantee DOM load ordering — plugins resolve their named imports against the global `Chart` exposed by the core shim.

## Native Options

Pass any Chart.js-native config via `options()`:

```php
Chart::make()
    ->engine('chartjs')
    ->type('line')
    ->options([
        'scales' => [
            'y' => ['beginAtZero' => true],
        ],
        'plugins' => [
            'legend' => ['position' => 'bottom'],
        ],
    ]);
```

The PHP array maps directly to the configuration object passed to `new Chart(ctx, config)`.
