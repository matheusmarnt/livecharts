<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Charts;

use Matheusmarnt\LiveCharts\Contracts\ChartContract;
use Matheusmarnt\LiveCharts\Exceptions\DataShapeMismatchException;
use Matheusmarnt\LiveCharts\Exceptions\EmptyDatasetException;
use Matheusmarnt\LiveCharts\Support\ChartPayload;

/**
 * @phpstan-consistent-constructor
 */
abstract class Chart implements ChartContract
{
    /**
     * Chart types whose label count must match each dataset's data count.
     *
     * @var list<string>
     */
    protected const TYPES_REQUIRING_LABEL_MATCH = [
        'line', 'bar', 'area', 'scatter', 'radar', 'bubble',
        'pie', 'donut', 'doughnut', 'radialBar', 'polarArea',
    ];

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

    protected ?string $dataPointClickEvent = null;

    protected ?string $zoomEvent = null;

    protected ?string $selectionEvent = null;

    protected ?string $scrollEvent = null;

    protected ?string $broadcastChannel = null;

    protected ?string $broadcastEvent = null;

    protected array $xaxis = [];

    protected array $yaxis = [];

    protected array $grid = [];

    protected array $stroke = [];

    protected array $markers = [];

    protected array $dataLabels = [];

    protected array $options = [];

    public function __construct()
    {
        $this->engine = config('livecharts.engine', 'apexcharts');
    }

    public static function make(): static
    {
        return new static;
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
                $this->datasets[] = Dataset::make($dataset['name'])
                    ->data($dataset['data'])
                    ->color($dataset['color'] ?? null)
                    ->type($dataset['type'] ?? null)
                    ->meta($dataset['meta'] ?? []);
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

    public function onDataPointClick(string $event): self
    {
        $this->dataPointClickEvent = $event;

        return $this;
    }

    public function onZoom(string $event): self
    {
        $this->zoomEvent = $event;

        return $this;
    }

    public function onSelection(string $event): self
    {
        $this->selectionEvent = $event;

        return $this;
    }

    public function onScroll(string $event): self
    {
        $this->scrollEvent = $event;

        return $this;
    }

    public function broadcastOn(string $channel): self
    {
        $this->broadcastChannel = $channel;

        return $this;
    }

    public function broadcastAs(string $event): self
    {
        $this->broadcastEvent = $event;

        return $this;
    }

    public function xaxis(array $config): self
    {
        $this->xaxis = array_merge_recursive($this->xaxis, $config);

        return $this;
    }

    public function yaxis(array $config): self
    {
        $this->yaxis = array_merge_recursive($this->yaxis, $config);

        return $this;
    }

    public function grid(array $config): self
    {
        $this->grid = array_merge_recursive($this->grid, $config);

        return $this;
    }

    public function stroke(array $config): self
    {
        $this->stroke = array_merge_recursive($this->stroke, $config);

        return $this;
    }

    public function markers(array $config): self
    {
        $this->markers = array_merge_recursive($this->markers, $config);

        return $this;
    }

    public function dataLabels(array $config): self
    {
        $this->dataLabels = array_merge_recursive($this->dataLabels, $config);

        return $this;
    }

    public function options(array $options): self
    {
        $this->options = array_merge_recursive($this->options, $options);

        return $this;
    }

    public function toPayload(): array
    {
        return $this->toPayloadObject()->toArray();
    }

    public function toPayloadObject(): ChartPayload
    {
        $this->validate();

        return new ChartPayload(
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
            onDataPointClick: $this->dataPointClickEvent,
            onZoom: $this->zoomEvent,
            onSelection: $this->selectionEvent,
            onScroll: $this->scrollEvent,
            broadcastOn: $this->broadcastChannel,
            broadcastAs: $this->broadcastEvent,
            xaxis: $this->xaxis,
            yaxis: $this->yaxis,
            grid: $this->grid,
            stroke: $this->stroke,
            markers: $this->markers,
            dataLabels: $this->dataLabels,
            options: $this->options,
        );
    }

    /**
     * @throws EmptyDatasetException
     * @throws DataShapeMismatchException
     */
    protected function validate(): void
    {
        if ($this->datasets === []) {
            throw EmptyDatasetException::forChart($this->type);
        }

        foreach ($this->datasets as $dataset) {
            if (! $dataset instanceof Dataset) {
                continue;
            }

            if ($dataset->data === []) {
                throw EmptyDatasetException::forDataset($dataset->name);
            }
        }

        if ($this->labels === [] || ! in_array($this->type, self::TYPES_REQUIRING_LABEL_MATCH, true)) {
            return;
        }

        $expected = count($this->labels);

        foreach ($this->datasets as $dataset) {
            if (! $dataset instanceof Dataset) {
                continue;
            }

            $actual = count($dataset->data);

            if ($actual !== $expected) {
                throw DataShapeMismatchException::forDataset($dataset->name, $expected, $actual);
            }
        }
    }
}
