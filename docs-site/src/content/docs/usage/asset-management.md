---
title: Asset Management
description: Control how engine assets are loaded.
---

LiveCharts ships pre-built IIFE bundles for every supported engine and plugin. Two loading strategies are available — choose based on whether your app uses `wire:navigate` (Livewire SPA navigation).

## Loading strategy

Configure via `LIVECHARTS_ASSETS_STRATEGY` (or `config('livecharts.assets.strategy')`):

| Strategy | Behaviour | Default |
|---|---|---|
| `navigate` | Assets emitted via Livewire `@assets` block inside the chart component. Livewire loads them once and re-ensures them across `wire:navigate` transitions. `@liveChartsScripts` not required. | ✅ Yes |
| `stack` | Assets pushed to the `livecharts-scripts` Blade stack when a chart renders. Requires `@liveChartsScripts` in the layout. Legacy behavior. | No |

```env
# .env — switch to legacy stack strategy
LIVECHARTS_ASSETS_STRATEGY=stack
```

### `navigate` strategy (default)

No layout changes needed. Charts self-contain their script dependencies via Livewire's `@assets` block. Livewire deduplicates identical blocks, so multiple charts of the same engine load the scripts once per page. This strategy works correctly across `wire:navigate` transitions without any configuration.

```blade
<!-- No @liveChartsScripts needed — engine scripts load automatically -->
<livewire:line-chart :chart="$chart" />
```

### `stack` strategy (legacy)

Place `@liveChartsScripts` **before `@livewireScripts`** in your layout:

```blade
<body>
    <!-- chart components here -->
    @liveChartsScripts
    @livewireScripts
</body>
```

:::danger[`stack` strategy + `wire:navigate`]
The `stack` strategy does **not** support `wire:navigate`. If the SPA entry page has no chart, the engine scripts are never pushed to the stack, and navigating to a chart page throws:

```
Uncaught ReferenceError: livecharts is not defined
```

Use the `navigate` strategy (default) for SPA apps.
:::

**Using a Blade layout (`@extends`/`@section`)?** You can place the directive in the layout's `<head>`. The push/stack mechanism works because `@extends` child views render before the layout resolves stacks.

```blade
<!-- layouts/app.blade.php -->
<head>
    @liveChartsScripts  {{-- works here with @extends/@section --}}
</head>
```

:::caution[Standalone views — stack strategy only]
In views that do not extend a layout, place `@liveChartsScripts` **after** the chart components. If placed in `<head>` of a standalone view, the stack is empty when the directive renders.
:::

## SPA navigation (`wire:navigate`)

The `navigate` strategy handles `wire:navigate` automatically. Charts initialize correctly whether the chart page is the SPA entry point or reached via navigation.

If you must use the `stack` strategy, you are responsible for ensuring assets are present on every page that may navigate to a chart page (e.g. by always including `@liveChartsScripts` in your base layout).

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

Or run `php artisan livecharts:install` — the installer publishes all bundles automatically on first run.

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
