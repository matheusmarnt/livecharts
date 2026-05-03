---
title: Livewire & Volt Examples
description: Complete examples using LiveCharts inside Livewire v3/v4 class components and Volt functional components.
---

import { Tabs, TabItem } from '@astrojs/starlight/components';

Every LiveCharts chart runs inside a `<livewire:livecharts />` child component. The examples below show how to wire it up from a **Livewire class component** (v3/v4) and from a **Volt functional component** side-by-side.

---

## Basic chart in a Livewire page

<Tabs>
<TabItem label="Livewire class">

```php
// app/Livewire/SalesDashboard.php
namespace App\Livewire;

use Livewire\Component;
use Matheusmarnt\LiveCharts\Facades\LiveCharts;
use Matheusmarnt\LiveCharts\Enums\{TwColor, TwPalette};

class SalesDashboard extends Component
{
    public function render()
    {
        $chart = LiveCharts::bar()
            ->title('Monthly Sales')
            ->labels(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'])
            ->dataset('2026', [1200, 1900, 1500, 2200, 1800, 2600])
            ->palette(TwPalette::Vibrant)
            ->titleColor(dark: TwColor::Slate100, light: TwColor::Slate900)
            ->gridColor(dark: TwColor::Slate800, light: TwColor::Slate200);

        return view('livewire.sales-dashboard', compact('chart'));
    }
}
```

```blade
{{-- resources/views/livewire/sales-dashboard.blade.php --}}
<div>
    <livewire:livecharts :chart="$chart" />
</div>
```

</TabItem>
<TabItem label="Volt">

```php
{{-- resources/views/livewire/sales-dashboard.blade.php --}}
<?php

use function Livewire\Volt\{computed};
use Matheusmarnt\LiveCharts\Facades\LiveCharts;
use Matheusmarnt\LiveCharts\Enums\{TwColor, TwPalette};

$chart = computed(fn () =>
    LiveCharts::bar()
        ->title('Monthly Sales')
        ->labels(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'])
        ->dataset('2026', [1200, 1900, 1500, 2200, 1800, 2600])
        ->palette(TwPalette::Vibrant)
        ->titleColor(dark: TwColor::Slate100, light: TwColor::Slate900)
        ->gridColor(dark: TwColor::Slate800, light: TwColor::Slate200)
);

?>

<div>
    <livewire:livecharts :chart="$this->chart" />
</div>
```

</TabItem>
</Tabs>

---

## Reactive filter (property binding)

The chart re-renders when a Livewire property changes. Use `:key` to force a clean re-mount.

<Tabs>
<TabItem label="Livewire class">

```php
namespace App\Livewire;

use Livewire\Component;
use Matheusmarnt\LiveCharts\Facades\LiveCharts;
use Matheusmarnt\LiveCharts\Enums\TwColor;

class RevenueChart extends Component
{
    public string $period = '30d';
    public string $engine = 'apexcharts';

    public function render()
    {
        $chart = LiveCharts::line()
            ->engine($this->engine)
            ->title('Revenue — '.$this->period)
            ->labels($this->labels())
            ->dataset('Revenue', $this->data())
            ->titleColor(dark: TwColor::Amber300, light: TwColor::Amber700)
            ->gridColor(dark: TwColor::Slate800, light: TwColor::Slate200)
            ->tooltipColor(dark: TwColor::White, light: TwColor::Slate900);

        return view('livewire.revenue-chart', compact('chart'));
    }

    private function labels(): array
    {
        return match ($this->period) {
            '7d'  => ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            '30d' => range(1, 30),
            default => range(1, 90),
        };
    }

    private function data(): array
    {
        return array_map(fn () => rand(800, 3000), $this->labels());
    }
}
```

```blade
<div>
    <div class="flex gap-4 mb-4">
        <select wire:model.live="period">
            <option value="7d">Last 7 days</option>
            <option value="30d">Last 30 days</option>
            <option value="90d">Last 90 days</option>
        </select>

        <select wire:model.live="engine">
            <option value="apexcharts">ApexCharts</option>
            <option value="chartjs">Chart.js</option>
        </select>
    </div>

    <livewire:livecharts :chart="$chart" :key="$period.'-'.$engine" />
</div>
```

</TabItem>
<TabItem label="Volt">

