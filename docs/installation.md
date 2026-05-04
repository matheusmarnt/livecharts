# Installation Guide

## Requirements

- PHP `^8.2`
- Laravel `^10.0 || ^11.0 || ^12.0 || ^13.0`
- Livewire `^3.0 || ^4.0`

> **Laravel 10 caveat:** the package's `composer.json` declares `^10.0` for runtime compatibility, but the test matrix only exercises Laravel 11/12/13 because `pestphp/pest-plugin-laravel ^3.0` requires Laravel 11+. Production use on Laravel 10 is supported but not CI-validated.

## 1. Install the package

```bash
composer require matheusmarnt/livecharts
```

The service provider is auto-registered via Laravel's package discovery. The `LiveCharts` facade is aliased automatically.

## 2. Run the installer

```bash
php artisan livecharts:install
```

The installer:

1. Publishes `config/livecharts.php`
2. Publishes the LiveCharts JS runtime **and** the pre-built engine bundles (`livecharts.js`, `apexcharts.js`, `chartjs.js`, plus Chart.js plugins for `treemap`, `matrix`, `sankey`, `financial`, `luxon`, `adapter-luxon`) to `public/vendor/livecharts/js`
3. Prompts whether to publish chart class stubs to `stubs/livecharts` (used by `make:chart`)

> **⚠️ Assets must be published for `local` and `both` modes**
>
> The default asset mode is `both` (local first, CDN fallback). The files in `public/vendor/livecharts/js/` **must exist** for this to work. If you skip `livecharts:install`, assets are missing after a fresh clone, or a deployment wipes `public/vendor/`, every chart will fail silently with `ApexCharts is not defined` or `Chart is not defined`.
>
> Re-publish assets on demand:
>
> ```bash
> php artisan vendor:publish --tag=livecharts-assets --force
> ```
>
> Verify the output:
>
> ```bash
> ls public/vendor/livecharts/js/
> # Expected: apexcharts.js  chartjs.js  livecharts.js  (+ plugin bundles)
> ```
>
> If you prefer no local files, set `LIVECHARTS_ASSETS_MODE=cdn` in `.env` — no publish step needed.

> **Environment-specific invocations**
>
> | Environment | Artisan invocation |
> |---|---|
> | `php artisan serve` (host) | `php artisan <command>` |
> | Laravel Sail | `./vendor/bin/sail artisan <command>` |
> | Docker Compose (non-Sail) | `docker compose exec app php artisan <command>` |

## 3. Wire the asset directive

LiveCharts ships in **`both` mode** by default (since v2.2.0): the locally-published engine bundles are served first, with the matching jsDelivr CDN URL wired as the `<script onerror>` fallback.

Place `@liveChartsScripts` **before `@livewireScripts`** and before the closing `</body>` tag. The LiveCharts JS runtime registers an Alpine component (`livecharts`) — it must load before Livewire's bundled Alpine starts:

```blade
<!DOCTYPE html>
<html>
<head>
    @livewireStyles
</head>
<body>
    {{ $slot }}
    @liveChartsScripts
    @livewireScripts
</body>
</html>
```

> **⚠️ Wrong order = `livecharts is not defined`**
>
> If `@liveChartsScripts` appears **after** `@livewireScripts`, or is missing entirely, Alpine initializes before the `livecharts` factory is registered. Every chart fails immediately:
>
> ```
> Alpine Expression Error: livecharts is not defined
> Uncaught ReferenceError: livecharts is not defined
> ```
>
> There is no runtime recovery — the directive must precede `@livewireScripts`.

If your layout uses additional script directives (e.g. `@fluxScripts`), place `@liveChartsScripts` first — the only hard constraint is that it comes before `@livewireScripts`:

```blade
@liveChartsScripts
@fluxScripts
@livewireScripts
```

