---
title: Dataset API
description: Reference for the Dataset value object.
---

`Dataset` is the value object representing a single series in a chart. It's used inside class-based chart definitions and returned implicitly by `Chart::dataset()`.

## Construction

```php
use Matheusmarnt\LiveCharts\Charts\Dataset;

$dataset = Dataset::make('Revenue')
    ->data([1200, 1900, 3000])
    ->color('#10B981');
```

## API

| Method | Signature | Description |
|---|---|---|
| `make()` | `static make(string $name): self` | Create a new dataset with a label. |
| `data()` | `data(array $values): self` | Set the numeric values. |
| `color()` | `color(string $hex): self` | Single colour for the whole series. |
| `colors()` | `colors(array $hexes): self` | Per-point colours (e.g. for bar charts). |
| `type()` | `type(string $type): self` | Override the type for mixed-type charts. |
| `meta()` | `meta(array $meta): self` | Arbitrary engine-specific options merged into the payload. |

## Mixed-type charts

`type()` lets you build combined visualisations like a bar + line overlay:

```php
public function datasets(): array
{
    return [
        Dataset::make('Sales')->type('bar')->data([100, 200, 150]),
        Dataset::make('Trend')->type('line')->data([110, 180, 160]),
    ];
}
```

Engine support varies — ApexCharts handles this via `chart.type = 'line'` plus per-series `type`. Chart.js handles it via per-dataset `type` on a base bar chart. Both are covered by the adapter.

## Engine-specific extras

Use `meta()` to pass keys the abstraction doesn't model directly:

```php
Dataset::make('Revenue')
    ->data([10, 20, 30])
    ->meta([
        'fill' => true,
        'tension' => 0.4,
    ]);
```

The adapter merges `meta` into the engine's dataset config object.
