---
title: Drill-Down Pattern
description: Build interactive charts that respond to clicks.
---

A drill-down chart reacts to clicks: tap a bar in the parent chart, and a child chart loads with the underlying detail. LiveCharts ships everything you need — the only code you write is the listener.

## Anatomy

1. Parent chart calls `onDataPointClick('eventName')`.
2. The engine adapter wires a click handler that dispatches `eventName` via Livewire with the clicked index + label.
3. A Livewire `#[On('eventName')]` listener loads the detail data.
4. Re-render the page with the child chart visible.

## Full example

```php
namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;
use Matheusmarnt\LiveCharts\Facades\LiveCharts;

class SalesDashboard extends Component
{
    public ?string $selectedMonth = null;

    public function render()
    {
        $monthly = LiveCharts::bar()
            ->labels(['Jan', 'Feb', 'Mar', 'Apr'])
            ->dataset('Revenue', [12000, 19000, 15000, 22000])
            ->onDataPointClick('drilldown');

        $daily = $this->selectedMonth
            ? LiveCharts::line()
                ->labels($this->dailyLabels($this->selectedMonth))
                ->dataset('Revenue', $this->dailyData($this->selectedMonth))
            : null;

        return view('livewire.sales-dashboard', compact('monthly', 'daily'));
    }

    #[On('drilldown')]
    public function drillInto(int $index, string $label): void
    {
        $this->selectedMonth = $label;
    }
}
```

```blade
<div class="space-y-6">
    <livewire:livecharts :chart="$monthly" />

    @if ($daily)
        <h2>Daily breakdown — {{ $selectedMonth }}</h2>
        <livewire:livecharts :chart="$daily" :key="$selectedMonth" />
    @endif
</div>
```

## Event payload

The dispatched event always has the same shape:

| Argument | Type | Description |
|---|---|---|
| `index` | `int` | Zero-based index of the clicked datapoint. |
| `label` | `string` | The matching label from the chart's `labels()` array. |
| `value` | `float\|int\|null` | The clicked datapoint's value. |
| `dataset` | `string` | Name of the dataset that was clicked. |

Listener signatures can take any subset of these — Livewire injects them by name.

## Tips

- Pass the parent's identifier to the child via `:key="$selectedMonth"` so the child fully re-mounts on each drill.
- For multi-level drill-downs, push the trail onto an array so the user can navigate back up (`$breadcrumbs`).
- For SPA-style routing, dispatch the event from the listener as well so a sibling component (e.g. a side panel) can react too.
