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
            $chartBlock = array_merge($chartBlock, $this->themedSidecar('background', $payload->backgroundColor));
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
            $labelStyle = array_merge(
                ['colors' => [$color]],
                $this->themedSidecar('colors', $payload->labelsColor),
            );
            $options['xaxis']['labels']['style'] = array_merge(
                $options['xaxis']['labels']['style'] ?? [],
                $labelStyle,
            );
            $options['yaxis']['labels']['style'] = array_merge(
                $options['yaxis']['labels']['style'] ?? [],
                $labelStyle,
            );
        }

        // Axis border + tick colors
        if ($payload->axisColor !== null) {
            $color = $this->pickColor($payload->axisColor);
            $axisSidecar = $this->themedSidecar('color', $payload->axisColor);
            $options['xaxis']['axisBorder'] = array_merge(
                $options['xaxis']['axisBorder'] ?? [],
                ['color' => $color],
                $axisSidecar,
            );
            $options['xaxis']['axisTicks'] = array_merge(
                $options['xaxis']['axisTicks'] ?? [],
                ['color' => $color],
                $axisSidecar,
            );
            $options['yaxis']['axisBorder'] = array_merge(
                $options['yaxis']['axisBorder'] ?? [],
                ['color' => $color],
                $axisSidecar,
            );
        }

        // Grid color
        if ($payload->gridColor !== null) {
            $options['grid'] = array_merge_recursive($options['grid'] ?? [], [
                'borderColor' => $this->pickColor($payload->gridColor),
            ]);
            $options['grid'] = array_merge(
                $options['grid'],
                $this->themedSidecar('borderColor', $payload->gridColor),
            );
        }

        // Data labels color
        if ($payload->dataLabelsColor !== null) {
            $dataLabelsStyle = array_merge(
                ['colors' => [$this->pickColor($payload->dataLabelsColor)]],
                $this->themedSidecar('colors', $payload->dataLabelsColor),
            );
            $options['dataLabels'] = array_merge_recursive($options['dataLabels'] ?? [], [
                'style' => $dataLabelsStyle,
            ]);
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
                $style = array_merge($style, $this->themedSidecar('color', $payload->titleColor));
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

        return $title;
    }

    /**
     * @return array<string, mixed>
     */
    private function buildSubtitleOptions(ChartPayload $payload): array
    {
        $subtitle = ['text' => $payload->subtitle];

        if ($payload->subtitleColor !== null) {
            $subtitle['style'] = array_merge(
                ['color' => $this->pickColor($payload->subtitleColor)],
                $this->themedSidecar('color', $payload->subtitleColor),
            );
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
            $legend['labels'] = array_merge(
                ['colors' => $this->pickColor($payload->legendColor)],
                $this->themedSidecar('colors', $payload->legendColor),
            );
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
                $style = array_merge($style, $this->themedSidecar('color', $payload->tooltipColor));

                // tooltip.theme controls bg+text as a unit ('dark'/'light').
                // Swap in sync with page theme so the tooltip matches the active mode.
                $tooltip['theme'] = 'light';
                $tooltip = array_merge($tooltip, [
                    '__lc_themed' => ['theme' => ['dark' => 'dark', 'light' => 'light']],
                ]);
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
