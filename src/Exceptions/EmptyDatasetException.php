<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Exceptions;

use Exception;

class EmptyDatasetException extends Exception
{
    public static function forChart(string $chartType): self
    {
        return new self(trans('livecharts::livecharts.exceptions.empty_dataset_chart', [
            'type' => $chartType,
        ]));
    }

    public static function forDataset(string $name): self
    {
        return new self(trans('livecharts::livecharts.exceptions.empty_dataset_named', [
            'name' => $name,
        ]));
    }
}
