<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Charts;

use Matheusmarnt\LiveCharts\Contracts\ChartContract;
use Matheusmarnt\LiveCharts\Engines\EngineFactory;
use Matheusmarnt\LiveCharts\Enums\ThemeMode;
use Matheusmarnt\LiveCharts\Enums\TwColor;
use Matheusmarnt\LiveCharts\Enums\TwPalette;
use Matheusmarnt\LiveCharts\Exceptions\DataShapeMismatchException;
use Matheusmarnt\LiveCharts\Exceptions\EmptyDatasetException;
use Matheusmarnt\LiveCharts\Support\ChartPayload;
use Matheusmarnt\LiveCharts\Support\ColorResolver;
use Matheusmarnt\LiveCharts\Support\ColorValue;

/**
 * @phpstan-consistent-constructor
 */
abstract class Chart implements ChartContract
{
    /**
     * All chart types shipped by the package, derived from concrete Chart subclasses.
     *
     * @var list<string>
     */
    public const TYPES = [
        'area', 'bar', 'boxPlot', 'bubble', 'candlestick',
        'donut', 'heatmap', 'line', 'matrix', 'pie',
        'polarArea', 'radar', 'radialBar', 'rangeBar',
        'sankey', 'scatter', 'treemap',
    ];

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

    protected bool $engineExplicit = false;

    protected ?string $title = null;

    protected ?string $subtitle = null;

    protected int|string $height = 350;

    protected int|string $width = '100%';

    /** @var array<int, string|int|float> */
    protected array $labels = [];

    /** @var array<int, Dataset> */
    protected array $datasets = [];

    /** @var list<ColorValue> */
    protected array $colors = [];

    protected string $theme = 'auto';

    protected ?ColorValue $titleColor = null;

    protected ?ColorValue $subtitleColor = null;

    protected ?ColorValue $legendColor = null;

    protected ?ColorValue $labelsColor = null;

    protected ?ColorValue $tooltipColor = null;

    protected ?ColorValue $axisColor = null;

    protected ?ColorValue $gridColor = null;

    protected ?ColorValue $dataLabelsColor = null;

    protected ?ColorValue $backgroundColor = null;

    /** @var array{size?: int, weight?: string, family?: string}|null */
    protected ?array $titleFont = null;

    /** @var array{size?: int, weight?: string, family?: string}|null */
    protected ?array $legendFont = null;

    /** @var array{size?: int, weight?: string, family?: string}|null */
    protected ?array $tooltipFont = null;

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

    /** @var array<string, mixed> */
    protected array $xaxis = [];

    /** @var array<string, mixed> */
    protected array $yaxis = [];

    /** @var array<string, mixed> */
    protected array $grid = [];

    /** @var array<string, mixed> */
    protected array $stroke = [];

    /** @var array<string, mixed> */
    protected array $markers = [];

    /** @var array<string, mixed> */
    protected array $dataLabels = [];

    /** @var array<string, mixed> */
    protected array $options = [];

