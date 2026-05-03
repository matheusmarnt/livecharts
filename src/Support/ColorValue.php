<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Support;

use JsonSerializable;
use Matheusmarnt\LiveCharts\Enums\TwColor;

/**
 * Immutable carrier for a theme-aware color pair {dark, light}.
 *
 * When the dev passes a plain hex string (e.g. '#ff0000'), both dark
 * and light resolve to that same hex — the adapter/JS resolver still
 * works uniformly.
 */
final class ColorValue implements JsonSerializable
{
    public function __construct(
        public readonly string $dark,
        public readonly string $light,
        public readonly ?float $alpha = null,
    ) {}

    /**
     * Build from a single value (both themes get the same hex).
     */
    public static function from(TwColor|self|string $value): self
    {
        if ($value instanceof self) {
            return $value;
        }

        $hex = $value instanceof TwColor ? $value->value : $value;

        return new self($hex, $hex);
    }

    /**
     * Build a theme-aware pair.
     */
    public static function pair(
        TwColor|string $dark,
        TwColor|string $light,
        ?float $alpha = null,
    ): self {
        $darkHex = $dark  instanceof TwColor ? $dark->value : $dark;
        $lightHex = $light instanceof TwColor ? $light->value : $light;

        return new self($darkHex, $lightHex, $alpha);
    }

    public function withAlpha(float $alpha): self
    {
        return new self($this->dark, $this->light, max(0.0, min(1.0, $alpha)));
    }

    public function darkHex(): string
    {
        return $this->applyAlpha($this->dark);
    }

    public function lightHex(): string
    {
        return $this->applyAlpha($this->light);
    }

    public function forTheme(string $theme): string
    {
        return $theme === 'dark' ? $this->darkHex() : $this->lightHex();
    }

    private function applyAlpha(string $hex): string
    {
        if ($this->alpha === null) {
            return $hex;
        }

        $raw = ltrim($hex, '#');
        if (strlen($raw) !== 6) {
            return $hex;
        }

        $r = (int) hexdec(substr($raw, 0, 2));
        $g = (int) hexdec(substr($raw, 2, 2));
        $b = (int) hexdec(substr($raw, 4, 2));

        return "rgba({$r},{$g},{$b},{$this->alpha})";
    }

    /**
     * @return array{dark: string, light: string}
     */
    public function jsonSerialize(): array
    {
        return [
            'dark' => $this->darkHex(),
            'light' => $this->lightHex(),
        ];
    }
}
