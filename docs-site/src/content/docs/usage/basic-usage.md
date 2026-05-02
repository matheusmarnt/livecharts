---
title: Basic Usage
description: Learn how to define and render your first chart.
---

LiveCharts provides two primary ways to define charts: a fluent builder and class-based definitions.

## Fluent Builder

The fluent builder is ideal for quick, simple charts.

```php
use Matheusmarnt\LiveCharts\Facades\LiveCharts;

$chart = LiveCharts::line()
    ->title('Total Users')
    ->labels(['Jan', 'Feb', 'Mar'])
    ->dataset('Current Year', [100, 200, 150])
    ->colors(['#3B82F6']);
```

In your Blade view:

```blade
<livewire:livecharts :chart="$chart" />
```

## Class-Based Charts

For reusability and testability, you should define your charts as PHP classes.

```php
namespace App\Charts;

use Matheusmarnt\LiveCharts\Charts\Chart;
use Matheusmarnt\LiveCharts\Charts\Dataset;

class RevenueChart extends Chart
{
    protected string $type = 'line';

    public function datasets(): array
    {
        return [
            Dataset::make('Revenue')
                ->data([1200, 1900, 3000])
                ->color('#10B981'),
        ];
    }
}
```

## Supported Types

You can use specialized factory methods or the `type()` method:

- `LiveCharts::line()`
- `LiveCharts::bar()`
- `LiveCharts::area()`
- `LiveCharts::pie()`
- `LiveCharts::donut()`
- `LiveCharts::radar()`
- `LiveCharts::scatter()`
- `LiveCharts::bubble()`
- `LiveCharts::heatmap()`
- `LiveCharts::treemap()`
- `LiveCharts::candlestick()`
- `LiveCharts::rangeBar()`
- `LiveCharts::radialBar()`
- `LiveCharts::polarArea()`
