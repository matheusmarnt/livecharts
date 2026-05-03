---
title: Engines Overview
description: How LiveCharts abstracts multiple chart libraries behind a single API.
---

LiveCharts ships with two first-party engines and an open adapter contract for registering your own.

| Engine | Slug | Bundle |
|---|---|---|
| ApexCharts | `apexcharts` | `^5.10.6` |
| Chart.js | `chartjs` | `^4.5.1` |

## How adapters work

Each engine implements the `EngineAdapter` contract:

```php
interface EngineAdapter
{
    public function engineName(): string;
    public function supportedTypes(): array;
    public function buildPayload(Chart $chart): array;
    public function registerRequiredAssets(): void;
}
```

The adapter is responsible for:

1. Declaring its identifier (`engineName`).
2. Listing the chart types it can render (`supportedTypes`).
3. Translating the abstract `Chart` definition into the engine's native config object (`buildPayload`).
4. Registering its `<script>` tags with the `AssetManager` (`registerRequiredAssets`).

Both first-party adapters extend `BaseEngineAdapter`, which provides shared helpers:

- `assertTypeSupported()` — throws `InvalidChartTypeException` on mismatch.
- `isSingleSeries()` — distinguishes single-dataset types like pie/donut.
- `normalizeLabels()` / `normalizeColors()` — aligns shapes between engines.

## Selecting an engine

Per chart:

```php
LiveCharts::line()->engine('chartjs');
```

Globally:

```php
// config/livecharts.php
'default_engine' => 'chartjs',
```

## Type compatibility

Not every type is supported by every engine. The `EngineAdapter::supportedTypes()` array is the authoritative source. If you call `engine('chartjs')->treemap()` without the matrix plugin loaded, LiveCharts throws `InvalidChartTypeException` at definition time — not at render time — so failures surface during testing.

See [ApexCharts](../apexcharts/) and [Chart.js](../chartjs/) for engine-specific support matrices.

## Custom engines

The `EngineFactory` is container-bound and can be extended at runtime. Register your own adapter and use it like any first-party engine:

```php
LiveCharts::registerEngine('echarts', \App\Charts\EChartsAdapter::class);
```

See [Custom Engines](../custom/) for the full walkthrough.
