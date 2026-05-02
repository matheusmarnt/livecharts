<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Charts;

use Matheusmarnt\LiveCharts\Contracts\ChartContract;
use Matheusmarnt\LiveCharts\Support\ChartPayload;

abstract class Chart implements ChartContract
{
    protected string $type = 'line';
    protected string $engine;
    protected ?string $title = null;
    protected ?string $subtitle = null;
    protected int|string $height = 350;
    protected int|string $width = '100%';
    protected array $labels = [];
    protected array $datasets = [];
    protected array $colors = [];
    protected string $theme = 'auto';
    protected bool $stacked = false;
    protected bool $sparkline = false;
    protected bool $zoom = false;
    protected bool $toolbar = false;
    protected bool $legend = true;
    protected bool $tooltip = true;
    protected int $pollEvery = 0;
    protected array $options = [];

    public function __construct()
    {
        $this->engine = config('livecharts.engine', 'apexcharts');
    }

    public static function make(): static
    {
        return new static();
    }

    public function type(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function engine(string $engine): self
    {
        $this->engine = $engine;
        return $this;
    }

    public function title(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function subtitle(string $subtitle): self
    {
        $this->subtitle = $subtitle;
        return $this;
    }

    public function labels(array $labels): self
    {
        $this->labels = $labels;
        return $this;
    }

    public function dataset(string $name, array $data): self
    {
        $this->datasets[] = Dataset::make($name)->data($data);
        return $this;
    }

    public function datasets(array $datasets): self
    {
        foreach ($datasets as $dataset) {
            if ($dataset instanceof Dataset) {
                $this->datasets[] = $dataset;
            } else {
                $this->datasets[] = Dataset::make($dataset['name'])->data($dataset['data']);
            }
        }
        return $this;
    }

    public function colors(array $colors): self
    {
        $this->colors = $colors;
        return $this;
    }

    public function height(int|string $height): self
    {
        $this->height = $height;
        return $this;
    }

    public function width(int|string $width): self
    {
        $this->width = $width;
        return $this;
    }

    public function theme(string $theme): self
    {
        $this->theme = $theme;
        return $this;
    }

    public function stacked(bool $stacked = true): self
    {
        $this->stacked = $stacked;
        return $this;
    }

    public function sparkline(bool $sparkline = true): self
    {
        $this->sparkline = $sparkline;
        return $this;
    }

    public function zoom(bool $zoom = true): self
    {
        $this->zoom = $zoom;
        return $this;
    }

    public function toolbar(bool $toolbar = true): self
    {
        $this->toolbar = $toolbar;
        return $this;
    }

    public function legend(bool $legend = true): self
    {
        $this->legend = $legend;
        return $this;
    }

    public function tooltip(bool $tooltip = true): self
    {
        $this->tooltip = $tooltip;
        return $this;
    }

    public function pollEvery(int $ms): self
    {
        $this->pollEvery = $ms;
        return $this;
    }

    public function options(array $options): self
    {
        $this->options = array_merge_recursive($this->options, $options);
        return $this;
    }

    public function toPayload(): array
    {
        return (new ChartPayload(
            type: $this->type,
            engine: $this->engine,
            title: $this->title,
            subtitle: $this->subtitle,
            height: $this->height,
            width: $this->width,
            labels: $this->labels,
            datasets: $this->datasets,
            colors: $this->colors,
            theme: $this->theme,
            stacked: $this->stacked,
            sparkline: $this->sparkline,
            zoom: $this->zoom,
            toolbar: $this->toolbar,
            legend: $this->legend,
            tooltip: $this->tooltip,
            pollEvery: $this->pollEvery,
            options: $this->options,
        ))->toArray();
    }
}
