<?php

use Matheusmarnt\LiveCharts\Charts\BoxPlotChart;
use Matheusmarnt\LiveCharts\Charts\BubbleChart;
use Matheusmarnt\LiveCharts\Charts\CandlestickChart;
use Matheusmarnt\LiveCharts\Charts\HeatmapChart;
use Matheusmarnt\LiveCharts\Charts\MatrixChart;
use Matheusmarnt\LiveCharts\Charts\PolarAreaChart;
use Matheusmarnt\LiveCharts\Charts\RadarChart;
use Matheusmarnt\LiveCharts\Charts\RadialBarChart;
use Matheusmarnt\LiveCharts\Charts\RangeBarChart;
use Matheusmarnt\LiveCharts\Charts\SankeyChart;
use Matheusmarnt\LiveCharts\Charts\ScatterChart;
use Matheusmarnt\LiveCharts\Charts\TreemapChart;
use Matheusmarnt\LiveCharts\LiveCharts;

it('factory methods return correct instances', function () {
    $lc = app(LiveCharts::class);

    expect($lc->radar())->toBeInstanceOf(RadarChart::class)
        ->and($lc->scatter())->toBeInstanceOf(ScatterChart::class)
        ->and($lc->bubble())->toBeInstanceOf(BubbleChart::class)
        ->and($lc->heatmap())->toBeInstanceOf(HeatmapChart::class)
        ->and($lc->rangeBar())->toBeInstanceOf(RangeBarChart::class)
        ->and($lc->radialBar())->toBeInstanceOf(RadialBarChart::class)
        ->and($lc->polarArea())->toBeInstanceOf(PolarAreaChart::class)
        ->and($lc->boxPlot())->toBeInstanceOf(BoxPlotChart::class)
        ->and($lc->treemap())->toBeInstanceOf(TreemapChart::class)
        ->and($lc->candlestick())->toBeInstanceOf(CandlestickChart::class)
        ->and($lc->matrix())->toBeInstanceOf(MatrixChart::class)
        ->and($lc->sankey())->toBeInstanceOf(SankeyChart::class);
});
