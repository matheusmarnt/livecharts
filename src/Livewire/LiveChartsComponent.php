<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Component;
use Matheusmarnt\LiveCharts\Charts\Dataset;
use Matheusmarnt\LiveCharts\Contracts\ChartContract;
use Matheusmarnt\LiveCharts\Engines\EngineFactory;
use Matheusmarnt\LiveCharts\Support\AssetManager;
use Matheusmarnt\LiveCharts\Support\ChartPayload;
use Matheusmarnt\LiveCharts\Support\ColorValue;

class LiveChartsComponent extends Component
{
    /** @var array<string, mixed> */
    public array $payload;

    public string $id;

    public string $class = '';

    public function mount(ChartContract $chart, ?string $id = null, string $class = ''): void
    {
        $this->payload = $chart->toPayload();
        $this->id = $id ?? 'chart-'.Str::uuid()->toString();
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
            $dataset = Dataset::make($d['name'])
                ->data($d['data'] ?? []);

            if (! empty($d['background'])) {
                $bg = $d['background'];
                $dataset->backgroundColor(dark: $bg['dark'], light: $bg['light'] ?? $bg['dark']);
            } elseif (! empty($d['color'])) {
                $dataset->color($d['color']);
            }

            if (! empty($d['border'])) {
                $brd = $d['border'];
                $dataset->borderColor(dark: $brd['dark'], light: $brd['light'] ?? $brd['dark']);
            }

            if (! empty($d['type'])) {
                $dataset->type($d['type']);
            }

            return $dataset->meta($d['meta'] ?? []);
        }, $payload['datasets']);

        $adapter = app(EngineFactory::class)->resolve($payload['engine']);

        $cv = fn (?array $c): ?ColorValue => $c ? ColorValue::pair($c['dark'], $c['light']) : null;

        $chartPayload = new ChartPayload(
            type: $payload['type'],
            engine: $payload['engine'],
            title: $payload['title'] ?? null,
            subtitle: $payload['subtitle'] ?? null,
            height: $payload['height'],
            width: $payload['width'],
            labels: $payload['labels'],
            datasets: $datasets,
            colors: array_map(
                fn (array $c) => ColorValue::pair($c['dark'], $c['light']),
                $payload['colors']
            ),
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
            options: $payload['options'],
            titleColor: $cv($payload['titleColor'] ?? null),
            subtitleColor: $cv($payload['subtitleColor'] ?? null),
            legendColor: $cv($payload['legendColor'] ?? null),
            labelsColor: $cv($payload['labelsColor'] ?? null),
            tooltipColor: $cv($payload['tooltipColor'] ?? null),
            axisColor: $cv($payload['axisColor'] ?? null),
            gridColor: $cv($payload['gridColor'] ?? null),
            dataLabelsColor: $cv($payload['dataLabelsColor'] ?? null),
            backgroundColor: $cv($payload['backgroundColor'] ?? null),
        );

        $options = $adapter->build($chartPayload);

        app(AssetManager::class)->registerEngine($payload['engine']);
        app(AssetManager::class)->flushPendingPushes();

        /** @var view-string $view */
        $view = 'livecharts::livewire.livecharts';

        return view($view, [
            'options' => $options,
            'adapter' => $adapter,
        ]);
    }
}
