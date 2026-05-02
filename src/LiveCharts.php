<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts;

use Matheusmarnt\LiveCharts\Charts\GenericChart;

class LiveCharts
{
    public function make(): GenericChart
    {
        return new GenericChart;
    }
}
