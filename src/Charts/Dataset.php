<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Charts;

class Dataset
{
    public function __construct(
        public string $name,
        public array $data,
        public ?string $color = null,
        public ?string $type = null,
        public array $meta = [],
    ) {}

    public static function make(string $name): self
    {
        return new self($name, []);
    }

    public function data(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function color(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function type(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function meta(array $meta): self
    {
        $this->meta = $meta;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'data' => $this->data,
            'color' => $this->color,
            'type' => $this->type,
            'meta' => $this->meta,
        ];
    }
}
