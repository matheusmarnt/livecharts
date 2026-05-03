---
title: Asset Management
description: Control how engine assets are loaded.
---

LiveCharts ships pre-built IIFE bundles for every supported engine and plugin. The `@liveChartsScripts` directive resolves these against the configured asset mode.

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
| `local` | Serve only the local copies. Fails closed if the file is missing. |
| `cdn` | Serve only the public CDN URLs. No local files needed. |
| `both` | Serve local first, fall back to CDN via `<script>` `onerror` handler. **Default.** |

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
