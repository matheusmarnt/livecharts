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

    /**
     * @return list<array{src: string|null, fallback?: string}>
     */
    public function getRequiredScripts(): array
    {
        $scripts = [];
        $cdn = config('livecharts.assets.cdn', []);
        $mode = config('livecharts.assets.mode', 'cdn');

        foreach (array_keys($this->assets) as $key) {
            $localUrl = asset("vendor/livecharts/js/{$key}.js");
            $cdnUrl = $cdn[$key] ?? null;

            if ($mode === 'local') {
                $scripts[] = ['src' => $localUrl];
            } elseif ($mode === 'cdn') {
                $scripts[] = ['src' => $cdnUrl];
            } else {
                // Both/Fallback mode: serve the locally-published asset first
                // and fall back to the CDN copy if the local file is missing.
                $scripts[] = [
                    'src' => $localUrl,
                    'fallback' => $cdnUrl,
                ];
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
