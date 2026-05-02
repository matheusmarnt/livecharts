<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Engines;

use Matheusmarnt\LiveCharts\Contracts\EngineAdapter;
use Matheusmarnt\LiveCharts\Support\AssetManager;
use Matheusmarnt\LiveCharts\Support\ChartPayload;

class ChartJsAdapter implements EngineAdapter
{
    public function build(ChartPayload $payload): array
    {
        $this->registerRequiredAssets($payload);

        $isSingleSeries = in_array($payload->type, ['pie', 'donut', 'doughnut', 'polarArea']);

        $chartType = $payload->type === 'donut' ? 'doughnut' : $payload->type;

        $options = [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => $this->buildScales($payload),
            'plugins' => [
                'title' => [
                    'display' => (bool) $payload->title,
                    'text' => $payload->title,
                ],
                'subtitle' => [
                    'display' => (bool) $payload->subtitle,
                    'text' => $payload->subtitle,
                ],
                'legend' => [
                    'display' => $payload->legend,
                ],
                'tooltip' => [
                    'enabled' => $payload->tooltip,
                ],
            ],
        ];

        return [
            'type' => $chartType,
            'data' => [
                'labels' => $payload->labels,
                'datasets' => array_map(fn ($dataset) => [
                    'label' => $dataset['name'],
                    'data' => $dataset['data'],
                    'backgroundColor' => $isSingleSeries ? $payload->colors : ($dataset['color'] ?? $payload->colors[0] ?? null),
                    'borderColor' => $isSingleSeries ? '#fff' : ($dataset['color'] ?? $payload->colors[0] ?? null),
                    'fill' => $payload->type === 'area',
                    'tension' => 0.4, // Default smooth lines
                ], $payload->datasets),
            ],
            'options' => array_merge_recursive($options, $payload->options),
        ];
    }

    protected function buildScales(ChartPayload $payload): array
    {
        if (in_array($payload->type, ['pie', 'donut', 'doughnut', 'polarArea', 'radar', 'sankey', 'treemap', 'matrix'])) {
            return [];
        }

        return [
            'y' => [
                'beginAtZero' => true,
                'stacked' => $payload->stacked,
            ],
            'x' => [
                'stacked' => $payload->stacked,
            ],
        ];
    }

    protected function registerRequiredAssets(ChartPayload $payload): void
    {
        $assetManager = app(AssetManager::class);

        if ($payload->type === 'treemap') {
            $assetManager->registerAsset('chartjs-treemap');
        }

        if ($payload->type === 'matrix') {
            $assetManager->registerAsset('chartjs-matrix');
        }

        if ($payload->type === 'sankey') {
            $assetManager->registerAsset('chartjs-sankey');
        }

        if ($payload->type === 'candlestick') {
            $assetManager->registerAsset('chartjs-luxon');
            $assetManager->registerAsset('chartjs-adapter-luxon');
            $assetManager->registerAsset('chartjs-financial');
        }
    }

    public function jsConstructor(): string
    {
        return 'Chart';
    }

    public function assetHandle(): string
    {
        return 'chartjs';
    }

    public function version(): string
    {
        return '^4.5.1';
    }
}
