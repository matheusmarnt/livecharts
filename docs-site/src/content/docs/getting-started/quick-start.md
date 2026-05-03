---
title: Quick Start
description: Render your first chart in under five minutes.
---

Already installed? Let's render your first chart.

## 1. Define a chart

The fastest path uses the fluent builder:

```php
use Matheusmarnt\LiveCharts\Facades\LiveCharts;

class DashboardController
{
    public function __invoke()
    {
        $chart = LiveCharts::line()
            ->title('Sign-ups this week')
            ->labels(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'])
            ->dataset('New Users', [12, 19, 15, 28, 22, 30, 18])
            ->colors(['#10B981']);

        return view('dashboard', compact('chart'));
    }
}
```

## 2. Render it in Blade

```blade
<x-app-layout>
    <div class="p-6">
        <livewire:livecharts :chart="$chart" />
    </div>
</x-app-layout>
```

That's it — the chart renders, hydrates Alpine, registers cleanup hooks, and survives Livewire morph cycles.

## 3. Add reactivity (optional)

Bind the chart to Livewire properties to make it react to user input:

```php
namespace App\Livewire;

use Livewire\Component;
use Matheusmarnt\LiveCharts\Facades\LiveCharts;

class SalesDashboard extends Component
{
    public string $range = '7d';

    public function render()
    {
        $chart = LiveCharts::line()
            ->labels($this->labelsFor($this->range))
            ->dataset('Sales', $this->dataFor($this->range));

        return view('livewire.sales-dashboard', compact('chart'));
    }
}
```

```blade
<div>
    <select wire:model.live="range">
        <option value="7d">Last 7 days</option>
        <option value="30d">Last 30 days</option>
    </select>

    <livewire:livecharts :chart="$chart" :key="$range" />
</div>
```

The `:key` prop ensures the chart fully re-mounts when the range changes.

## 4. Make it live

Add polling to refresh data automatically:

```php
LiveCharts::line()
    ->dataset('Active sessions', $this->activeSessions())
    ->pollEvery(30); // every 30 seconds
```

## Next steps

- Read about [class-based charts](../../usage/class-based/) for reusable definitions.
- Wire up [interactive events](../../usage/events/) for drill-downs.
- Switch engines on the [Engines overview](../../engines/overview/).
