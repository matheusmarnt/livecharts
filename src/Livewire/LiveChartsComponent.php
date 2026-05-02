<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Livewire;

use Livewire\Component;
use Matheusmarnt\LiveCharts\Contracts\ChartContract;
use Matheusmarnt\LiveCharts\Engines\EngineFactory;
use Matheusmarnt\LiveCharts\Support\ChartPayload;
use Illuminate\Contracts\View\View;

class LiveChartsComponent extends Component
{
    public array $payload;

    public string $id;

    public string $class = '';

    public function mount(ChartContract $chart, ?string $id = null, string $class = ''): void
    {
        $this->payload = $chart->toPayload();
        $this->id = $id ?? 'chart-' . uniqid();
        $this->class = $class;
    }

    public function render(): View
    {
        $adapter = EngineFactory::resolve($this->payload['engine']);
        $options = $adapter->build(new ChartPayload(...$this->payload));

        return view('livecharts::livewire.livecharts', [
            'options' => $options,
            'adapter' => $adapter,
        ]);
    }
}
