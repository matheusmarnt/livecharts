<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Contracts;

use Matheusmarnt\LiveCharts\Support\ChartPayload;

interface EngineAdapter
{
    /**
     * Transform a normalized ChartPayload into engine-native options array.
     *
     * @return array<string, mixed>
     */
    public function build(ChartPayload $payload): array;

    /**
     * Return the JS class name or constructor used by the engine.
     */
    public function jsConstructor(): string;

    /**
     * Return the CDN or NPM package handle for this engine.
     */
    public function assetHandle(): string;

    /**
     * Version constraint for the engine.
     */
    public function version(): string;

    /**
     * Chart types accepted by this engine.
     *
     * @return list<string>
     */
    public function supportedTypes(): array;

    /**
     * Identifier used in the engine registry and error messages.
     */
    public function engineName(): string;
}
