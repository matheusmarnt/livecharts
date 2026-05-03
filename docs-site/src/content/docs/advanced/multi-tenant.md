---
title: Multi-Tenant
description: Scope chart data per tenant or user.
---

LiveCharts has no opinion on tenancy — it builds whatever data you hand it. The only rule: scope inside the chart definition, not in the Blade view.

## Recommended pattern: class-based + container resolution

```php
namespace App\Charts;

use App\Repositories\OrderRepository;
use Matheusmarnt\LiveCharts\Charts\Chart;
use Matheusmarnt\LiveCharts\Charts\Dataset;

class TenantRevenueChart extends Chart
{
    protected string $type = 'line';

    public function __construct(private OrderRepository $orders) {}

    public function datasets(): array
    {
        return [
            Dataset::make('Revenue')->data(
                $this->orders->forTenant(tenant()->id)->monthly()
            ),
        ];
    }
}
```

Render by class string — LiveCharts resolves it from the container, so the constructor injection picks up the current tenant context:

```blade
<livewire:livecharts :chart="App\Charts\TenantRevenueChart::class" />
```

## Authorization

Class-based charts compose well with policies. Authorize inside `datasets()` or wrap the resolution in a controller:

```php
public function datasets(): array
{
    abort_unless(auth()->user()->can('viewRevenue'), 403);

    return [
        Dataset::make('Revenue')->data($this->orders->monthly()),
    ];
}
```

## Cache keys

Bake the tenant identifier into your cache keys so charts don't leak between tenants:

```php
public function datasets(): array
{
    $data = Cache::remember(
        "tenant.{$this->tenantId}.revenue.monthly",
        now()->addMinutes(5),
        fn () => $this->orders->monthly($this->tenantId)
    );

    return [Dataset::make('Revenue')->data($data)];
}
```

## Pitfall: fluent builder + global state

Avoid the fluent builder when data depends on the request:

```php
// ⚠️ Bad — the controller can be called for any tenant
public function show()
{
    $chart = LiveCharts::line()->dataset('Revenue', Order::all()->pluck('total'));
    return view('dashboard', compact('chart'));
}
```

The fluent builder is fine for static or controller-scoped data, but for per-tenant charts always reach for a class. Class-based charts re-resolve on every request, picking up the current tenant container binding cleanly.
