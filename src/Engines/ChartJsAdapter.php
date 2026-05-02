<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Engines;

use Matheusmarnt\LiveCharts\Contracts\EngineAdapter;
use Matheusmarnt\LiveCharts\Support\ChartPayload;

class ChartJsAdapter implements EngineAdapter
{
    public function build(ChartPayload $payload): array
    {
        $isSingleSeries = in_array($payload->type, ['pie', 'donut', 'doughnut', 'polarArea']);

        return [
            'type' => $payload->type === 'donut' ? 'doughnut' : $payload->type,
            'data' => [
                'labels' => $payload->labels,
                'datasets' => array_map(fn ($dataset) => [
                    'label' => $dataset['name'],
                    'data' => $dataset['data'],
                    'backgroundColor' => $isSingleSeries ? $payload->colors : ($dataset['color'] ?? $payload->colors[0] ?? null),
                    'borderColor' => $isSingleSeries ? '#fff' : ($dataset['color'] ?? $payload->colors[0] ?? null),
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