    public function __construct()
    {
        $this->engine = config('livecharts.engine', 'apexcharts');
        $this->theme = config('livecharts.theme.mode', 'auto');
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
        $this->engineExplicit = true;

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

    /**
     * @param  array<int, string|int|float>  $labels
     */
    public function labels(array $labels): self
    {
        $this->labels = $labels;

        return $this;
    }

    /**
     * @param  array<int, mixed>  $data
     */
    public function dataset(string $name, array $data): self
    {
        $this->datasets[] = Dataset::make($name)->data($data);

        return $this;
    }

    /**
     * @param  array<int, Dataset|array<string, mixed>>  $datasets
     */
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

    /**
     * @param  array<int, string|TwColor|ColorValue|array{dark?: TwColor|string, light?: TwColor|string}>  $colors
     */
    public function colors(array $colors): self
    {
        $this->colors = ColorResolver::resolveList($colors);

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

    public function theme(ThemeMode|string $mode): self
    {
        $this->theme = $mode instanceof ThemeMode ? $mode->value : $mode;

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

    public function titleColor(TwColor|string|null $dark = null, TwColor|string|null $light = null): self
    {
        $this->titleColor = ColorResolver::resolvePair($dark, $light);

        return $this;
    }

    public function subtitleColor(TwColor|string|null $dark = null, TwColor|string|null $light = null): self
    {
        $this->subtitleColor = ColorResolver::resolvePair($dark, $light);

        return $this;
    }

    public function legendColor(TwColor|string|null $dark = null, TwColor|string|null $light = null): self
    {
        $this->legendColor = ColorResolver::resolvePair($dark, $light);

        return $this;
    }

    public function labelsColor(TwColor|string|null $dark = null, TwColor|string|null $light = null): self
    {
        $this->labelsColor = ColorResolver::resolvePair($dark, $light);

        return $this;
    }

    public function tooltipColor(TwColor|string|null $dark = null, TwColor|string|null $light = null): self
    {
        $this->tooltipColor = ColorResolver::resolvePair($dark, $light);

        return $this;
    }

    public function axisColor(TwColor|string|null $dark = null, TwColor|string|null $light = null): self
    {
        $this->axisColor = ColorResolver::resolvePair($dark, $light);

        return $this;
    }

    public function gridColor(TwColor|string|null $dark = null, TwColor|string|null $light = null): self
    {
        $this->gridColor = ColorResolver::resolvePair($dark, $light);

        return $this;
    }

    public function dataLabelsColor(TwColor|string|null $dark = null, TwColor|string|null $light = null): self
    {
        $this->dataLabelsColor = ColorResolver::resolvePair($dark, $light);

        return $this;
    }

    public function backgroundColor(TwColor|string|null $dark = null, TwColor|string|null $light = null): self
    {
        $this->backgroundColor = ColorResolver::resolvePair($dark, $light);

        return $this;
    }

    public function palette(TwPalette $palette): self
    {
        $pairs = $palette->pairs();

        $this->colors = array_map(
            fn (array $pair) => ColorValue::pair($pair['dark'], $pair['light']),
            $pairs,
        );

        return $this;
    }

    public function titleFont(?int $size = null, ?string $weight = null, ?string $family = null): self
    {
        $this->titleFont = array_filter(
            ['size' => $size, 'weight' => $weight, 'family' => $family],
            fn ($v) => $v !== null,
        );

        return $this;
    }

    public function legendFont(?int $size = null, ?string $weight = null, ?string $family = null): self
    {
        $this->legendFont = array_filter(
            ['size' => $size, 'weight' => $weight, 'family' => $family],
            fn ($v) => $v !== null,
        );

        return $this;
    }

    public function tooltipFont(?int $size = null, ?string $weight = null, ?string $family = null): self
    {
        $this->tooltipFont = array_filter(
            ['size' => $size, 'weight' => $weight, 'family' => $family],
            fn ($v) => $v !== null,
        );

        return $this;
    }

    public function pollEvery(int $ms): self
    {
        $this->pollEvery = $ms;

        return $this;
    }

    public function poll(int $ms): self
    {
        return $this->pollEvery($ms);
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

    /**
     * @param  array<string, mixed>  $config
     */
    public function xaxis(array $config): self
    {
        $this->xaxis = array_merge_recursive($this->xaxis, $config);

        return $this;
    }

    /**
     * @param  array<string, mixed>  $config
     */
    public function yaxis(array $config): self
    {
        $this->yaxis = array_merge_recursive($this->yaxis, $config);

        return $this;
    }

    /**
     * @param  array<string, mixed>  $config
     */
    public function grid(array $config): self
    {
        $this->grid = array_merge_recursive($this->grid, $config);

        return $this;
    }

    /**
     * @param  array<string, mixed>  $config
     */
    public function stroke(array $config): self
    {
        $this->stroke = array_merge_recursive($this->stroke, $config);

        return $this;
    }

    /**
     * @param  array<string, mixed>  $config
     */
    public function markers(array $config): self
    {
        $this->markers = array_merge_recursive($this->markers, $config);

        return $this;
    }

    /**
     * @param  array<string, mixed>  $config
     */
    public function dataLabels(array $config): self
    {
        $this->dataLabels = array_merge_recursive($this->dataLabels, $config);

        return $this;
    }

    /**
     * @param  array<string, mixed>  $options
     */
    public function options(array $options): self
    {
        $this->options = array_merge_recursive($this->options, $options);

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toPayload(): array
    {
        return $this->toPayloadObject()->toArray();
    }

    public function toPayloadObject(): ChartPayload
    {
        if (! $this->engineExplicit) {
            $this->engine = app(EngineFactory::class)->engineForType($this->type);
        }

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
            titleColor: $this->titleColor,
            subtitleColor: $this->subtitleColor,
            legendColor: $this->legendColor,
            labelsColor: $this->labelsColor,
            tooltipColor: $this->tooltipColor,
            axisColor: $this->axisColor,
            gridColor: $this->gridColor,
            dataLabelsColor: $this->dataLabelsColor,
            backgroundColor: $this->backgroundColor,
            titleFont: $this->titleFont,
            legendFont: $this->legendFont,
            tooltipFont: $this->tooltipFont,
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
            if ($dataset->data === []) {
                throw EmptyDatasetException::forDataset($dataset->name);
            }
        }

        if ($this->labels === [] || ! in_array($this->type, self::TYPES_REQUIRING_LABEL_MATCH, true)) {
            return;
        }

        $expected = count($this->labels);

        foreach ($this->datasets as $dataset) {
            $actual = count($dataset->data);

            if ($actual !== $expected) {
                throw DataShapeMismatchException::forDataset($dataset->name, $expected, $actual);
            }
        }
    }
}
