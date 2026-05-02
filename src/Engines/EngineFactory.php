<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Engines;

use Matheusmarnt\LiveCharts\Contracts\EngineAdapter;
use Matheusmarnt\LiveCharts\Exceptions\UnknownEngineException;

class EngineFactory
{
    /** @var array<string, class-string<EngineAdapter>> */
    protected static array $engines = [];

    /**
     * Register a new engine adapter.
     *
     * @param  class-string<EngineAdapter>  $adapter
     */
    public static function register(string $name, string $adapter): void
    {
        static::$engines[$name] = $adapter;
    }

    /**
     * Resolve an engine adapter by name.
     *
     * @throws UnknownEngineException
     */
    public static function resolve(string $name): EngineAdapter
    {
        if (! isset(static::$engines[$name])) {
            throw new UnknownEngineException(trans('livecharts::livecharts.exceptions.unknown_engine', [
                'name' => $name,
                'registered' => implode(', ', array_keys(static::$engines)),
            ]));
        }

        $adapter = static::$engines[$name];

        return new $adapter;
    }
}
