---
title: Broadcasting
description: Push chart updates from server to client over WebSockets.
---

For dashboards where polling isn't fast enough — order tickers, live KPIs, IoT telemetry — broadcasting flips the model. The server pushes; the client refreshes on demand.

## Pattern

1. Broadcast an event when the underlying data changes.
2. Listen for it inside the Livewire component hosting the chart.
3. Refetch + re-render.

## Server side

```php
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;

class RevenueUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets;

    public function broadcastOn(): Channel
    {
        return new Channel("tenant.{$this->tenantId}");
    }
}
```

## Client side (Livewire)

Use Livewire 3's `#[On]` Echo helpers to bind the listener:

```php
namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;
use Matheusmarnt\LiveCharts\Facades\LiveCharts;

class LiveRevenue extends Component
{
    public array $points = [];

    public function mount(): void
    {
        $this->points = $this->loadInitialPoints();
    }

    #[On('echo:tenant.{tenantId},RevenueUpdated')]
    public function refresh(): void
    {
        $this->points = $this->loadInitialPoints();
    }

    public function render()
    {
        $chart = LiveCharts::line()
            ->labels(array_keys($this->points))
            ->dataset('Revenue', array_values($this->points));

        return view('livewire.live-revenue', compact('chart'));
    }
}
```

The `:key` directive guarantees a clean re-mount when the data shape changes:

```blade
<livewire:livecharts :chart="$chart" :key="md5(json_encode($points))" />
```

## When to broadcast vs. poll

| Scenario | Choose |
|---|---|
| Update interval ≥ 30s | Polling — cheaper, simpler. |
| Update interval < 5s | Broadcasting — saves request overhead. |
| Sub-second / push-from-IoT | Broadcasting + a custom JS handler that calls `window.LiveCharts[id].updateSeries()` directly. |

## Direct engine updates (advanced)

If you need surgical updates without a full Livewire round-trip, reach into `window.LiveCharts[id]` and call the engine API directly:

```js
window.Echo.channel(`tenant.${tenantId}`)
    .listen('RevenueUpdated', (event) => {
        const chart = window.LiveCharts['revenue-chart'];
        chart.updateSeries([{ data: event.points }], true);
    });
```

This bypasses Livewire entirely — fast, but you give up the reactive PHP layer. Use it only when latency really matters.
