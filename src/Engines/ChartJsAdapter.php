<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Engines;

use Matheusmarnt\LiveCharts\Contracts\EngineAdapter;
use Matheusmarnt\LiveCharts\Support\ChartPayload;

class ChartJsAdapter implements EngineAdapter
{
    public function build(ChartPayload $payload): array
    {
        // Basic Chart.js implementation (to be expanded in v2)
        return [
            'type' => $payload->type,
            'data' => [
                'labels' => $payload->labels,
                'datasets' => array_map(fn($dataset) => [
                    'label' => $dataset['name'],
                    'data' => $dataset['data'],
                    'backgroundColor' => $dataset['color'],
                    'borderColor' => $dataset['color'],
                ], $payload->datasets),
            ],
            'options' => array_merge_recursive([
                'responsive' => true,
                'maintainAspectRatio' => false,
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
            ], $payload->options),
        ];
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
        return '^4.0';
    }
}
