<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Support;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

class ChartPayload implements Arrayable, Jsonable, JsonSerializable
{
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
        public array $options = [],
    ) {}

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
            'datasets' => array_map(fn($dataset) => $dataset->toArray(), $this->datasets),
            'colors' => $this->colors,
            'theme' => $this->theme,
            'stacked' => $this->stacked,
            'sparkline' => $this->sparkline,
            'zoom' => $this->zoom,
            'toolbar' => $this->toolbar,
            'legend' => $this->legend,
            'tooltip' => $this->tooltip,
            'pollEvery' => $this->pollEvery,
            'options' => $this->options,
        ];
    }

    public function toJson($options = 0): string
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }
}
