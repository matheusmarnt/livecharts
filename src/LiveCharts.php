<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts;

use Matheusmarnt\LiveCharts\Charts\AreaChart;
use Matheusmarnt\LiveCharts\Charts\BarChart;
use Matheusmarnt\LiveCharts\Charts\BubbleChart;
use Matheusmarnt\LiveCharts\Charts\DonutChart;
use Matheusmarnt\LiveCharts\Charts\GenericChart;
use Matheusmarnt\LiveCharts\Charts\HeatmapChart;
use Matheusmarnt\LiveCharts\Charts\LineChart;
use Matheusmarnt\LiveCharts\Charts\PieChart;
use Matheusmarnt\LiveCharts\Charts\RadarChart;
use Matheusmarnt\LiveCharts\Charts\ScatterChart;

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

    public function radar(): RadarChart
    {
        return new RadarChart;
    }

    public function scatter(): ScatterChart
    {
        return new ScatterChart;
    }

    public function bubble(): BubbleChart
    {
        return new BubbleChart;
    }

    public function heatmap(): HeatmapChart
    {
        return new HeatmapChart;
    }
}
