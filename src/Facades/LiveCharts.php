<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Matheusmarnt\LiveCharts\LiveCharts
 *
 * @method static void registerEngine(string $name, class-string<\Matheusmarnt\LiveCharts\Contracts\EngineAdapter> $adapter)
 * @method static \Matheusmarnt\LiveCharts\Charts\GenericChart make()
 * @method static \Matheusmarnt\LiveCharts\Charts\LineChart line()
 * @method static \Matheusmarnt\LiveCharts\Charts\BarChart bar()
 * @method static \Matheusmarnt\LiveCharts\Charts\AreaChart area()
 * @method static \Matheusmarnt\LiveCharts\Charts\PieChart pie()
 * @method static \Matheusmarnt\LiveCharts\Charts\DonutChart donut()
 * @method static \Matheusmarnt\LiveCharts\Charts\RadarChart radar()
 * @method static \Matheusmarnt\LiveCharts\Charts\ScatterChart scatter()
 * @method static \Matheusmarnt\LiveCharts\Charts\BubbleChart bubble()
 * @method static \Matheusmarnt\LiveCharts\Charts\HeatmapChart heatmap()
 * @method static \Matheusmarnt\LiveCharts\Charts\RangeBarChart rangeBar()
 * @method static \Matheusmarnt\LiveCharts\Charts\RadialBarChart radialBar()
 * @method static \Matheusmarnt\LiveCharts\Charts\PolarAreaChart polarArea()
 * @method static \Matheusmarnt\LiveCharts\Charts\BoxPlotChart boxPlot()
 * @method static \Matheusmarnt\LiveCharts\Charts\TreemapChart treemap()
 * @method static \Matheusmarnt\LiveCharts\Charts\CandlestickChart candlestick()
 * @method static \Matheusmarnt\LiveCharts\Charts\MatrixChart matrix()
 * @method static \Matheusmarnt\LiveCharts\Charts\SankeyChart sankey()
 */
class LiveCharts extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Matheusmarnt\LiveCharts\LiveCharts::class;
    }
}
