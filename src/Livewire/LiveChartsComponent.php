<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Livewire;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use Matheusmarnt\LiveCharts\Charts\Dataset;
use Matheusmarnt\LiveCharts\Contracts\ChartContract;
use Matheusmarnt\LiveCharts\Engines\EngineFactory;
use Matheusmarnt\LiveCharts\Support\AssetManager;
use Matheusmarnt\LiveCharts\Support\ChartPayload;

class LiveChartsComponent extends Component
{
    /** @var array<string, mixed> */
    public array $payload;

    public string $id;

    public string $class = '';

    public function mount(ChartContract $chart, ?string $id = null, string $class = ''): void
    {
        $this->payload = $chart->toPayload();
        $this->id = $id ?? 'chart-'.uniqid();
        $this->class = $class;
    }

    public function refresh(): void
    {
        $this->dispatch('livecharts:refreshed', id: $this->id);
    }

    public function render(): View
    {
        $payload = $this->payload;

        // Reconstruct datasets as objects for the adapter
        $datasets = array_map(function ($d) {
            return new Dataset(
                name: $d['name'],
                data: $d['data'],
                color: $d['color'] ?? null,
                type: $d['type'] ?? null,
                meta: $d['meta'] ?? []
            );
        }, $payload['datasets']);

        $adapter = EngineFactory::resolve($payload['engine']);

        $chartPayload = new ChartPayload(
            type: $payload['type'],
            engine: $payload['engine'],
            title: $payload['title'] ?? null,
            subtitle: $payload['subtitle'] ?? null,
            height: $payload['height'],
            width: $payload['width'],
            labels: $payload['labels'],
            datasets: $datasets,
            colors: $payload['colors'],
            theme: $payload['theme'],
            stacked: $payload['stacked'],
            sparkline: $payload['sparkline'],
            zoom: $payload['zoom'],
            toolbar: $payload['toolbar'],
            legend: $payload['legend'],
            tooltip: $payload['tooltip'],
            pollEvery: $payload['pollEvery'],
            onDataPointClick: $payload['onDataPointClick'] ?? null,
            onZoom: $payload['onZoom'] ?? null,
            onSelection: $payload['onSelection'] ?? null,
            onScroll: $payload['onScroll'] ?? null,
            broadcastOn: $payload['broadcastOn'] ?? null,
            broadcastAs: $payload['broadcastAs'] ?? null,
            options: $payload['options']
        );

        $options = $adapter->build($chartPayload);

        app(AssetManager::class)->registerEngine($payload['engine']);

        /** @var view-string $view */
        $view = 'livecharts::livewire.livecharts';

        return view($view, [
            'options' => $options,
            'adapter' => $adapter,
        ]);
    }
}
