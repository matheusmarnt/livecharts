<?php

use Matheusmarnt\LiveCharts\Charts\AreaChart;
use Matheusmarnt\LiveCharts\Charts\BarChart;
use Matheusmarnt\LiveCharts\Charts\DonutChart;
use Matheusmarnt\LiveCharts\Charts\LineChart;
use Matheusmarnt\LiveCharts\Charts\PieChart;
use Matheusmarnt\LiveCharts\Facades\LiveCharts;

it('can create specialized charts via facade', function () {
    expect(LiveCharts::line())->toBeInstanceOf(LineChart::class);
    expect(LiveCharts::bar())->toBeInstanceOf(BarChart::class);
    expect(LiveCharts::area())->toBeInstanceOf(AreaChart::class);
    expect(LiveCharts::pie())->toBeInstanceOf(PieChart::class);
    expect(LiveCharts::donut())->toBeInstanceOf(DonutChart::class);
});

it('specialized charts have correct default types', function () {
    expect(LiveCharts::line()->toPayload()['type'])->toBe('line');
    expect(LiveCharts::bar()->toPayload()['type'])->toBe('bar');
    expect(LiveCharts::pie()->toPayload()['type'])->toBe('pie');
});
