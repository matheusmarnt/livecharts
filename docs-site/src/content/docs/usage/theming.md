---
title: Theming
description: Configure light, dark, and auto themes.
---

LiveCharts exposes three theme modes:

| Mode | Behaviour |
|---|---|
| `auto` | Follows the user's `prefers-color-scheme` system setting. |
| `light` | Always uses the light palette. |
| `dark` | Always uses the dark palette. |

:::caution
Theming is currently **config-only**. The JavaScript consumption path for engine-specific theme tokens is on the roadmap. Today, `theme` controls colour selection inside LiveCharts but you may need to pass engine-native options for full styling control.
:::

## Global default

```php
// config/livecharts.php
return [
    'theme' => env('LIVECHARTS_THEME', 'auto'),
    // ...
];
```

## Per-chart override

```php
LiveCharts::line()->theme('dark');
```

## Engine-native styling (recommended today)

Until the JS theming hook lands, override engine options directly:

### ApexCharts

```php
LiveCharts::line()->options([
    'chart' => ['foreColor' => '#cbd5e1'],
    'grid' => ['borderColor' => '#334155'],
    'tooltip' => ['theme' => 'dark'],
]);
```

### Chart.js

```php
LiveCharts::engine('chartjs')->bar()->options([
    'plugins' => [
        'legend' => ['labels' => ['color' => '#cbd5e1']],
    ],
    'scales' => [
        'x' => ['grid' => ['color' => '#334155']],
        'y' => ['grid' => ['color' => '#334155']],
    ],
]);
```

## Mixing with Tailwind

If you're driving the rest of the UI with Tailwind, derive your chart palette from your CSS variables:

```php
LiveCharts::line()
    ->colors(['rgb(var(--color-primary))', 'rgb(var(--color-secondary))']);
```

Just remember the values are emitted as JSON to the engine — they need to be valid CSS colour strings the browser can resolve at render time.
