---
title: Exceptions
description: Every exception LiveCharts throws and how to handle it.
---

LiveCharts throws four typed exceptions. All extend a common `LiveChartsException` base and produce translated messages via the `livecharts.errors.*` translation keys.

| Exception | When |
|---|---|
| `UnknownEngineException` | The slug passed to `engine()` is not registered. |
| `InvalidChartTypeException` | The selected engine doesn't support the chart type. |
| `EmptyDatasetException` | A chart was rendered with zero datasets. |
| `DataShapeMismatchException` | Dataset shape doesn't match the engine's expectations (e.g. nested array for a single-series chart). |

All four are PHP `RuntimeException` subclasses — handle them with regular `try`/`catch` or surface them via Laravel's exception handler.

## UnknownEngineException

Thrown by `EngineFactory::resolve()` when the slug isn't in the registry.

```php
try {
    LiveCharts::engine('echarts')->line();
} catch (UnknownEngineException $e) {
    // Register the engine first via LiveCharts::registerEngine()
}
```

## InvalidChartTypeException

Thrown at definition time, before render, when the type isn't in `EngineAdapter::supportedTypes()`.

```php
try {
    LiveCharts::engine('chartjs')->treemap(); // requires the matrix plugin
} catch (InvalidChartTypeException $e) {
    // Either pick a different engine, or load the missing plugin
}
```

## EmptyDatasetException

Thrown by the adapter's `buildPayload()` when the chart has no datasets. Catch this for graceful UX:

```php
try {
    $payload = $adapter->buildPayload($chart);
} catch (EmptyDatasetException $e) {
    return view('charts.empty-state');
}
```

## DataShapeMismatchException

Thrown when a single-series type (pie, donut, polarArea, radialBar) receives multiple datasets, or when a multi-series type receives malformed input.

```php
LiveCharts::pie()
    ->dataset('A', [1, 2, 3])
    ->dataset('B', [4, 5, 6]); // throws — pie expects one series
```

## Type validation map (Appendix A)

The full type-per-engine matrix is encoded in the adapters and asserted by Pest arch tests. Today:

| Type | ApexCharts | Chart.js |
|---|---|---|
| `line` | ✅ | ✅ |
| `area` | ✅ | ✅ (line + `fill: true`) |
| `bar` | ✅ | ✅ |
| `column` | ✅ | ✅ (bar variant) |
| `pie` | ✅ | ✅ |
| `donut` | ✅ | ✅ (doughnut) |
| `radar` | ✅ | ✅ |
| `polarArea` | ✅ | ✅ |
| `radialBar` | ✅ | ❌ |
| `scatter` | ✅ | ✅ |
| `bubble` | ✅ | ✅ |
| `heatmap` | ✅ | ❌ (without plugin) |
| `treemap` | ✅ | ✅ (via plugin) |
| `candlestick` | ✅ | ✅ (via financial plugin) |
| `rangeBar` | ✅ | ❌ |
