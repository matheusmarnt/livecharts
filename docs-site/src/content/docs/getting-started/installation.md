---
title: Installation
description: How to install and configure LiveCharts.
---

## Composer

Install the package via composer:

```bash
composer require matheusmarnt/livecharts
```

## Install Assets

Run the installation command to publish the configuration and essential assets:

```bash
php artisan livecharts:install
```

This publishes `config/livecharts.php` and copies the pre-built JS bundles to `public/vendor/livecharts/js/`.

:::caution[Assets must be published for `local` and `both` modes]
The default asset mode is `both` (local first, CDN fallback). If you skip `livecharts:install`, the local JS files won't exist and every chart will throw `ApexCharts is not defined` / `Chart is not defined`. This also applies after a fresh clone or any deployment that wipes `public/vendor/`.

Run the asset publish step explicitly if needed:

```bash
php artisan vendor:publish --tag=livecharts-assets --force
```

Verify the files landed correctly:

```bash
ls public/vendor/livecharts/js/
# Expected: apexcharts.js  chartjs.js  livecharts.js  (+ plugin bundles)
```

If you prefer zero local files, set `LIVECHARTS_ASSETS_MODE=cdn` in `.env` — no publish step needed.
:::

## Register Scripts

Since **v2.7.7**, no layout changes are needed for most apps. LiveCharts uses the **`navigate` asset strategy** by default: each chart component emits its engine scripts via Livewire's `@assets` block, which Livewire loads once per page and re-ensures across `wire:navigate` SPA transitions.

```html
<body>
    <!-- chart components self-contain their scripts -->
    @livewireScripts
</body>
```

:::tip[`wire:navigate` (Livewire SPA) works out of the box]
The `navigate` strategy handles SPA navigation automatically. Charts initialize correctly whether the chart page is the SPA entry point or reached via a `wire:navigate` link.
:::

### Legacy strategy (`@liveChartsScripts`)

If you need the legacy push-stack behavior (e.g. non-Livewire contexts or manual placement), set `LIVECHARTS_ASSETS_STRATEGY=stack` in `.env` and add `@liveChartsScripts` **before `@livewireScripts`**:

```env
LIVECHARTS_ASSETS_STRATEGY=stack
```

```html
<body>
    @liveChartsScripts
    @livewireScripts
</body>
```

:::danger[`stack` strategy + `wire:navigate` = `livecharts is not defined`]
The `stack` strategy does **not** support `wire:navigate`. If the SPA entry page has no chart, navigating to a chart page throws `Uncaught ReferenceError: livecharts is not defined`. Use the `navigate` strategy (default) for SPA apps.
:::

:::tip[Using a shared layout with `@extends`? (stack strategy only)]
You can place `@liveChartsScripts` in the layout's `<head>`. The directive uses Blade's push/stack mechanism, so scripts pushed by chart components in child sections are captured before the layout renders.

```html
<!-- layouts/app.blade.php -->
<head>
    @liveChartsScripts
</head>
```
:::

See [Asset Management](/usage/asset-management) for the full strategy reference.
