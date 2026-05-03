---
title: Chart API
description: Full reference for the Chart fluent builder.
---

The `Chart` class is the fluent builder behind `LiveCharts::line()`, `LiveCharts::bar()`, and friends. Every method returns `$this` for chaining.

## Type & engine

| Method | Signature | Description |
|---|---|---|
| `type()` | `type(string $type): self` | Set the chart type (`line`, `bar`, `pie`, …). |
| `engine()` | `engine(string $name): self` | Select the rendering engine. |

```php
LiveCharts::make()->type('bar')->engine('chartjs');
```

## Data

| Method | Signature | Description |
|---|---|---|
| `labels()` | `labels(array $labels): self` | X-axis labels (or pie slice labels). |
| `dataset()` | `dataset(string $name, array $data): self` | Append a single dataset. |
| `datasets()` | `datasets(array $datasets): self` | Replace all datasets at once. |
| `colors()` | `colors(array $hexes): self` | Override the colour palette. |

```php
LiveCharts::line()
    ->labels(['Jan', 'Feb', 'Mar'])
    ->dataset('Revenue', [10, 20, 30])
    ->colors(['#10B981']);
```

## Presentation

| Method | Signature | Description |
|---|---|---|
| `title()` | `title(string $text): self` | Chart title rendered by the engine. |
| `subtitle()` | `subtitle(string $text): self` | Optional subtitle. |
| `theme()` | `theme(string $mode): self` | `auto`, `light`, or `dark`. |
| `options()` | `options(array $options): self` | Engine-native options (deep-merged). |

## Reactivity

| Method | Signature | Description |
|---|---|---|
| `poll()` | `poll(): self` | Enable polling at the configured default interval. |
| `pollEvery()` | `pollEvery(int $seconds): self` | Enable polling at a custom interval. |
| `onDataPointClick()` | `onDataPointClick(string $event): self` | Dispatch a Livewire event on click. |

```php
LiveCharts::bar()
    ->pollEvery(15)
    ->onDataPointClick('drilldown');
```

## Type constants

`Chart::TYPES` exposes the canonical list of supported types. The `make:chart` command derives its `--type` option list from this constant — keep the two in sync if you fork.

## Class-based equivalents

When extending `Chart` directly, every fluent setter has a corresponding overridable method:

| Fluent | Class-based |
|---|---|
| `->type('bar')` | `protected string $type = 'bar';` |
| `->engine('chartjs')` | `protected string $engine = 'chartjs';` |
| `->labels([...])` | `public function labels(): array` |
| `->dataset(...)` | `public function datasets(): array` |
| `->options([...])` | `public function options(): array` |

See [Class-Based Charts](../../usage/class-based/) for the full pattern.
