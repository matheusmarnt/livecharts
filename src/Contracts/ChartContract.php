<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Contracts;

use Matheusmarnt\LiveCharts\Charts\Dataset;

interface ChartContract
{
    public function type(string $type): self;

    public function engine(string $engine): self;

    public function title(string $title): self;

    public function subtitle(string $subtitle): self;

    /**
     * @param  array<int, string|int|float>  $labels
     */
    public function labels(array $labels): self;

    /**
     * @param  array<int, mixed>  $data
     */
    public function dataset(string $name, array $data): self;

    /**
     * @param  array<int, Dataset|array<string, mixed>>  $datasets
     */
    public function datasets(array $datasets): self;

    /**
     * @param  array<int, string>  $colors
     */
    public function colors(array $colors): self;

    public function height(int|string $height): self;

    public function width(int|string $width): self;

    /**
     * @param  array<string, mixed>  $options
     */
    public function options(array $options): self;

    /**
     * @param  array<string, mixed>  $config
     */
    public function xaxis(array $config): self;

    /**
     * @param  array<string, mixed>  $config
     */
    public function yaxis(array $config): self;

    /**
     * @param  array<string, mixed>  $config
     */
    public function grid(array $config): self;

    /**
     * @param  array<string, mixed>  $config
     */
    public function stroke(array $config): self;

    /**
     * @param  array<string, mixed>  $config
     */
    public function markers(array $config): self;

    /**
     * @param  array<string, mixed>  $config
     */
    public function dataLabels(array $config): self;

    public function onDataPointClick(string $event): self;

    public function onZoom(string $event): self;

    public function onSelection(string $event): self;

    public function onScroll(string $event): self;

    public function broadcastOn(string $channel): self;

    public function broadcastAs(string $event): self;

    /**
     * @return array<string, mixed>
     */
    public function toPayload(): array;
}
