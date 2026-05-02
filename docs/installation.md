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
2. Copies `resources/js/livecharts.js` to `resources/js/livecharts.js` in your application
3. Prompts whether to publish chart class stubs to `stubs/livecharts` (used by `make:chart`)

> **Environment-specific invocations**
>
> | Environment | Artisan invocation |
> |---|---|
> | `php artisan serve` (host) | `php artisan <command>` |
> | Laravel Sail | `./vendor/bin/sail artisan <command>` |
> | Docker Compose (non-Sail) | `docker compose exec app php artisan <command>` |

## 3. Wire the asset directive

LiveCharts ships in **CDN mode** by default — engine scripts (ApexCharts, Chart.js, plugins) are loaded from jsDelivr on demand. Place the directive once in your layout `<head>`:

```blade
<!DOCTYPE html>
<html>
<head>
    @liveChartsScripts
    @livewireStyles
</head>
<body>
    {{ $slot }}
    @livewireScripts
</body>
</html>
```

The directive emits the loader script and only fetches the engine bundles for engines actually used on the page.

> **Asset modes:** `cdn` (default), `local`, or `both` (fallback). Switch via `LIVECHARTS_ASSETS_MODE` in `.env` or `config/livecharts.php`. See [Local assets](#local-assets) below.

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
                Dataset::make('2026')->data([400, 300, 600])->color('#10B981'),
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

To self-host the engine bundles instead of using the jsDelivr CDN:

```bash
php artisan vendor:publish --tag=livecharts-assets
```

This copies the engine JS files to `public/vendor/livecharts/js`. Then:

```env
LIVECHARTS_ASSETS_MODE=local
```

Or `both` for CDN with local fallback. The `@liveChartsScripts` directive emits the right `<script>` tags for the active mode.

## Customization

### Theme

```php
$chart->theme('auto'); // 'auto' | 'light' | 'dark'
```

`auto` follows Tailwind's `.dark` class on `<html>` (default) or the `prefers-color-scheme` media query — switch via `theme.auto_detect` in `config/livecharts.php`.

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

The package ships a debug route that renders one of every chart type for visual smoke-testing. Print the URL with:

```bash
php artisan livecharts:preview
```

The route is registered automatically at `/livecharts/preview` under the `web` middleware group — point your browser at it after starting `php artisan serve`. Restrict or disable the route in production by overriding `LiveChartsServiceProvider::registerRoutes()` from a child provider, or by gating it via middleware in your application.
