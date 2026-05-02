<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts;

use Matheusmarnt\LiveCharts\Charts\AreaChart;
use Matheusmarnt\LiveCharts\Charts\BarChart;
use Matheusmarnt\LiveCharts\Charts\BoxPlotChart;
use Matheusmarnt\LiveCharts\Charts\BubbleChart;
use Matheusmarnt\LiveCharts\Charts\CandlestickChart;
use Matheusmarnt\LiveCharts\Charts\DonutChart;
use Matheusmarnt\LiveCharts\Charts\GenericChart;
use Matheusmarnt\LiveCharts\Charts\HeatmapChart;
use Matheusmarnt\LiveCharts\Charts\LineChart;
use Matheusmarnt\LiveCharts\Charts\MatrixChart;
use Matheusmarnt\LiveCharts\Charts\PieChart;
use Matheusmarnt\LiveCharts\Charts\PolarAreaChart;
use Matheusmarnt\LiveCharts\Charts\RadarChart;
use Matheusmarnt\LiveCharts\Charts\RadialBarChart;
use Matheusmarnt\LiveCharts\Charts\RangeBarChart;
use Matheusmarnt\LiveCharts\Charts\SankeyChart;
use Matheusmarnt\LiveCharts\Charts\ScatterChart;
use Matheusmarnt\LiveCharts\Charts\TreemapChart;
use Matheusmarnt\LiveCharts\Contracts\EngineAdapter;
use Matheusmarnt\LiveCharts\Engines\EngineFactory;

class LiveCharts
{
    public function __construct(protected EngineFactory $engines) {}

    /**
     * Register a new engine adapter at runtime.
     *
     * @param  class-string<EngineAdapter>  $adapter
     */
    public function registerEngine(string $name, string $adapter): void
    {
        $this->engines->register($name, $adapter);
    }

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

    public function rangeBar(): RangeBarChart
    {
        return new RangeBarChart;
    }

    public function radialBar(): RadialBarChart
    {
        return new RadialBarChart;
    }

    public function polarArea(): PolarAreaChart
    {
        return new PolarAreaChart;
    }

    public function boxPlot(): BoxPlotChart
    {
        return new BoxPlotChart;
    }

    public function treemap(): TreemapChart
    {
        return new TreemapChart;
    }

    public function candlestick(): CandlestickChart
    {
        return new CandlestickChart;
    }

    public function matrix(): MatrixChart
    {
        return new MatrixChart;
    }

    public function sankey(): SankeyChart
    {
        return new SankeyChart;
    }
}
