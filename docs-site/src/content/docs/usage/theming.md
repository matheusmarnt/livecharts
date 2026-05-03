---
title: Theming
description: Configure light, dark, and auto themes with live toggle support.
---

LiveCharts exposes three theme modes and a live JS observer that re-colors charts when your app switches between dark and light mode — no Livewire roundtrip required.

| Mode | Behaviour |
|---|---|
| `auto` | Follows the host app's `<html class="dark">` (default strategy). |
| `light` | Always uses the light palette. |
| `dark` | Always uses the dark palette. |

## Global default

```php
// config/livecharts.php
return [
    'theme' => [
        'mode'        => env('LIVECHARTS_THEME', 'auto'),
        'auto_detect' => env('LIVECHARTS_THEME_DETECT', 'class'), // 'class' | 'media'
    ],
];
```

`auto_detect` controls how the JS observer detects theme changes:

| Strategy | Trigger |
|---|---|
| `class` | Watches `<html class="dark">` — works with Tailwind dark mode |
| `media` | Watches `prefers-color-scheme: dark` OS media query |

## Per-chart override

```php
use Matheusmarnt\LiveCharts\Enums\ThemeMode;

LiveCharts::line()->theme(ThemeMode::Dark);
LiveCharts::line()->theme('dark'); // string form still works
```

## Theme-aware color customization

The recommended way to style charts for both themes is the fluent color API using `TwColor` enums. Charts re-color instantly when the theme toggles — no page reload, no Livewire request.

```php
use Matheusmarnt\LiveCharts\Enums\TwColor;

LiveCharts::line()
    ->titleColor(dark: TwColor::Amber300, light: TwColor::Amber600)
    ->legendColor(dark: TwColor::Slate200, light: TwColor::Slate700)
    ->gridColor(dark: TwColor::Slate800, light: TwColor::Slate200)
    ->tooltipColor(dark: TwColor::White, light: TwColor::Slate900)
    ->backgroundColor(dark: TwColor::Slate900, light: TwColor::White)
    ->labelsColor(dark: TwColor::Slate400, light: TwColor::Slate600);
```

See the [Color Customization](/usage/customization) page for the full API reference.

## Live toggle behavior

When `auto_detect` is `class` (default), LiveCharts watches for changes to `document.documentElement.classList`. Toggle your app's dark mode and all charts update instantly:

```js
// Example toggle — charts re-color with no round-trip
document.documentElement.classList.toggle('dark');
```

This works with Tailwind's class strategy, Alpine.js dark mode plugins, and any UI framework that drives dark mode via the `.dark` class.