```php
<?php

use function Livewire\Volt\{state, computed};
use Matheusmarnt\LiveCharts\Facades\LiveCharts;
use Matheusmarnt\LiveCharts\Enums\TwColor;

state(['period' => '30d', 'engine' => 'apexcharts']);

$chart = computed(fn () =>
    LiveCharts::line()
        ->engine($this->engine)
        ->title('Revenue — '.$this->period)
        ->labels(range(1, (int) $this->period))
        ->dataset('Revenue', array_map(fn () => rand(800, 3000), range(1, (int) $this->period)))
        ->titleColor(dark: TwColor::Amber300, light: TwColor::Amber700)
        ->gridColor(dark: TwColor::Slate800, light: TwColor::Slate200)
        ->tooltipColor(dark: TwColor::White, light: TwColor::Slate900)
);

?>

<div>
    <div class="flex gap-4 mb-4">
        <select wire:model.live="period">
            <option value="7d">Last 7 days</option>
            <option value="30d">Last 30 days</option>
            <option value="90d">Last 90 days</option>
        </select>

        <select wire:model.live="engine">
            <option value="apexcharts">ApexCharts</option>
            <option value="chartjs">Chart.js</option>
        </select>
    </div>

    <livewire:livecharts :chart="$this->chart" :key="$period.'-'.$engine" />
</div>
```

</TabItem>
</Tabs>

---

## Auto-refresh with polling

`pollEvery()` adds `wire:poll` to the chart component. The `livecharts:refreshed` event fires each cycle — listen on the parent to hydrate sibling state.

<Tabs>
<TabItem label="Livewire class">

```php
namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;
use Matheusmarnt\LiveCharts\Facades\LiveCharts;
use Matheusmarnt\LiveCharts\Enums\TwColor;

class ActiveSessions extends Component
{
    public int $total = 0;

    public function mount(): void
    {
        $this->total = $this->fetchSessions();
    }

    #[On('livecharts:refreshed')]
    public function refresh(): void
    {
        $this->total = $this->fetchSessions();
    }

    public function render()
    {
        $chart = LiveCharts::line()
            ->title('Active Sessions')
            ->labels(array_map(fn ($i) => now()->subMinutes(9 - $i)->format('H:i'), range(0, 9)))
            ->dataset('Sessions', $this->fetchHistory())
            ->pollEvery(30)
            ->gridColor(dark: TwColor::Slate800, light: TwColor::Slate200)
            ->labelsColor(dark: TwColor::Slate400, light: TwColor::Slate600);

        return view('livewire.active-sessions', compact('chart'));
    }

    private function fetchSessions(): int
    {
        return rand(100, 500); // replace with real query
    }

    private function fetchHistory(): array
    {
        return array_map(fn () => rand(100, 500), range(0, 9));
    }
}
```

```blade
<div>
    <p class="text-2xl font-bold">{{ $total }} active now</p>
    <livewire:livecharts :chart="$chart" />
</div>
```

</TabItem>
<TabItem label="Volt">

```php
<?php

use function Livewire\Volt\{state, computed, mount, on};
use Matheusmarnt\LiveCharts\Facades\LiveCharts;
use Matheusmarnt\LiveCharts\Enums\TwColor;

state(['total' => 0]);

mount(function () {
    $this->total = rand(100, 500);
});

on(['livecharts:refreshed' => function () {
    $this->total = rand(100, 500);
}]);

$chart = computed(fn () =>
    LiveCharts::line()
        ->title('Active Sessions')
        ->labels(array_map(fn ($i) => now()->subMinutes(9 - $i)->format('H:i'), range(0, 9)))
        ->dataset('Sessions', array_map(fn () => rand(100, 500), range(0, 9)))
        ->pollEvery(30)
        ->gridColor(dark: TwColor::Slate800, light: TwColor::Slate200)
        ->labelsColor(dark: TwColor::Slate400, light: TwColor::Slate600)
);

?>

<div>
    <p class="text-2xl font-bold">{{ $total }} active now</p>
    <livewire:livecharts :chart="$this->chart" />
</div>
```

</TabItem>
</Tabs>

---

## Click events + drill-down

<Tabs>
<TabItem label="Livewire class">

```php
namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;
use Matheusmarnt\LiveCharts\Facades\LiveCharts;
use Matheusmarnt\LiveCharts\Enums\TwColor;

class SalesDrillDown extends Component
{
    public ?string $selected = null;
    public array $detail = [];

    public function render()
    {
        $overview = LiveCharts::bar()
            ->title('Sales by Region')
            ->labels(['North', 'South', 'East', 'West'])
            ->dataset('Q1', [4200, 3100, 5600, 2900])
            ->onDataPointClick('region-selected')
            ->titleColor(dark: TwColor::Slate100, light: TwColor::Slate900);

        return view('livewire.sales-drill-down', compact('overview'));
    }

    #[On('region-selected')]
    public function drillDown(array $data): void
    {
        $this->selected = $data['label'];
        $this->detail   = $this->detailFor($data['label']);
    }

    private function detailFor(string $region): array
    {
        return array_map(fn () => rand(200, 1800), range(1, 3));
    }
}
```

