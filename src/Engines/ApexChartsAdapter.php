<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Engines;

use Matheusmarnt\LiveCharts\Support\ChartPayload;

class ApexChartsAdapter extends BaseEngineAdapter
{
    /** @var list<string> */
    public const SUPPORTED_TYPES = [
        'line', 'bar', 'area', 'pie', 'donut', 'radialBar', 'polarArea',
        'scatter', 'heatmap', 'radar', 'candlestick', 'boxPlot', 'rangeBar',
        'treemap', 'bubble',
    ];

    public function engineName(): string
    {
        return 'apexcharts';
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

        $isSingleSeries = $this->isSingleSeries($payload->type);

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
            'labels' => $this->normalizeLabels($payload),
            'title' => [
                'text' => $payload->title,
            ],
            'subtitle' => [
                'text' => $payload->subtitle,
            ],
            'colors' => $this->normalizeColors($payload),
            'legend' => ['show' => $payload->legend],
            'tooltip' => ['enabled' => $payload->tooltip],
        ];

        foreach (['xaxis', 'yaxis', 'grid', 'stroke', 'markers', 'dataLabels'] as $key) {
            if (! empty($payload->{$key})) {
                $options[$key] = $payload->{$key};
            }
        }

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
