<?php

declare(strict_types=1);

use Illuminate\Console\Command;
use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Facades\Facade;
use Livewire\Component;
use Matheusmarnt\LiveCharts\Charts\Chart;
use Matheusmarnt\LiveCharts\Charts\Dataset;
use Matheusmarnt\LiveCharts\Contracts\EngineAdapter;
use Matheusmarnt\LiveCharts\Engines\ApexChartsAdapter;
use Matheusmarnt\LiveCharts\Engines\BaseEngineAdapter;
use Matheusmarnt\LiveCharts\Engines\ChartJsAdapter;
use Matheusmarnt\LiveCharts\Engines\EngineFactory;
use Spatie\LaravelPackageTools\PackageServiceProvider;

arch('debug helpers must not ship')
    ->expect(['dd', 'dump', 'ray', 'var_dump', 'print_r'])
    ->each->not->toBeUsed();

arch('source declares strict types')
    ->expect('Matheusmarnt\\LiveCharts')
    ->toUseStrictTypes();

arch('contracts are interfaces')
    ->expect('Matheusmarnt\\LiveCharts\\Contracts')
    ->toBeInterfaces();

arch('exceptions extend \\Exception and use Exception suffix')
    ->expect('Matheusmarnt\\LiveCharts\\Exceptions')
    ->toBeClasses()
    ->toExtend(Exception::class)
    ->toHaveSuffix('Exception');

arch('chart classes extend Chart abstract')
    ->expect('Matheusmarnt\\LiveCharts\\Charts')
    ->classes()
    ->toExtend(Chart::class)
    ->ignoring([Chart::class, Dataset::class]);

arch('engine adapters extend BaseEngineAdapter and implement contract')
    ->expect([ApexChartsAdapter::class, ChartJsAdapter::class])
    ->toExtend(BaseEngineAdapter::class)
    ->toImplement(EngineAdapter::class);

arch('EngineFactory is a plain class — not an adapter')
    ->expect(EngineFactory::class)
    ->toBeClass()
    ->not->toExtend(BaseEngineAdapter::class);

arch('BaseEngineAdapter implements EngineAdapter contract')
    ->expect(BaseEngineAdapter::class)
    ->toImplement(EngineAdapter::class);

arch('commands extend Laravel Command base')
    ->expect('Matheusmarnt\\LiveCharts\\Commands')
    ->classes()
    ->toExtend(Command::class);

arch('facade extends Laravel Facade')
    ->expect('Matheusmarnt\\LiveCharts\\Facades')
    ->classes()
    ->toExtend(Facade::class);

arch('livewire components extend Component')
    ->expect('Matheusmarnt\\LiveCharts\\Livewire')
    ->classes()
    ->toExtend(Component::class);

arch('service provider extends Spatie PackageServiceProvider')
    ->expect(\Matheusmarnt\LiveCharts\LiveChartsServiceProvider::class)
    ->toExtend(PackageServiceProvider::class);

arch('GeneratorCommand stays available for chart make command')
    ->expect(GeneratorCommand::class)
    ->toBeClass();

arch('charts namespace has no public mutable state via implements clause')
    ->expect('Matheusmarnt\\LiveCharts\\Charts')
    ->classes()
    ->not->toBeFinal()
    ->ignoring([Dataset::class]);

arch('engines do not depend on Charts namespace concretes')
    ->expect('Matheusmarnt\\LiveCharts\\Engines')
    ->not->toUse('Matheusmarnt\\LiveCharts\\Charts');

arch('contracts depend on nothing in Engines namespace')
    ->expect('Matheusmarnt\\LiveCharts\\Contracts')
    ->not->toUse('Matheusmarnt\\LiveCharts\\Engines');

arch('exceptions stay leaf — never used by other exceptions')
    ->expect('Matheusmarnt\\LiveCharts\\Exceptions')
    ->not->toUse('Matheusmarnt\\LiveCharts\\Exceptions');
