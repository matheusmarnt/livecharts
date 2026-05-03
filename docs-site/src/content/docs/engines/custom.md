---
title: Custom Engines
description: Register your own rendering engine via the adapter contract.
---

LiveCharts is engine-agnostic by design. The first-party ApexCharts and Chart.js adapters are just two implementations of the public `EngineAdapter` contract. You can ship your own adapter for ECharts, Highcharts, Plotly, or any internal library.

## 1. Implement the adapter

Extend `BaseEngineAdapter` to inherit shared helpers:

```php
namespace App\Charts;

use Matheusmarnt\LiveCharts\Charts\Chart;
use Matheusmarnt\LiveCharts\Engines\BaseEngineAdapter;
use Matheusmarnt\LiveCharts\Support\AssetManager;

class EChartsAdapter extends BaseEngineAdapter
{
    public function engineName(): string
    {
        return 'echarts';
    }

    public function supportedTypes(): array
    {
        return ['line', 'bar', 'pie', 'scatter', 'sunburst', 'sankey'];
    }

    public function buildPayload(Chart $chart): array
    {
        $this->assertTypeSupported($chart->type());

        return [
            'series' => $this->normalizeSeries($chart),
            'xAxis'  => ['type' => 'category', 'data' => $chart->labels()],
            'yAxis'  => ['type' => 'value'],
        ];
    }

    public function registerRequiredAssets(): void
    {
        app(AssetManager::class)->register('echarts', [
            'local' => '/vendor/livecharts/echarts.js',
            'cdn'   => 'https://cdn.jsdelivr.net/npm/echarts@5/dist/echarts.min.js',
        ]);
    }
}
```

## 2. Register at runtime

Call the static facade method anywhere the container is booted — typically a service provider:

```php
namespace App\Providers;

use App\Charts\EChartsAdapter;
use Illuminate\Support\ServiceProvider;
use Matheusmarnt\LiveCharts\Facades\LiveCharts;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        LiveCharts::registerEngine('echarts', EChartsAdapter::class);
    }
}
```

Behind the scenes, `EngineFactory` is container-bound as a singleton. Registering an engine adds it to the in-memory registry — there's no static state to worry about.

## 3. Wire the JS runtime

Implement the rendering callback on the JavaScript side. LiveCharts dispatches a `livecharts:render` event with the engine name and payload — your handler builds the chart instance and stores it under `window.LiveCharts[id]` so the package can reach it for cleanup.

A minimal ECharts handler:

```js
window.addEventListener('livecharts:render', (event) => {
    const { id, engine, payload, element } = event.detail;
    if (engine !== 'echarts') return;

    const instance = echarts.init(element);
    instance.setOption(payload);
    window.LiveCharts[id] = instance;
});
```

## 4. Use it like any other engine

```php
LiveCharts::engine('echarts')
    ->type('sankey')
    ->dataset('Flow', $sankeyData);
```

## Testing tips

- Add a feature test that resolves your adapter via `app(EngineFactory::class)->resolve('echarts')` to assert it's registered.
- Use Pest arch rules to forbid direct `new EChartsAdapter()` calls outside the service provider.
- If you ship the adapter as a downstream package, expose it via the same `livecharts.engines` config array so end users can override CDN/local URLs.
