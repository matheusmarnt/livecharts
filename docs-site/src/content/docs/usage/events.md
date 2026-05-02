---
title: Interactive Events
description: Learn how to handle chart interactions using Livewire events.
---

LiveCharts dispatches standard Livewire events when users interact with the charts.

## Click Events

You can listen for data point clicks by specifying an event name:

```php
$chart->onDataPointClick('chart-clicked');
```

Then, in your parent Livewire component:

```php
use Livewire\Attributes\On;

#[On('chart-clicked')]
public function handleChartClick($data)
{
    // $data contains: seriesIndex, dataPointIndex, value, label
}
```

## Zoom and Selection

```php
$chart->onZoom('chart-zoomed')
      ->onSelection('chart-selected');
```

Both events provide `xaxis` and `yaxis` boundaries in the payload.

## Manual Updates

You can also update charts from Javascript or Alpine.js by dispatching a global event:

```javascript
window.dispatchEvent(new CustomEvent('livecharts:update:chart-id', {
    detail: {
        payload: { /* new payload */ },
        options: { /* new engine options */ }
    }
}));
```
