<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Engines;

use Matheusmarnt\LiveCharts\Contracts\EngineAdapter;
use Matheusmarnt\LiveCharts\Support\ChartPayload;

class ApexChartsAdapter implements EngineAdapter
{
    public function build(ChartPayload $payload): array
    {
        $isSingleSeries = in_array($payload->type, ['pie', 'donut', 'radialBar']);

        $options = [
            'chart' => [
                'type' => $payload->type,
                'height' => $payload->height,
                'width' => $payload->width,
                'sparkline' => ['enabled' => $payload->sparkline],
                'toolbar' => ['show' => $payload->toolbar],
                'zoom' => ['enabled' => $payload->zoom],
            ],
            'series' => $isSingleSeries
                ? ($payload->datasets[0]['data'] ?? [])
                : array_map(fn ($dataset) => [
                    'name' => $dataset['name'],
                    'data' => $dataset['data'],
                ], $payload->datasets),
            'labels' => $payload->labels,
            'title' => [
                'text' => $payload->title,
            ],
            'subtitle' => [
                'text' => $payload->subtitle,
            ],
            'colors' => $payload->colors,
            'legend' => ['show' => $payload->legend],
            'tooltip' => ['enabled' => $payload->tooltip],
        ];

        if ($payload->theme !== 'auto') {
            $options['theme'] = ['mode' => $payload->theme];
        }

        return array_merge_recursive($options, $payload->options);
    }

    public function jsConstructor(): string
    {
        return 'ApexCharts';
    }

    public function assetHandle(): string
    {
        return 'apexcharts';
    }

    public function version(): string
    {
        return '^3.41';
    }
}
