<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Support;

class AssetManager
{
    /** @var array<string, bool> */
    protected array $engines = [];

    protected bool $scriptsRendered = false;

    public function registerEngine(string $engine): void
    {
        $this->engines[$engine] = true;
    }

    public function getRequiredScripts(): array
    {
        $scripts = [];
        $cdn = config('livecharts.assets.cdn', []);

        foreach (array_keys($this->engines) as $engine) {
            if (isset($cdn[$engine])) {
                $scripts[] = $cdn[$engine];
            }
        }

        return $scripts;
    }

    public function markAsRendered(): void
    {
        $this->scriptsRendered = true;
    }

    public function hasBeenRendered(): bool
    {
        return $this->scriptsRendered;
    }
}
