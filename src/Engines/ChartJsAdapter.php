<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Engines;

use Matheusmarnt\LiveCharts\Support\AssetManager;
use Matheusmarnt\LiveCharts\Support\ChartPayload;

class ChartJsAdapter extends BaseEngineAdapter
{
    /** @var list<string> */
    public const SUPPORTED_TYPES = [
        'line', 'bar', 'area', 'pie', 'donut', 'doughnut', 'polarArea',
        'scatter', 'radar', 'bubble', 'candlestick', 'matrix', 'sankey', 'treemap',
    ];

    public function engineName(): string
    {
        return 'chartjs';
    }

    /**
     * @return list<string>
     */
    public function supportedTypes(): array
    {
        return self::SUPPORTED_TYPES;
    }

    /**
     * @return array<string, mixed>
     */
    public function build(ChartPayload $payload): array
    {
        $this->assertTypeSupported($payload);

        $this->registerRequiredAssets($payload);

        $isSingleSeries = $this->isSingleSeries($payload->type);
        $colors = $this->normalizeColors($payload);

        $chartType = $payload->type === 'donut' ? 'doughnut' : $payload->type;

        $options = [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => array_merge_recursive($this->buildScales($payload), [
                'x' => $payload->xaxis,
                'y' => $payload->yaxis,
            ]),
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
                'datalabels' => $payload->dataLabels,
            ],
        ];

        return [
            'type' => $chartType,
            'data' => [
                'labels' => $this->normalizeLabels($payload),
                'datasets' => array_map(fn ($dataset) => [
                    'type' => $dataset->type,
                    'label' => $dataset->name,
                    'data' => $dataset->data,
                    'backgroundColor' => $isSingleSeries ? $colors : ($dataset->color ?? $colors[0] ?? null),
                    'borderColor' => $isSingleSeries ? '#fff' : ($dataset->color ?? $colors[0] ?? null),
                    'fill' => $payload->type === 'area' || $dataset->type === 'area',
                    'tension' => ($payload->stroke['curve'] ?? null) === 'smooth' ? 0.4 : 0, // Basic mapping
                    ...$payload->stroke, // Merge remaining stroke options
                    'point' => $payload->markers, // Basic mapping
                ], $payload->datasets),
            ],
            'options' => array_merge_recursive($options, $payload->options),
        ];
    }

    /**
     * @return array<string, mixed>
     */
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
