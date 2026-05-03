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

        $scaleOverrides = array_filter([
            'x' => $payload->xaxis ?: null,
            'y' => $payload->yaxis ?: null,
        ]);

        $plugins = [
            'title' => $this->buildTitlePlugin($payload),
            'subtitle' => $this->buildSubtitlePlugin($payload),
            'legend' => $this->buildLegendPlugin($payload),
            'tooltip' => $this->buildTooltipPlugin($payload),
        ];

        if (! empty($payload->dataLabels)) {
            $plugins['datalabels'] = $payload->dataLabels;
        }

        if ($payload->dataLabelsColor !== null) {
            $datalabels = $plugins['datalabels'] ?? [];
            $datalabels['color'] = $this->pickColor($payload->dataLabelsColor);
            $datalabels = array_merge($datalabels, $this->themedSidecar('dataLabelsColor', $payload->dataLabelsColor));
            $plugins['datalabels'] = $datalabels;
        }

        $scales = array_merge_recursive($this->buildScales($payload), $scaleOverrides);

        if ($payload->labelsColor !== null) {
            $ticksBlock = ['color' => $this->pickColor($payload->labelsColor)];
            $ticksBlock = array_merge($ticksBlock, $this->themedSidecar('labelsColor', $payload->labelsColor));
            $scales['x']['ticks'] = array_merge($scales['x']['ticks'] ?? [], $ticksBlock);
            $scales['y']['ticks'] = array_merge($scales['y']['ticks'] ?? [], $ticksBlock);
        }

        if ($payload->axisColor !== null) {
            $borderBlock = ['color' => $this->pickColor($payload->axisColor)];
            $borderBlock = array_merge($borderBlock, $this->themedSidecar('axisColor', $payload->axisColor));
            $scales['x']['border'] = array_merge($scales['x']['border'] ?? [], $borderBlock);
            $scales['y']['border'] = array_merge($scales['y']['border'] ?? [], $borderBlock);
        }

        if ($payload->gridColor !== null) {
            $gridBlock = ['color' => $this->pickColor($payload->gridColor)];
            $gridBlock = array_merge($gridBlock, $this->themedSidecar('gridColor', $payload->gridColor));
            $scales['x']['grid'] = array_merge($scales['x']['grid'] ?? [], $gridBlock);
            $scales['y']['grid'] = array_merge($scales['y']['grid'] ?? [], $gridBlock);
        }

        $options = [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => $scales,
            'plugins' => $plugins,
        ];

        return [
            'type' => $chartType,
            'data' => [
                'labels' => $this->normalizeLabels($payload),
                'datasets' => array_map(
                    fn ($dataset, int $idx) => $this->buildDataset($dataset, $idx, $colors, $isSingleSeries, $payload),
                    $payload->datasets,
                    array_keys($payload->datasets),
                ),
            ],
            'options' => array_merge_recursive($options, $payload->options),
        ];
    }

    /**
     * @param  list<string>  $colors
     * @return array<string, mixed>
     */
    private function buildDataset(mixed $dataset, int $idx, array $colors, bool $isSingleSeries, ChartPayload $payload): array
    {
        if ($isSingleSeries) {
            $bg = $colors;
            $border = '#fff';
        } else {
            $bg = $dataset->background?->lightHex() ?? $colors[$idx] ?? ($colors[0] ?? null);
            $border = $dataset->border?->lightHex() ?? $bg;
        }

        $row = [
            'type' => $dataset->type,
            'label' => $dataset->name,
            'data' => $dataset->data,
            'backgroundColor' => $bg,
            'borderColor' => $border,
            'fill' => $payload->type === 'area' || $dataset->type === 'area',
            'tension' => ($payload->stroke['curve'] ?? null) === 'smooth' ? 0.4 : 0,
            ...$payload->stroke,
            'point' => $payload->markers,
        ];

        if (! $isSingleSeries && $dataset->background !== null) {
            $row = array_merge($row, $this->themedSidecar('datasetBackground_'.$idx, $dataset->background));
        }

        if (! $isSingleSeries && $dataset->border !== null) {
            $row = array_merge($row, $this->themedSidecar('datasetBorder_'.$idx, $dataset->border));
        }

        return $row;
    }

    /**
     * @return array<string, mixed>
     */
    private function buildTitlePlugin(ChartPayload $payload): array
    {
        $plugin = [
            'display' => (bool) $payload->title,
            'text' => $payload->title,
        ];

        if ($payload->titleColor !== null) {
            $plugin['color'] = $this->pickColor($payload->titleColor);
            $plugin = array_merge($plugin, $this->themedSidecar('titleColor', $payload->titleColor));
        }

        if ($payload->titleFont !== null) {
            $plugin['font'] = array_filter([
                'size' => $payload->titleFont['size'] ?? null,
                'weight' => $payload->titleFont['weight'] ?? null,
                'family' => $payload->titleFont['family'] ?? null,
            ], fn ($v) => $v !== null);
        }

        return $plugin;
    }

    /**
     * @return array<string, mixed>
     */
    private function buildSubtitlePlugin(ChartPayload $payload): array
    {
        $plugin = [
            'display' => (bool) $payload->subtitle,
            'text' => $payload->subtitle,
        ];

        if ($payload->subtitleColor !== null) {
            $plugin['color'] = $this->pickColor($payload->subtitleColor);
            $plugin = array_merge($plugin, $this->themedSidecar('subtitleColor', $payload->subtitleColor));
        }

        return $plugin;
    }

    /**
     * @return array<string, mixed>
     */
    private function buildLegendPlugin(ChartPayload $payload): array
    {
        $plugin = ['display' => $payload->legend];

        if ($payload->legendColor !== null) {
            $labels = ['color' => $this->pickColor($payload->legendColor)];
            $labels = array_merge($labels, $this->themedSidecar('legendColor', $payload->legendColor));
            $plugin['labels'] = $labels;
        }

        if ($payload->legendFont !== null) {
            $fontBlock = array_filter([
                'size' => $payload->legendFont['size'] ?? null,
                'weight' => $payload->legendFont['weight'] ?? null,
                'family' => $payload->legendFont['family'] ?? null,
            ], fn ($v) => $v !== null);

            if (! empty($fontBlock)) {
                $plugin['labels'] = array_merge($plugin['labels'] ?? [], ['font' => $fontBlock]);
            }
        }

        return $plugin;
    }

    /**
     * @return array<string, mixed>
     */
    private function buildTooltipPlugin(ChartPayload $payload): array
    {
        $plugin = ['enabled' => $payload->tooltip];

        if ($payload->tooltipColor !== null) {
            $plugin['titleColor'] = $this->pickColor($payload->tooltipColor);
            $plugin['bodyColor'] = $this->pickColor($payload->tooltipColor);
            $plugin = array_merge($plugin, $this->themedSidecar('tooltipColor', $payload->tooltipColor));
        }

        if ($payload->tooltipFont !== null) {
            $fontBlock = array_filter([
                'size' => $payload->tooltipFont['size'] ?? null,
                'weight' => $payload->tooltipFont['weight'] ?? null,
                'family' => $payload->tooltipFont['family'] ?? null,
            ], fn ($v) => $v !== null);

            if (! empty($fontBlock)) {
                $plugin['titleFont'] = $fontBlock;
                $plugin['bodyFont'] = $fontBlock;
            }
        }

        return $plugin;
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

        // Register the engine asset first so plugin scripts always load and
        // execute after the chart.js global is defined in the DOM.
        $assetManager->registerAsset('chartjs');

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
