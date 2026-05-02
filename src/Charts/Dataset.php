<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Charts;

class Dataset
{
    /**
     * @param  array<int, mixed>  $data
     * @param  array<string, mixed>  $meta
     */
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

    /**
     * @param  array<int, mixed>  $data
     */
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

    /**
     * @param  array<string, mixed>  $meta
     */
    public function meta(array $meta): self
    {
        $this->meta = $meta;

        return $this;
    }

    /**
     * @return array{name: string, data: array<int, mixed>, color: string|null, type: string|null, meta: array<string, mixed>}
     */
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
