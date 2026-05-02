<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Exceptions;

use Exception;

class DataShapeMismatchException extends Exception
{
    public static function forDataset(string $datasetName, int $expected, int $actual): self
    {
        return new self(
            "Dataset [{$datasetName}] data count mismatch: expected {$expected} points (matching labels), got {$actual}."
        );
    }
}
