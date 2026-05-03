---
title: Class-Based Charts
description: Define reusable, testable chart classes.
---

Class-based charts are the recommended approach for any chart that ships to production. They centralise data, encapsulate business logic, and are trivial to test in isolation.

## Generate a chart class

```bash
php artisan make:chart RevenueChart
```

The command writes `app/Charts/RevenueChart.php` using the stub published by `livecharts:install`. Customise the stub to match your conventions — it's just a file under `stubs/livecharts/chart.stub`.

## Anatomy

```php
namespace App\Charts;

use Matheusmarnt\LiveCharts\Charts\Chart;
use Matheusmarnt\LiveCharts\Charts\Dataset;

class RevenueChart extends Chart
{
    protected string $type = 'line';
    protected string $engine = 'apexcharts';

    public function labels(): array
    {
        return ['Jan', 'Feb', 'Mar', 'Apr'];
    }

    public function datasets(): array
    {
        return [
            Dataset::make('Revenue')
                ->data([12000, 19000, 15000, 22000])
                ->color('#10B981'),
        ];
    }

    public function options(): array
    {
        return [
            'stroke' => ['curve' => 'smooth'],
            'yaxis' => ['title' => ['text' => 'USD']],
        ];
    }
}
```

## Render it

Pass the **class string** to the Livewire component — LiveCharts resolves it from the container:

```blade
<livewire:livecharts :chart="App\Charts\RevenueChart::class" />
```

Container resolution unlocks dependency injection. Inject services via the constructor and let LiveCharts wire everything up:

```php
public function __construct(private OrderRepository $orders) {}

public function datasets(): array
{
    return [
        Dataset::make('Revenue')->data($this->orders->monthlyRevenue()),
    ];
}
```

## Per-tenant data

Class-based charts are the right place to scope data to the current tenant or user:

```php
public function datasets(): array
{
    $userId = auth()->id();

    return [
        Dataset::make('My orders')->data(
            $this->orders->forUser($userId)->monthly()
        ),
    ];
}
```

See [Multi-Tenant](../../advanced/multi-tenant/) for the full pattern.

## Testing

```php
use App\Charts\RevenueChart;

it('returns four months of revenue', function () {
    $chart = app(RevenueChart::class);

    expect($chart->datasets()[0]->data())->toHaveCount(4);
});
```