> **Blade layouts with `@extends`/`@section`:** when your app uses a shared layout, you can place `@liveChartsScripts` in the layout's `<head>`. The directive uses Blade's push/stack mechanism — scripts registered by chart components in child sections are pushed to the stack before the layout renders, so they're available when `<head>` resolves.
>
> ```blade
> <!-- layouts/app.blade.php -->
> <head>
>     @liveChartsScripts  {{-- works here when using @extends/@section --}}
> </head>
> ```
>
> **Standalone views (not extending a layout):** always place `@liveChartsScripts` after the chart components in the body.

The directive emits only the engine bundles required by the engines actually rendered on the page.

> **Asset modes:** `both` (default — local-first with CDN fallback), `local` (no CDN), or `cdn` (no local). Switch via `LIVECHARTS_ASSETS_MODE` in `.env` or `config/livecharts.php`. See [Local assets](#local-assets) below.

## 4. Build a chart

### Fluent builder

```php
use Matheusmarnt\LiveCharts\Facades\LiveCharts;

$chart = LiveCharts::line()
    ->title('Monthly Revenue')
    ->labels(['Jan', 'Feb', 'Mar'])
    ->dataset('2026', [100, 200, 150])
    ->colors(['#3B82F6']);
```

### Class-based chart

Generate a class:

```bash
php artisan make:chart RevenueChart --type=bar
```

Edit `app/Charts/RevenueChart.php`:

```php
namespace App\Charts;

use Matheusmarnt\LiveCharts\Charts\Chart;
use Matheusmarnt\LiveCharts\Charts\Dataset;
use Matheusmarnt\LiveCharts\Enums\TwColor;

class RevenueChart extends Chart
{
    protected string $type = 'bar';

    public function __construct()
    {
        parent::__construct();

        $this
            ->title('Revenue')
            ->labels(['Jan', 'Feb', 'Mar'])
            ->datasets([
                Dataset::make('2026')
                    ->data([400, 300, 600])
                    ->backgroundColor(dark: TwColor::Emerald400, light: TwColor::Emerald600),
            ]);
    }
}
```

Available `--type` values: `line`, `bar`, `area`, `pie`, `donut`, `radar`, `scatter`, `bubble`, `heatmap`, `rangeBar`, `radialBar`, `polarArea`, `boxPlot`, `treemap`, `candlestick`, `matrix`, `sankey`.

> **Stubs:** if you accepted the stubs prompt during `livecharts:install`, the generator stub lives at `stubs/livecharts/chart.php.stub`. Edit that file to customize the boilerplate emitted by `make:chart`.

## 5. Render the chart

```blade
<livewire:livecharts :chart="$chart" />
```

For a class-based chart:

```blade
<livewire:livecharts :chart="new App\Charts\RevenueChart" />
```

The Livewire component handles mount, hydration, theme detection, and re-render. No JavaScript glue required.

## 6. Reactivity

### Polling

```php
$chart->poll(5000); // milliseconds
```

The component subscribes to `wire:poll="refresh"` and dispatches a browser event every tick:

```js
window.addEventListener('livecharts:refreshed', (e) => {
    // e.detail.id — the chart's DOM id
})
```

To hydrate fresh data, override `refresh()` on a parent Livewire component (re-fetch your data, then re-emit the chart payload).

### Click and zoom events

```php
$chart
    ->onDataPointClick('chart-clicked')
    ->onZoom('chart-zoomed')
    ->onSelection('chart-selected')
    ->onScroll('chart-scrolled');
```

```php
use Livewire\Attributes\On;

#[On('chart-clicked')]
public function handle(array $data): void
{
    // $data: ['seriesIndex' => 0, 'dataPointIndex' => 2, 'value' => 150, 'label' => 'Mar']
}
```

### Broadcasting

```php
$chart
    ->broadcastOn('private-charts.'.$user->id)
    ->broadcastAs('chart.updated');
```

Subscribe via Laravel Echo and the chart re-renders when the channel fires.

## 7. Engine selection

The default engine is set in `config/livecharts.php`:

```php
'engine' => env('LIVECHARTS_ENGINE', 'apexcharts'),
```

Override per chart:

```php
LiveCharts::line()->engine('chartjs')->labels(...)->dataset(...);
```

Register a custom engine adapter at runtime (typically in a service provider's `boot()`):

```php
use App\LiveCharts\Engines\HighchartsAdapter;
use Matheusmarnt\LiveCharts\Facades\LiveCharts;

LiveCharts::registerEngine('highcharts', HighchartsAdapter::class);
```

The custom class must implement `Matheusmarnt\LiveCharts\Contracts\EngineAdapter`. Built-in adapters: `apexcharts`, `chartjs`.

> **Internal API change (v2.0+):** `EngineFactory::register()` and `EngineFactory::resolve()` are now instance methods bound as a singleton on the container. Public callers should use the facade method above; advanced callers can resolve via `app(EngineFactory::class)`.

## Local assets

The package ships pre-built engine bundles via Vite (`vite build`) under `resources/dist`:

| Bundle | Source | Purpose |
|---|---|---|
| `livecharts.js` | `resources/js/livecharts.js` | Alpine component + Livewire hooks |
| `apexcharts.js` | npm `apexcharts@^5.10.6` | ApexCharts engine |
| `chartjs.js` | npm `chart.js@^4.5.1` | Chart.js engine (UMD-mirrored named exports) |
| `chartjs-treemap.js` | npm `chartjs-chart-treemap` | Treemap plugin |
| `chartjs-matrix.js` | npm `chartjs-chart-matrix` | Matrix plugin |
| `chartjs-sankey.js` | npm `chartjs-chart-sankey` | Sankey plugin |
| `chartjs-financial.js` | npm `chartjs-chart-financial` | Candlestick plugin |
| `chartjs-luxon.js` + `chartjs-adapter-luxon.js` | npm `luxon` + `chartjs-adapter-luxon` | Time-axis adapter for candlestick |

`livecharts:install` republishes all of these to `public/vendor/livecharts/js`. To re-publish on demand:

```bash
php artisan vendor:publish --tag=livecharts-assets --force
```

Switch modes:

```env
LIVECHARTS_ASSETS_MODE=both   # default — local-first, CDN fallback via <script onerror>
LIVECHARTS_ASSETS_MODE=local  # no CDN
LIVECHARTS_ASSETS_MODE=cdn    # no local copy required
```

The `@liveChartsScripts` directive emits the right `<script>` tags for the active mode and registers only the bundles required by the engines actually rendered on the page.

> **Building from source:** if you fork the package or modify `resources/js/livecharts.js`, run `npm ci && npm run build` to regenerate the bundles. The `js-build.yml` workflow fails CI when the committed `resources/dist/` is out of sync with the source.

## Customization

### Theme

```php
use Matheusmarnt\LiveCharts\Enums\ThemeMode;

$chart->theme(ThemeMode::Auto);   // enum form (recommended)
$chart->theme('auto');            // string form — still supported
```

Available modes: `ThemeMode::Auto`, `ThemeMode::Light`, `ThemeMode::Dark`.

`auto` follows Tailwind's `.dark` class on `<html>` (default) or the `prefers-color-scheme` media query:

```php
// config/livecharts.php
'theme' => [
    'mode'        => env('LIVECHARTS_THEME', 'auto'),
    'auto_detect' => env('LIVECHARTS_THEME_DETECT', 'class'), // 'class' | 'media'
],
```

Charts re-color **live** when the theme toggles — the JS observer patches `updateOptions` / `chart.update` directly, no Livewire roundtrip required.

### Color tokens

v2.6+ ships `TwColor`, a 289-case backed enum covering all Tailwind v4 color families. Every chart element accepts `dark:` / `light:` named-arg pairs:

```php
use Matheusmarnt\LiveCharts\Enums\TwColor;

$chart
    ->titleColor(dark: TwColor::Amber300, light: TwColor::Amber600)
    ->legendColor(dark: TwColor::Slate200, light: TwColor::Slate700)
    ->gridColor(dark: TwColor::Slate800, light: TwColor::Slate200)
    ->tooltipColor(dark: TwColor::White, light: TwColor::Slate900)
    ->backgroundColor(dark: TwColor::Slate900, light: TwColor::White);
```

Single-value form sets both themes to the same hex:

```php
$chart->titleColor(TwColor::Slate500);
$chart->titleColor('#6b7280'); // plain hex still works
```

Dataset-level background/border split:

```php
use Matheusmarnt\LiveCharts\Charts\Dataset;

Dataset::make('Revenue')
    ->data([100, 200, 150])
    ->backgroundColor(dark: TwColor::Emerald400, light: TwColor::Emerald600)
    ->borderColor(dark: TwColor::Emerald300, light: TwColor::Emerald700);
```

Palette presets auto-fill dataset colors:

```php
use Matheusmarnt\LiveCharts\Enums\TwPalette;

$chart->palette(TwPalette::Vibrant); // Vibrant | Muted | Monochrome | Pastel | Neon
```

Helper methods on `TwColor`:

```php
TwColor::Sky500->withAlpha(0.6);  // 'rgba(14,165,233,0.6)'
TwColor::Sky500->lighter(2);      // TwColor::Sky300
TwColor::Sky500->darker(1);       // TwColor::Sky600
TwColor::ramp('sky');             // [Sky50, Sky100, ..., Sky950]
```

### Layout primitives

All ApexCharts/Chart.js layout primitives are exposed as fluent methods that merge into the engine's options:

```php
$chart
    ->xaxis(['type' => 'datetime', 'tickAmount' => 6])
    ->yaxis(['min' => 0, 'forceNiceScale' => true])
    ->grid(['show' => false])
    ->stroke(['width' => 2, 'curve' => 'smooth'])
    ->markers(['size' => 4])
    ->dataLabels(['enabled' => false]);
```

### Raw engine options

Escape hatch for engine-specific options not covered by fluent methods:

```php
$chart->options([
    'chart' => ['animations' => ['enabled' => false]],
    'tooltip' => ['shared' => true, 'intersect' => false],
]);
```

### Publish views

```bash
php artisan vendor:publish --tag=livecharts-views
```

Edits to the published Blade files override the package defaults.

### Publish translations

```bash
php artisan vendor:publish --tag=livecharts-translations
```

Bundled locales: `en`, `pt_BR`, `es`.

### Publish stubs (after install)

```bash
php artisan vendor:publish --tag=livecharts-stubs
```

Outputs `stubs/livecharts/chart.php.stub`. `make:chart` reads the application stub if present, falling back to the package default.

## Updating

```bash
composer update matheusmarnt/livecharts
php artisan vendor:publish --tag=livecharts-config --force
php artisan vendor:publish --tag=livecharts-assets --force  # only if running in local asset mode
```

When upgrading **across a major version** (e.g. `1.x → 2.x`) review [CHANGELOG.md](../CHANGELOG.md) for breaking changes before running `--force` on the config.

## Preview Route

The package ships a debug route that renders one of every chart type for visual smoke-testing. Open it in your default browser with:

```bash
php artisan livecharts:preview            # opens the URL via the OS-native opener
php artisan livecharts:preview --no-open  # only prints the URL (CI / headless)
```

The command detects the host OS and spawns the appropriate opener (`open` on macOS, `xdg-open` on Linux/BSD, `cmd /c start` on Windows). On failure it falls back to printing the URL with the warning string from `livecharts.preview.open_failed`.

The route is registered automatically at `/livecharts/preview` under the `web` middleware group — make sure your local server (`php artisan serve`, Sail, or Docker) is running before invoking the command. Restrict or disable the route in production by overriding `LiveChartsServiceProvider::registerRoutes()` from a child provider, or by gating it via middleware in your application.
