<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Contracts;

interface ChartContract
{
    public function type(string $type): self;

    public function engine(string $engine): self;

    public function title(string $title): self;

    public function subtitle(string $subtitle): self;

    public function labels(array $labels): self;

    public function dataset(string $name, array $data): self;

    public function datasets(array $datasets): self;

    public function colors(array $colors): self;

    public function height(int|string $height): self;

    public function width(int|string $width): self;

    public function options(array $options): self;

    public function onDataPointClick(string $event): self;

    public function toPayload(): array;
}
