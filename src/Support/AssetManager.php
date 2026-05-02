<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Support;

class AssetManager
{
    /** @var array<string, bool> */
    protected array $assets = [];

    protected bool $scriptsRendered = false;

    public function registerEngine(string $engine): void
    {
        $this->registerAsset($engine);
    }

    public function registerAsset(string $key): void
    {
        $this->assets[$key] = true;
    }

    public function getRequiredScripts(): array
    {
        $scripts = [];
        $cdn = config('livecharts.assets.cdn', []);

        foreach (array_keys($this->assets) as $key) {
            if (isset($cdn[$key])) {
                $scripts[] = $cdn[$key];
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
