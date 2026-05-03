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

        $chartBlock = [
            'type' => $payload->type,
            'height' => $payload->height,
            'width' => $payload->width,
            'sparkline' => ['enabled' => $payload->sparkline],
            'toolbar' => ['show' => $payload->toolbar],
            'zoom' => ['enabled' => $payload->zoom],
        ];

        if ($payload->backgroundColor !== null) {
            $chartBlock['background'] = $this->pickColor($payload->backgroundColor);
            $chartBlock = array_merge($chartBlock, $this->themedSidecar('backgroundColor', $payload->backgroundColor));
        }

        $options = [
            'chart' => $chartBlock,
            'series' => $isSingleSeries
                ? ($payload->datasets[0]->data ?? [])
                : array_map(fn ($dataset) => [
                    'name' => $dataset->name,
                    'data' => $dataset->data,
                    'type' => $dataset->type,
                ], $payload->datasets),
            'labels' => $this->normalizeLabels($payload),
            'title' => $this->buildTitleOptions($payload),
            'subtitle' => $this->buildSubtitleOptions($payload),
            'colors' => $this->normalizeColors($payload),
            'legend' => $this->buildLegendOptions($payload),
            'tooltip' => $this->buildTooltipOptions($payload),
        ];

        foreach (['xaxis', 'yaxis', 'grid', 'stroke', 'markers', 'dataLabels'] as $key) {
            if (! empty($payload->{$key})) {
                $options[$key] = $payload->{$key};
            }
        }

        // Axis label colors
        if ($payload->labelsColor !== null) {
            $color = $this->pickColor($payload->labelsColor);
            $options['xaxis'] = array_merge_recursive($options['xaxis'] ?? [], [
                'labels' => ['style' => ['colors' => [$color]]],
            ]);
            $options['yaxis'] = array_merge_recursive($options['yaxis'] ?? [], [
                'labels' => ['style' => ['colors' => [$color]]],
            ]);
            $options['xaxis'] = array_merge(
                $options['xaxis'],
                $this->themedSidecar('labelsColor', $payload->labelsColor),
            );
        }

        // Axis border + tick colors
        if ($payload->axisColor !== null) {
            $color = $this->pickColor($payload->axisColor);
            $options['xaxis'] = array_merge_recursive($options['xaxis'] ?? [], [
                'axisBorder' => ['color' => $color],
                'axisTicks' => ['color' => $color],
            ]);
            $options['yaxis'] = array_merge_recursive($options['yaxis'] ?? [], [
                'axisBorder' => ['color' => $color],
            ]);
            $options['xaxis'] = array_merge(
                $options['xaxis'],
                $this->themedSidecar('axisColor', $payload->axisColor),
            );
        }

        // Grid color
        if ($payload->gridColor !== null) {
            $options['grid'] = array_merge_recursive($options['grid'] ?? [], [
                'borderColor' => $this->pickColor($payload->gridColor),
            ]);
            $options['grid'] = array_merge(
                $options['grid'],
                $this->themedSidecar('gridColor', $payload->gridColor),
            );
        }

        // Data labels color
        if ($payload->dataLabelsColor !== null) {
            $options['dataLabels'] = array_merge_recursive($options['dataLabels'] ?? [], [
                'style' => ['colors' => [$this->pickColor($payload->dataLabelsColor)]],
            ]);
            $options['dataLabels'] = array_merge(
                $options['dataLabels'],
                $this->themedSidecar('dataLabelsColor', $payload->dataLabelsColor),
            );
        }

        if ($payload->theme !== 'auto') {
            $options['theme'] = ['mode' => $payload->theme];
        }

        return array_merge_recursive($options, $payload->options);
    }

    /**
     * @return array<string, mixed>
     */
    private function buildTitleOptions(ChartPayload $payload): array
    {
        $title = ['text' => $payload->title];

        if ($payload->titleColor !== null || $payload->titleFont !== null) {
            $style = [];

            if ($payload->titleColor !== null) {
                $style['color'] = $this->pickColor($payload->titleColor);
            }

            if ($payload->titleFont !== null) {
                if (isset($payload->titleFont['size'])) {
                    $style['fontSize'] = $payload->titleFont['size'].'px';
                }
                if (isset($payload->titleFont['weight'])) {
                    $style['fontWeight'] = $payload->titleFont['weight'];
                }
                if (isset($payload->titleFont['family'])) {
                    $style['fontFamily'] = $payload->titleFont['family'];
                }
            }

            $title['style'] = $style;
        }

        if ($payload->titleColor !== null) {
            $title = array_merge($title, $this->themedSidecar('titleColor', $payload->titleColor));
        }

        return $title;
    }

    /**
     * @return array<string, mixed>
     */
    private function buildSubtitleOptions(ChartPayload $payload): array
    {
        $subtitle = ['text' => $payload->subtitle];

        if ($payload->subtitleColor !== null) {
            $subtitle['style'] = ['color' => $this->pickColor($payload->subtitleColor)];
            $subtitle = array_merge($subtitle, $this->themedSidecar('subtitleColor', $payload->subtitleColor));
        }

        return $subtitle;
    }

    /**
     * @return array<string, mixed>
     */
    private function buildLegendOptions(ChartPayload $payload): array
    {
        $legend = ['show' => $payload->legend];

        if ($payload->legendColor !== null) {
            $legend['labels'] = ['colors' => $this->pickColor($payload->legendColor)];
            $legend = array_merge($legend, $this->themedSidecar('legendColor', $payload->legendColor));
        }

        if ($payload->legendFont !== null) {
            if (isset($payload->legendFont['size'])) {
                $legend['fontSize'] = (string) $payload->legendFont['size'];
            }
            if (isset($payload->legendFont['family'])) {
                $legend['fontFamily'] = $payload->legendFont['family'];
            }
            if (isset($payload->legendFont['weight'])) {
                $legend['fontWeight'] = $payload->legendFont['weight'];
            }
        }

        return $legend;
    }

    /**
     * @return array<string, mixed>
     */
    private function buildTooltipOptions(ChartPayload $payload): array
    {
        $tooltip = ['enabled' => $payload->tooltip];

        if ($payload->tooltipColor !== null || $payload->tooltipFont !== null) {
            $style = [];

            if ($payload->tooltipColor !== null) {
                $style['color'] = $this->pickColor($payload->tooltipColor);
            }
            if (isset($payload->tooltipFont['size'])) {
                $style['fontSize'] = $payload->tooltipFont['size'].'px';
            }
            if (isset($payload->tooltipFont['family'])) {
                $style['fontFamily'] = $payload->tooltipFont['family'];
            }

            if (! empty($style)) {
                $tooltip['style'] = $style;
            }
        }

        if ($payload->tooltipColor !== null) {
            $tooltip = array_merge($tooltip, $this->themedSidecar('tooltipColor', $payload->tooltipColor));
        }

        return $tooltip;
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
