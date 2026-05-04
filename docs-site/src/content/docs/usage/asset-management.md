---
title: Asset Management
description: Control how engine assets are loaded.
---

LiveCharts ships pre-built IIFE bundles for every supported engine and plugin. The `@liveChartsScripts` directive resolves these against the configured asset mode.

## Directive placement

Place `@liveChartsScripts` **before `@livewireScripts`** and before the closing `</body>` tag. The directive registers the `livecharts` Alpine data factory; Livewire's `@livewireScripts` bootstraps Alpine — so the factory **must** exist before Alpine initializes.

```blade
<body>
    <!-- chart components here -->
    @liveChartsScripts
    @livewireScripts
</body>
```

If your layout uses additional script directives (e.g. `@fluxScripts`), place `@liveChartsScripts` first:

```blade
@liveChartsScripts
@fluxScripts
@livewireScripts
```

:::danger[Wrong order causes `livecharts is not defined`]
Placing `@liveChartsScripts` after `@livewireScripts`, or omitting it entirely, causes Alpine to initialize without the `livecharts` factory. Every chart fails immediately with:

```
Alpine Expression Error: livecharts is not defined
Uncaught ReferenceError: livecharts is not defined
```

There is no recovery path once Alpine has started — the directive must precede `@livewireScripts`.
:::

**Using a Blade layout (`@extends`/`@section`)?** You can place the directive in the layout's `<head>`. The directive uses Blade's push/stack mechanism: chart components push their engine script tags when they render, and the stack is flushed wherever `@liveChartsScripts` is placed. Because `@extends` child views render before the layout resolves its stacks, `<head>` placement works correctly in this pattern.

```blade
<!-- layouts/app.blade.php -->
<head>
    @liveChartsScripts  {{-- works here with @extends/@section --}}
</head>
```

:::caution[Standalone views]
In views that do **not** extend a layout (e.g. a single Blade file), place `@liveChartsScripts` **after** the chart components. If placed in `<head>` of a standalone view, the stack is empty when the directive renders and no scripts are emitted.
:::

## Bundled artefacts

| File | Source |
|---|---|
| `resources/dist/livecharts.js` | Core runtime (Alpine integration). |
| `resources/dist/apexcharts.js` | ApexCharts ^5.10.6 IIFE bundle. |
| `resources/dist/chartjs.js` | Chart.js ^4.5.1 IIFE bundle (mirrors UMD named exports on `window.Chart`). |
| `resources/dist/chartjs-treemap.js` | `chartjs-chart-treemap` plugin. |
| `resources/dist/chartjs-matrix.js` | `chartjs-chart-matrix` plugin. |
| `resources/dist/chartjs-sankey.js` | `chartjs-chart-sankey` plugin. |
| `resources/dist/chartjs-financial.js` | `chartjs-chart-financial` plugin (candlestick, OHLC). |
| `resources/dist/luxon.js` | Luxon date library. |
| `resources/dist/chartjs-adapter-luxon.js` | Chart.js time-axis adapter. |

`livecharts:install` publishes all bundles to `public/vendor/livecharts/`.

## Modes

Configure via `LIVECHARTS_ASSETS_MODE`:

| Mode | Behaviour |
|---|---|
| `local` | Serve only the local copies. Fails closed if the file is missing. **Requires published assets.** |
| `cdn` | Serve only the public CDN URLs. No local files needed. |
| `both` | Serve local first, fall back to CDN via `<script>` `onerror` handler. **Default. Requires published assets.** |

:::caution[`local` and `both` require published assets]
Modes `local` and `both` (the default) serve files from `public/vendor/livecharts/js/`. If that directory is empty or missing, every engine script request returns 404 and charts fail with `ApexCharts is not defined` or `Chart is not defined`.

Publish assets with:

```bash
php artisan vendor:publish --tag=livecharts-assets --force
```

Or run `php artisan livecharts:install` which does this automatically.

Use `LIVECHARTS_ASSETS_MODE=cdn` to skip local files entirely.
:::

## Rebuilding bundles

If you fork the package or override `package.json` deps, rebuild from source:

```bash
npm install
npm run build
```

The Vite config is mode-driven. Each target (`livecharts`, `apexcharts`, `chartjs`) emits an IIFE bundle that externalizes its peer (e.g. `chart.js` for plugins, `luxon` for the adapter) so plugins resolve against the global `Chart` set up by the core shim.

## DOM load ordering

Plugin bundles import named exports from `chart.js` at runtime. The Chart.js shim in `resources/dist/chartjs.js` mirrors every named export onto `window.Chart` so plugin bundles resolve correctly. The `ChartJsAdapter` registers the engine asset **before** any plugin asset to guarantee load ordering.

You don't have to think about any of this — but if you're auditing the script tags rendered by `@liveChartsScripts`, that's why they appear in that order.

## CI verification

The `js-build.yml` workflow runs `npm ci && npm run build` and fails the PR if the committed `resources/dist` directory drifts out of sync with the source. This guarantees a fresh checkout always produces deterministic artefacts.
