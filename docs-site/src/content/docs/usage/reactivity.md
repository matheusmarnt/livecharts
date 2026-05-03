---
title: Reactivity & Polling
description: Make charts react to Livewire state and refresh automatically.
---

LiveCharts is built around Livewire's reactive cycle. Three primitives cover almost every dashboard pattern:

1. **Property binding** — chart re-renders when a Livewire property changes.
2. **Polling** — chart refreshes on an interval without user input.
3. **`livecharts:refreshed` event** — userland hook for custom hydration.

## Property binding

Pass the chart from a Livewire component's `render()` method. Any change to a tracked property re-mounts the chart:

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

The `:key="$range"` directive forces a full re-mount when the range changes, ensuring Alpine state is rebuilt cleanly.

## Polling

Add a polling interval to refresh data automatically:

```php
LiveCharts::line()
    ->dataset('Active sessions', $this->activeSessions())
    ->pollEvery(30); // seconds
```

Or use the static helper for a default interval:

```php
LiveCharts::line()->poll(); // uses config('livecharts.poll_interval')
```

Under the hood, the Livewire component's blade template renders `wire:poll.30s="refresh"`, which calls `LiveChartsComponent::refresh()` and dispatches `livecharts:refreshed` for downstream hooks.

## The `livecharts:refreshed` event

Listen on the Livewire component to hydrate sibling state when the chart refreshes:

```blade
<div x-data x-on:livecharts:refreshed.window="$wire.recomputeKpis()">
    <livewire:livecharts :chart="$chart" />
</div>
```

Or pure Livewire:

```php
#[On('livecharts:refreshed')]
public function recomputeKpis(): void
{
    $this->revenue = $this->orders->total();
}
```

## Lifecycle safety

LiveCharts ships defensive Livewire hooks out of the box:

- `Livewire.hook('morph.updating')` skips chart subtrees so Alpine state is preserved across patches.
- `Livewire.hook('commit.applied')` re-syncs from `$wire.payload` via the soft-update path so options and data stay in sync.
- The Alpine `destroy()` callback nulls the engine instance and removes the global `window.LiveCharts[id]` registry entry to prevent memory leaks.

You don't have to wire any of this — it's handled by the package.
