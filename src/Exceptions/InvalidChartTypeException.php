<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Exceptions;

use Exception;

class InvalidChartTypeException extends Exception
{
    /**
     * @param  list<string>  $supported
     */
    public static function forEngine(string $type, string $engine, array $supported): self
    {
        return new self(trans('livecharts::livecharts.exceptions.invalid_chart_type', [
            'type' => $type,
            'engine' => $engine,
            'supported' => implode(', ', $supported),
        ]));
    }

    public static function forNoEngine(string $type): self
    {
        return new self(trans('livecharts::livecharts.exceptions.no_engine_for_type', [
            'type' => $type,
        ]));
    }
}
