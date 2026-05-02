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
        $list = implode(', ', $supported);

        return new self(
            "Chart type [{$type}] is not supported by engine [{$engine}]. Supported types: {$list}."
        );
    }
}
