<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts;

use Matheusmarnt\LiveCharts\Charts\AreaChart;
use Matheusmarnt\LiveCharts\Charts\BarChart;
use Matheusmarnt\LiveCharts\Charts\DonutChart;
use Matheusmarnt\LiveCharts\Charts\GenericChart;
use Matheusmarnt\LiveCharts\Charts\LineChart;
use Matheusmarnt\LiveCharts\Charts\PieChart;

class LiveCharts
{
    public function make(): GenericChart
    {
        return new GenericChart;
    }

    public function line(): LineChart
    {
        return new LineChart;
    }

    public function bar(): BarChart
    {
        return new BarChart;
    }

    public function area(): AreaChart
    {
        return new AreaChart;
    }

    public function pie(): PieChart
    {
        return new PieChart;
    }

    public function donut(): DonutChart
    {
        return new DonutChart;
    }
}
