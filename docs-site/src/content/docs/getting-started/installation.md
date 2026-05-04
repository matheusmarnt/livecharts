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

Add `@liveChartsScripts` **before `@livewireScripts`**. The LiveCharts JS runtime registers an Alpine component and must load before Livewire's bundled Alpine initializes:

```html
<body>
    <!-- your chart components here -->
    @liveChartsScripts
    @livewireScripts
</body>
```

:::danger[Wrong order = `livecharts is not defined`]
If `@liveChartsScripts` appears **after** `@livewireScripts` (or is missing entirely), Alpine will initialize before the `livecharts` data factory is registered. Every chart will fail with:

```
Alpine Expression Error: livecharts is not defined
```

The fix is always the same: move `@liveChartsScripts` above `@livewireScripts`.
:::

:::tip[Using Flux UI or other script directives?]
If your layout uses `@fluxScripts` or similar third-party directives, place `@liveChartsScripts` first — it only matters that it comes before `@livewireScripts`, which is what bootstraps Alpine:

```html
@liveChartsScripts
@fluxScripts
@livewireScripts
```
:::

:::tip[Using a shared layout with `@extends`?]
When your app uses Blade layouts (`@extends` / `@section`), you can place `@liveChartsScripts` in the layout's `<head>`. The directive uses Blade's push/stack mechanism, so scripts pushed by chart components in child sections are captured before the layout renders.

```html
<!-- layouts/app.blade.php -->
<head>
    @liveChartsScripts
</head>
```
:::

This directive injects the required charting engine scripts (CDN or local) and the Alpine.js integration bundle.
