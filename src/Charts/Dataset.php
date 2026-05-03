<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Charts;

use Matheusmarnt\LiveCharts\Enums\TwColor;
use Matheusmarnt\LiveCharts\Support\ColorResolver;
use Matheusmarnt\LiveCharts\Support\ColorValue;

class Dataset
{
    /**
     * @param  array<int, mixed>  $data
     * @param  array<string, mixed>  $meta
     */
    public function __construct(
        public string $name,
        public array $data,
        public ?ColorValue $background = null,
        public ?ColorValue $border = null,
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

    /**
     * Sets both background and border to the same color (convenience sugar).
     */
    public function color(TwColor|ColorValue|string|null $color): self
    {
        $resolved = ColorResolver::resolve($color);
        $this->background = $resolved;
        $this->border = $resolved;

        return $this;
    }

    public function backgroundColor(TwColor|string|null $dark = null, TwColor|string|null $light = null): self
    {
        $this->background = ColorResolver::resolvePair($dark, $light);

        return $this;
    }

    public function borderColor(TwColor|string|null $dark = null, TwColor|string|null $light = null): self
    {
        $this->border = ColorResolver::resolvePair($dark, $light);

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
     * @return array{name: string, data: array<int, mixed>, background: array{dark: string, light: string}|null, border: array{dark: string, light: string}|null, type: string|null, meta: array<string, mixed>}
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'data' => $this->data,
            'background' => $this->background?->jsonSerialize(),
            'border' => $this->border?->jsonSerialize(),
            'type' => $this->type,
            'meta' => $this->meta,
        ];
    }
}
