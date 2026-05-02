<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Engines;

use Matheusmarnt\LiveCharts\Contracts\EngineAdapter;
use Matheusmarnt\LiveCharts\Exceptions\InvalidChartTypeException;
use Matheusmarnt\LiveCharts\Support\ChartPayload;

class ApexChartsAdapter implements EngineAdapter
{
    /** @var list<string> */
    public const SUPPORTED_TYPES = [
        'line', 'bar', 'area', 'pie', 'donut', 'radialBar', 'polarArea',
        'scatter', 'heatmap', 'radar', 'candlestick', 'boxPlot', 'rangeBar',
        'treemap', 'bubble',
    ];

    /**
     * @return array<string, mixed>
     */
    public function build(ChartPayload $payload): array
    {
        if (! in_array($payload->type, self::SUPPORTED_TYPES, true)) {
            throw InvalidChartTypeException::forEngine($payload->type, 'apexcharts', self::SUPPORTED_TYPES);
        }

        $isSingleSeries = in_array($payload->type, ['pie', 'donut', 'radialBar', 'polarArea']);

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
                ? ($payload->datasets[0]->data ?? [])
                : array_map(fn ($dataset) => [
                    'name' => $dataset->name,
                    'data' => $dataset->data,
                    'type' => $dataset->type,
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
            'xaxis' => $payload->xaxis,
            'yaxis' => $payload->yaxis,
            'grid' => $payload->grid,
            'stroke' => $payload->stroke,
            'markers' => $payload->markers,
            'dataLabels' => $payload->dataLabels,
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
        return '^5.10.6';
    }
}
