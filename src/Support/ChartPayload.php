<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Support;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;
use Matheusmarnt\LiveCharts\Charts\Dataset;
use RuntimeException;

/**
 * @implements Arrayable<string, mixed>
 */
class ChartPayload implements Arrayable, Jsonable, JsonSerializable
{
    /**
     * @param  array<int, string|int|float>  $labels
     * @param  array<int, Dataset>  $datasets
     * @param  list<ColorValue>  $colors
     * @param  array<string, mixed>  $xaxis
     * @param  array<string, mixed>  $yaxis
     * @param  array<string, mixed>  $grid
     * @param  array<string, mixed>  $stroke
     * @param  array<string, mixed>  $markers
     * @param  array<string, mixed>  $dataLabels
     * @param  array<string, mixed>  $options
     * @param  array{size?: int, weight?: string, family?: string}|null  $titleFont
     * @param  array{size?: int, weight?: string, family?: string}|null  $legendFont
     * @param  array{size?: int, weight?: string, family?: string}|null  $tooltipFont
     */
    public function __construct(
        public string $type,
        public string $engine,
        public ?string $title = null,
        public ?string $subtitle = null,
        public int|string $height = 350,
        public int|string $width = '100%',
        public array $labels = [],
        public array $datasets = [],
        public array $colors = [],
        public string $theme = 'auto',
        public bool $stacked = false,
        public bool $sparkline = false,
        public bool $zoom = false,
        public bool $toolbar = false,
        public bool $legend = true,
        public bool $tooltip = true,
        public int $pollEvery = 0,
        public ?string $onDataPointClick = null,
        public ?string $onZoom = null,
        public ?string $onSelection = null,
        public ?string $onScroll = null,
        public ?string $broadcastOn = null,
        public ?string $broadcastAs = null,
        public array $xaxis = [],
        public array $yaxis = [],
        public array $grid = [],
        public array $stroke = [],
        public array $markers = [],
        public array $dataLabels = [],
        public array $options = [],
        public ?ColorValue $titleColor = null,
        public ?ColorValue $subtitleColor = null,
        public ?ColorValue $legendColor = null,
        public ?ColorValue $labelsColor = null,
        public ?ColorValue $tooltipColor = null,
        public ?ColorValue $axisColor = null,
        public ?ColorValue $gridColor = null,
        public ?ColorValue $dataLabelsColor = null,
        public ?ColorValue $backgroundColor = null,
        public ?array $titleFont = null,
        public ?array $legendFont = null,
        public ?array $tooltipFont = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'type' => $this->type,
            'engine' => $this->engine,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'height' => $this->height,
            'width' => $this->width,
            'labels' => $this->labels,
            'datasets' => array_map(fn ($dataset) => $dataset->toArray(), $this->datasets),
            'colors' => array_map(fn (ColorValue $cv) => $cv->jsonSerialize(), $this->colors),
            'theme' => $this->theme,
            'stacked' => $this->stacked,
            'sparkline' => $this->sparkline,
            'zoom' => $this->zoom,
            'toolbar' => $this->toolbar,
            'legend' => $this->legend,
            'tooltip' => $this->tooltip,
            'pollEvery' => $this->pollEvery,
            'onDataPointClick' => $this->onDataPointClick,
            'onZoom' => $this->onZoom,
            'onSelection' => $this->onSelection,
            'onScroll' => $this->onScroll,
            'broadcastOn' => $this->broadcastOn,
            'broadcastAs' => $this->broadcastAs,
            'xaxis' => $this->xaxis,
            'yaxis' => $this->yaxis,
            'grid' => $this->grid,
            'stroke' => $this->stroke,
            'markers' => $this->markers,
            'dataLabels' => $this->dataLabels,
            'options' => $this->options,
            'titleColor' => $this->titleColor?->jsonSerialize(),
            'subtitleColor' => $this->subtitleColor?->jsonSerialize(),
            'legendColor' => $this->legendColor?->jsonSerialize(),
            'labelsColor' => $this->labelsColor?->jsonSerialize(),
            'tooltipColor' => $this->tooltipColor?->jsonSerialize(),
            'axisColor' => $this->axisColor?->jsonSerialize(),
            'gridColor' => $this->gridColor?->jsonSerialize(),
            'dataLabelsColor' => $this->dataLabelsColor?->jsonSerialize(),
            'backgroundColor' => $this->backgroundColor?->jsonSerialize(),
            'titleFont' => $this->titleFont,
            'legendFont' => $this->legendFont,
            'tooltipFont' => $this->tooltipFont,
        ];
    }

    public function toJson($options = 0): string
    {
        $encoded = json_encode($this->jsonSerialize(), $options);

        if ($encoded === false) {
            throw new RuntimeException('Failed to encode ChartPayload to JSON: '.json_last_error_msg());
        }

        return $encoded;
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