```blade
<div>
    <livewire:livecharts :chart="$overview" />

    @if ($selected)
        <p class="mt-4 font-semibold">{{ $selected }} breakdown</p>
        <livewire:livecharts
            :chart="LiveCharts::bar()
                ->title($selected)
                ->labels(['Product A', 'Product B', 'Product C'])
                ->dataset('Sales', $detail)"
            :key="$selected"
        />
    @endif
</div>
```

</TabItem>
<TabItem label="Volt">

```php
<?php

use function Livewire\Volt\{state, computed, on};
use Matheusmarnt\LiveCharts\Facades\LiveCharts;
use Matheusmarnt\LiveCharts\Enums\TwColor;

state(['selected' => null, 'detail' => []]);

on(['region-selected' => function (array $data) {
    $this->selected = $data['label'];
    $this->detail   = array_map(fn () => rand(200, 1800), range(1, 3));
}]);

$overview = computed(fn () =>
    LiveCharts::bar()
        ->title('Sales by Region')
        ->labels(['North', 'South', 'East', 'West'])
        ->dataset('Q1', [4200, 3100, 5600, 2900])
        ->onDataPointClick('region-selected')
        ->titleColor(dark: TwColor::Slate100, light: TwColor::Slate900)
);

$detail = computed(fn () =>
    $this->selected
        ? LiveCharts::bar()
            ->title($this->selected)
            ->labels(['Product A', 'Product B', 'Product C'])
            ->dataset('Sales', $this->detail)
        : null
);

?>

<div>
    <livewire:livecharts :chart="$this->overview" />

    @if ($this->selected)
        <p class="mt-4 font-semibold">{{ $this->selected }} breakdown</p>
        <livewire:livecharts :chart="$this->detail" :key="$this->selected" />
    @endif
</div>
```

</TabItem>
</Tabs>

---

## Class-based chart with dependency injection

Class-based charts support Laravel's container — inject repositories, services, or configs directly.

<Tabs>
<TabItem label="Livewire class">

```php
// app/Charts/RevenueChart.php
namespace App\Charts;

use App\Repositories\OrderRepository;
use Matheusmarnt\LiveCharts\Charts\Chart;
use Matheusmarnt\LiveCharts\Charts\Dataset;
use Matheusmarnt\LiveCharts\Enums\{TwColor, TwPalette};

class RevenueChart extends Chart
{
    protected string $type = 'line';

    public function __construct(private OrderRepository $orders) {}

    public function labels(): array
    {
        return $this->orders->months(6);
    }

    public function datasets(): array
    {
        return [
            Dataset::make('Direct')
                ->data($this->orders->directRevenue(6))
                ->backgroundColor(dark: TwColor::Emerald400, light: TwColor::Emerald600)
                ->borderColor(dark: TwColor::Emerald300, light: TwColor::Emerald700),

            Dataset::make('Affiliate')
                ->data($this->orders->affiliateRevenue(6))
                ->backgroundColor(dark: TwColor::Sky400, light: TwColor::Sky600)
                ->borderColor(dark: TwColor::Sky300, light: TwColor::Sky700),
        ];
    }
}
```

```php
// app/Livewire/RevenuePage.php
namespace App\Livewire;

use Livewire\Component;

class RevenuePage extends Component
{
    public function render()
    {
        return view('livewire.revenue-page');
    }
}
```

```blade
{{-- resolve from container — DI applied automatically --}}
<livewire:livecharts :chart="App\Charts\RevenueChart::class" />
```

</TabItem>
<TabItem label="Volt">

```php
<?php

use function Livewire\Volt\{computed};
use App\Charts\RevenueChart;

// Volt resolves the class string from the container just like Livewire does
$chart = computed(fn () => RevenueChart::class);

?>

<div>
    <livewire:livecharts :chart="$this->chart" />
</div>
```

:::tip
Pass the **class string** (not an instance) when using DI-wired chart classes. LiveCharts resolves it via `app(RevenueChart::class)`, applying constructor injection automatically.
:::

</TabItem>
</Tabs>

---

## Multi-chart dashboard

<Tabs>
<TabItem label="Livewire class">

