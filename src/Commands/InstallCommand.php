<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\spin;

class InstallCommand extends Command
{
    public $signature = 'livecharts:install';

    public $description = 'Install LiveCharts package assets and configuration';

    public function handle(): int
    {
        info(__('livecharts::livecharts.install.starting'));

        spin(
            fn () => $this->publishConfiguration(),
            __('livecharts::livecharts.install.publishing_assets'),
        );

        $this->publishAssets();
        $this->publishStubs();

        info(__('livecharts::livecharts.install.completed'));

        $this->newLine();
        $this->line('  <comment>Next:</comment> php artisan livecharts:preview');

        return self::SUCCESS;
    }

    protected function publishConfiguration(): void
    {
        $this->call('vendor:publish', [
            '--provider' => "Matheusmarnt\LiveCharts\LiveChartsServiceProvider",
            '--tag' => 'livecharts-config',
        ]);
    }

    protected function publishAssets(): void
    {
        $jsPath = resource_path('js/livecharts.js');

        if (File::exists($jsPath) && ! confirm(label: __('livecharts::livecharts.install.overwrite_js'), default: false)) {
            return;
        }

        spin(function () use ($jsPath) {
            File::ensureDirectoryExists(dirname($jsPath));
            File::copy(__DIR__.'/../../resources/js/livecharts.js', $jsPath);
        }, __('livecharts::livecharts.install.publishing_assets'));

        $this->components->info(__('livecharts::livecharts.install.js_published', ['path' => $jsPath]));
    }

    protected function publishStubs(): void
    {
        if (! confirm(label: __('livecharts::livecharts.install.publish_stubs'), default: false)) {
            return;
        }

        spin(
            fn () => $this->call('vendor:publish', ['--tag' => 'livecharts-stubs']),
            __('livecharts::livecharts.install.publishing_assets'),
        );

        $this->components->info(__('livecharts::livecharts.install.stubs_published', ['path' => base_path('stubs/livecharts')]));
    }
}
