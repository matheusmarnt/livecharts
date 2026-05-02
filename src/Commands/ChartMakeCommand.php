<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Commands;

use Illuminate\Console\GeneratorCommand;
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

    protected function replacePlaceholders($stub, $name): string
    {
        $stub = str_replace(
            ['{{ type }}', '{{ engine }}'],
            [$this->option('type'), $this->option('engine') ?? config('livecharts.engine', 'apexcharts')],
            $stub
        );

        return $stub;
    }

    protected function buildClass($name): string
    {
        $stub = parent::buildClass($name);

        return $this->replacePlaceholders($stub, $name);
    }

    protected function getOptions(): array
    {
        return [
            ['type', 't', InputOption::VALUE_OPTIONAL, 'The type of the chart (line, bar, area, pie, donut, radar, scatter, bubble, heatmap)', 'line'],
            ['engine', 'e', InputOption::VALUE_OPTIONAL, 'The engine of the chart'],
        ];
    }
}