```php
namespace App\Livewire;

use Livewire\Component;
use Matheusmarnt\LiveCharts\Facades\LiveCharts;
use Matheusmarnt\LiveCharts\Enums\{TwColor, TwPalette};

class Dashboard extends Component
{
    public string $period = '30d';

    public function render()
    {
        $sharedColors = [
            'title'  => [dark: TwColor::Slate100, light: TwColor::Slate900],
            'legend' => [dark: TwColor::Slate300, light: TwColor::Slate700],
            'grid'   => [dark: TwColor::Slate800, light: TwColor::Slate200],
        ];

        $revenue = LiveCharts::area()
            ->title('Revenue')
            ->labels($this->months())
            ->dataset('MRR', [4200, 5100, 4800, 6200, 5900, 7100])
            ->palette(TwPalette::Vibrant)
            ->titleColor(...$sharedColors['title'])
            ->gridColor(...$sharedColors['grid']);

        $users = LiveCharts::bar()
            ->title('New Users')
            ->labels($this->months())
            ->dataset('Signups', [120, 180, 140, 220, 195, 260])
            ->palette(TwPalette::Muted)
            ->titleColor(...$sharedColors['title'])
            ->gridColor(...$sharedColors['grid']);

        $churn = LiveCharts::donut()
            ->title('Churn Reasons')
            ->labels(['Price', 'Support', 'Features', 'Other'])
            ->dataset('Churn', [42, 18, 25, 15])
            ->palette(TwPalette::Pastel);

        return view('livewire.dashboard', compact('revenue', 'users', 'churn'));
    }

    private function months(): array
    {
        return ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
    }
}
```

```blade
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="lg:col-span-2">
        <livewire:livecharts :chart="$revenue" />
    </div>
    <livewire:livecharts :chart="$users" />
    <livewire:livecharts :chart="$churn" />
</div>
```

</TabItem>
<TabItem label="Volt">

```php
<?php

use function Livewire\Volt\{state, computed};
use Matheusmarnt\LiveCharts\Facades\LiveCharts;
use Matheusmarnt\LiveCharts\Enums\{TwColor, TwPalette};

state(['period' => '30d']);

$months = computed(fn () => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']);

$revenue = computed(fn () =>
    LiveCharts::area()
        ->title('Revenue')
        ->labels($this->months)
        ->dataset('MRR', [4200, 5100, 4800, 6200, 5900, 7100])
        ->palette(TwPalette::Vibrant)
        ->titleColor(dark: TwColor::Slate100, light: TwColor::Slate900)
        ->gridColor(dark: TwColor::Slate800, light: TwColor::Slate200)
);

$users = computed(fn () =>
    LiveCharts::bar()
        ->title('New Users')
        ->labels($this->months)
        ->dataset('Signups', [120, 180, 140, 220, 195, 260])
        ->palette(TwPalette::Muted)
        ->titleColor(dark: TwColor::Slate100, light: TwColor::Slate900)
        ->gridColor(dark: TwColor::Slate800, light: TwColor::Slate200)
);

$churn = computed(fn () =>
    LiveCharts::donut()
        ->title('Churn Reasons')
        ->labels(['Price', 'Support', 'Features', 'Other'])
        ->dataset('Churn', [42, 18, 25, 15])
        ->palette(TwPalette::Pastel)
);

?>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="lg:col-span-2">
        <livewire:livecharts :chart="$this->revenue" />
    </div>
    <livewire:livecharts :chart="$this->users" />
    <livewire:livecharts :chart="$this->churn" />
</div>
```

</TabItem>
</Tabs>

---

## Broadcasting (real-time push)

<Tabs>
<TabItem label="Livewire class">

```php
$chart = LiveCharts::line()
    ->title('Live Orders')
    ->labels(['12:00', '12:05', '12:10', '12:15', '12:20'])
    ->dataset('Orders', [12, 18, 14, 22, 19])
    ->broadcastOn('private-dashboard.'.$team->id)
    ->broadcastAs('orders.updated');
```

When the server dispatches `orders.updated` on the channel, the chart re-renders automatically via Laravel Echo.

</TabItem>
<TabItem label="Volt">

```php
<?php

use function Livewire\Volt\{state, computed, mount};
use Matheusmarnt\LiveCharts\Facades\LiveCharts;

state(['teamId' => null]);

mount(function () {
    $this->teamId = auth()->user()->team_id;
});

$chart = computed(fn () =>
    LiveCharts::line()
        ->title('Live Orders')
        ->labels(['12:00', '12:05', '12:10', '12:15', '12:20'])
        ->dataset('Orders', [12, 18, 14, 22, 19])
        ->broadcastOn('private-dashboard.'.$this->teamId)
        ->broadcastAs('orders.updated')
);

?>

<div>
    <livewire:livecharts :chart="$this->chart" />
</div>
```

</TabItem>
</Tabs>

---

## Key patterns

| Pattern | Livewire class | Volt |
|---|---|---|
| Reactive state | `public $prop;` → `wire:model` | `state(['prop' => ...])` → `wire:model` |
| Computed chart | Build in `render()` | `computed(fn () => ...)` |
| Init logic | `mount()` method | `mount(fn () => ...)` |
| Event listener | `#[On('event')]` on method | `on(['event' => fn () => ...])` |
| Access chart in template | `$chart` | `$this->chart` |
| Force re-mount | `:key="$prop"` | `:key="$prop"` (same) |
| DI chart class | Pass class string | Pass class string |
