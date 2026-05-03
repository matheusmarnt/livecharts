<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Engines;

use Matheusmarnt\LiveCharts\Contracts\EngineAdapter;
use Matheusmarnt\LiveCharts\Exceptions\InvalidChartTypeException;
use Matheusmarnt\LiveCharts\Exceptions\UnknownEngineException;

class EngineFactory
{
    /** @var array<string, class-string<EngineAdapter>> */
    protected array $engines = [];

    /**
     * Register a new engine adapter.
     *
     * @param  class-string<EngineAdapter>  $adapter
     */
    public function register(string $name, string $adapter): void
    {
        $this->engines[$name] = $adapter;
    }

    /**
     * Resolve an engine adapter by name.
     *
     * @throws UnknownEngineException
     */
    public function resolve(string $name): EngineAdapter
    {
        if (! isset($this->engines[$name])) {
            throw new UnknownEngineException(trans('livecharts::livecharts.exceptions.unknown_engine', [
                'name' => $name,
                'registered' => implode(', ', array_keys($this->engines)),
            ]));
        }

        $adapter = $this->engines[$name];

        return new $adapter;
    }

    /**
     * Get the names of all registered engines.
     *
     * @return array<int, string>
     */
    public function names(): array
    {
        return array_keys($this->engines);
    }

    /**
     * Select the best engine name for a given chart type.
     *
     * Rules: if only one registered engine supports the type, use it.
     * If multiple engines support it, prefer 'apexcharts'.
     *
     * @throws InvalidChartTypeException when no registered engine supports the type
     */
    public function engineForType(string $type): string
    {
        $candidates = [];

        foreach ($this->engines as $name => $adapterClass) {
            $adapter = new $adapterClass;
            if (in_array($type, $adapter->supportedTypes(), true)) {
                $candidates[] = $name;
            }
        }

        if ($candidates === []) {
            throw InvalidChartTypeException::forNoEngine($type);
        }

        if (in_array('apexcharts', $candidates, true)) {
            return 'apexcharts';
        }

        return $candidates[0];
    }

    /**
     * Return all engine names that support a given chart type.
     *
     * @return array<int, string>
     */
    public function availableEnginesForType(string $type): array
    {
        $candidates = [];

        foreach ($this->engines as $name => $adapterClass) {
            $adapter = new $adapterClass;
            if (in_array($type, $adapter->supportedTypes(), true)) {
                $candidates[] = $name;
            }
        }

        return $candidates;
    }
}
