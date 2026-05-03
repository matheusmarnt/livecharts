<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Engines;

use Matheusmarnt\LiveCharts\Contracts\EngineAdapter;
use Matheusmarnt\LiveCharts\Exceptions\InvalidChartTypeException;
use Matheusmarnt\LiveCharts\Support\ChartPayload;
use Matheusmarnt\LiveCharts\Support\ColorValue;

abstract class BaseEngineAdapter implements EngineAdapter
{
    /**
     * Chart types that ship a single, flat series array instead of a list of datasets.
     *
     * @var list<string>
     */
    protected const SINGLE_SERIES_TYPES = [
        'pie', 'donut', 'doughnut', 'radialBar', 'polarArea',
    ];

    /**
     * Chart types accepted by the concrete engine.
     *
     * @return list<string>
     */
    abstract public function supportedTypes(): array;

    /**
     * Identifier used in error messages and the engine registry.
     */
    abstract public function engineName(): string;

    /**
     * @throws InvalidChartTypeException
     */
    protected function assertTypeSupported(ChartPayload $payload): void
    {
        if (! in_array($payload->type, $this->supportedTypes(), true)) {
            throw InvalidChartTypeException::forEngine(
                $payload->type,
                $this->engineName(),
                $this->supportedTypes(),
            );
        }
    }

    protected function isSingleSeries(string $type): bool
    {
        return in_array($type, static::SINGLE_SERIES_TYPES, true);
    }

    /**
     * @return array<int, string|int|float>
     */
    protected function normalizeLabels(ChartPayload $payload): array
    {
        return $payload->labels;
    }

    /**
     * Returns the light-theme hex list for the chart-level colors array.
     * JS observer will swap to dark values at runtime.
     *
     * @return list<string>
     */
    protected function normalizeColors(ChartPayload $payload): array
    {
        return array_map(
            fn (ColorValue $cv) => $cv->lightHex(),
            $payload->colors,
        );
    }

    /**
     * Resolve a ColorValue to its initial (light) hex for server-side paint.
     * Returns null when $cv is null (slot not set → engine default applies).
     */
    protected function pickColor(?ColorValue $cv): ?string
    {
        return $cv?->lightHex();
    }

    /**
     * Build a __lc_themed sidecar for a single color slot.
     * The JS observer reads this to swap on theme toggle.
     *
     * @return array{__lc_themed: array<string, array{dark: string, light: string}>}|array{}
     */
    protected function themedSidecar(string $key, ?ColorValue $cv): array
    {
        if ($cv === null) {
            return [];
        }

        return [
            '__lc_themed' => [
                $key => $cv->jsonSerialize(),
            ],
        ];
    }
}
