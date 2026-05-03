<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Support;

class AssetManager
{
    /** @var array<string, bool> */
    protected array $assets = [];

    /** @var array<string, bool> */
    protected array $pushed = [];

    protected bool $bootstrapPushed = false;

    protected bool $configBridgePushed = false;

    protected bool $scriptsRendered = false;

    protected string $packageRoot;

    public function __construct(?string $packageRoot = null)
    {
        $this->packageRoot = $packageRoot ?? dirname(__DIR__, 2);
    }

    public function registerEngine(string $engine): void
    {
        $this->registerAsset($engine);
    }

    public function registerAsset(string $key): void
    {
        $this->assets[$key] = true;
    }

    public function flushPendingPushes(): void
    {
        $pending = array_diff_key($this->assets, $this->pushed);

        if (empty($pending) && $this->bootstrapPushed) {
            return;
        }

        $html = '';

        foreach (array_keys($pending) as $key) {
            $html .= $this->buildScriptTag($key)."\n";
            $this->pushed[$key] = true;
        }

        if (! $this->bootstrapPushed && config('livecharts.assets.auto_inject', true)) {
            $bootstrap = $this->getBootstrapScript();
            if ($bootstrap !== null && $bootstrap !== '') {
                if (! $this->configBridgePushed) {
                    $strategy = config('livecharts.theme.auto_detect', 'class');
                    $configJs = 'window.LiveChartsConfig=window.LiveChartsConfig||{};window.LiveChartsConfig.themeStrategy='.json_encode($strategy).';';
                    $bootstrap = $configJs."\n".$bootstrap;
                    $this->configBridgePushed = true;
                }
                $html .= "<script>{$bootstrap}</script>\n";
                $this->bootstrapPushed = true;
            }
        }

        if ($html !== '') {
            app('view')->startPush('livecharts-scripts', $html);
        }
    }

    protected function buildScriptTag(string $key): string
    {
        $cdn = config('livecharts.assets.cdn', []);
        $mode = config('livecharts.assets.mode', 'cdn');
        $localUrl = asset("vendor/livecharts/js/{$key}.js");
        $cdnUrl = $cdn[$key] ?? null;

        if ($mode === 'local') {
            return '<script src="'.e($localUrl).'" defer></script>';
        }

        if ($mode === 'cdn') {
            return '<script src="'.e($cdnUrl ?? '').'" defer></script>';
        }

        $fallback = e($cdnUrl ?? '');

        return '<script src="'.e($localUrl)."\" onerror=\"this.onerror=null;this.src='{$fallback}';\" defer></script>";
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

    public function getBootstrapScript(): ?string
    {
        $candidates = [
            $this->packageRoot.'/resources/dist/livecharts.js',
            $this->packageRoot.'/resources/js/livecharts.js',
        ];

        foreach ($candidates as $path) {
            if (is_file($path)) {
                $contents = @file_get_contents($path);

                return $contents === false ? null : $contents;
            }
        }

        return null;
    }
}
