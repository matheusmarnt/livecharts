<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Exceptions;

use Exception;

class EmptyDatasetException extends Exception
{
    public static function forChart(string $chartType): self
    {
        return new self(
            "Chart of type [{$chartType}] has no datasets. Add at least one dataset via dataset() or datasets()."
        );
    }

    public static function forDataset(string $name): self
    {
        return new self(
            "Dataset [{$name}] has no data points. Provide values via Dataset::data()."
        );
    }
}
