<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    public $signature = 'livecharts:install';

    public $description = 'Install LiveCharts package assets and configuration';

    public function handle(): int
    {
        $this->info('Installing LiveCharts...');

        $this->publishConfiguration();
        $this->publishAssets();

        $this->info('LiveCharts installed successfully.');

        return self::SUCCESS;
    }

    protected function publishConfiguration(): void
    {
        $this->call('vendor:publish', [
            '--provider' => "Matheusmarnt\LiveCharts\LiveChartsServiceProvider",
            '--tag' => "livecharts-config",
        ]);
    }

    protected function publishAssets(): void
    {
        $this->info('Publishing assets...');
        
        $jsPath = resource_path('js/livecharts.js');
        
        if (File::exists($jsPath) && ! $this->confirm('livecharts.js already exists. Overwrite?', false)) {
            return;
        }

        File::ensureDirectoryExists(dirname($jsPath));
        File::copy(__DIR__.'/../../resources/js/livecharts.js', $jsPath);

        $this->info("Assets published to [{$jsPath}]");
    }
}
