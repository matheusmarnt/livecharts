<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Commands;

use Illuminate\Console\GeneratorCommand;
use Matheusmarnt\LiveCharts\Charts\Chart;
use Matheusmarnt\LiveCharts\Engines\EngineFactory;
use Symfony\Component\Console\Input\InputOption;

class ChartMakeCommand extends GeneratorCommand
{
    protected $name = 'make:chart';

    protected $description = 'Create a new chart class';

    protected $type = 'Chart';

    protected function getStub(): string
    {
        return __DIR__.'/../../resources/stubs/chart.php.stub';
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\Charts';
    }

    protected function replacePlaceholders(string $stub, string $name): string
    {
        $type = $this->option('type');
        $engine = $this->option('engine') ?? config('livecharts.engine', 'apexcharts');

        return str_replace(
            ['{{ type }}', '{{ engine }}'],
            [is_string($type) ? $type : 'line', is_string($engine) ? $engine : 'apexcharts'],
            $stub
        );
    }

    protected function buildClass($name): string
    {
        $stub = parent::buildClass($name);

        return $this->replacePlaceholders($stub, $name);
    }

    /**
     * @return array<int, array<int, mixed>>
     */
    protected function getOptions(): array
    {
        $types = implode(', ', Chart::TYPES);
        $engines = implode(', ', app(EngineFactory::class)->names());

        return [
            ['type', 't', InputOption::VALUE_OPTIONAL, "The type of the chart ({$types})", 'line'],
            ['engine', 'e', InputOption::VALUE_OPTIONAL, "The engine of the chart ({$engines})"],
        ];
    }
}
