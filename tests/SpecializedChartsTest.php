<?php

use Matheusmarnt\LiveCharts\Charts\AreaChart;
use Matheusmarnt\LiveCharts\Charts\BarChart;
use Matheusmarnt\LiveCharts\Charts\BubbleChart;
use Matheusmarnt\LiveCharts\Charts\DonutChart;
use Matheusmarnt\LiveCharts\Charts\HeatmapChart;
use Matheusmarnt\LiveCharts\Charts\LineChart;
use Matheusmarnt\LiveCharts\Charts\PieChart;
use Matheusmarnt\LiveCharts\Charts\RadarChart;
use Matheusmarnt\LiveCharts\Charts\ScatterChart;
use Matheusmarnt\LiveCharts\Facades\LiveCharts;

it('can create specialized charts via facade', function () {
    expect(LiveCharts::line())->toBeInstanceOf(LineChart::class);
    expect(LiveCharts::bar())->toBeInstanceOf(BarChart::class);
    expect(LiveCharts::area())->toBeInstanceOf(AreaChart::class);
    expect(LiveCharts::pie())->toBeInstanceOf(PieChart::class);
    expect(LiveCharts::donut())->toBeInstanceOf(DonutChart::class);
    expect(LiveCharts::radar())->toBeInstanceOf(RadarChart::class);
    expect(LiveCharts::scatter())->toBeInstanceOf(ScatterChart::class);
    expect(LiveCharts::bubble())->toBeInstanceOf(BubbleChart::class);
    expect(LiveCharts::heatmap())->toBeInstanceOf(HeatmapChart::class);
});

it('specialized charts have correct default types', function () {
    expect(LiveCharts::line()->toPayload()['type'])->toBe('line');
    expect(LiveCharts::bar()->toPayload()['type'])->toBe('bar');
    expect(LiveCharts::pie()->toPayload()['type'])->toBe('pie');
    expect(LiveCharts::radar()->toPayload()['type'])->toBe('radar');
    expect(LiveCharts::scatter()->toPayload()['type'])->toBe('scatter');
    expect(LiveCharts::bubble()->toPayload()['type'])->toBe('bubble');
    expect(LiveCharts::heatmap()->toPayload()['type'])->toBe('heatmap');
});
