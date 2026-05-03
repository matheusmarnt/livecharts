<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Engines;

use Matheusmarnt\LiveCharts\Contracts\EngineAdapter;
use Matheusmarnt\LiveCharts\Exceptions\InvalidChartTypeException;
use Matheusmarnt\LiveCharts\Support\ChartPayload;

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
     * @return array<int, string>
     */
    protected function normalizeColors(ChartPayload $payload): array
    {
        return $payload->colors;
    }
}
