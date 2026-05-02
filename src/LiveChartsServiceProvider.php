<?php

namespace Matheusmarnt\LiveCharts;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

use Matheusmarnt\LiveCharts\Engines\EngineFactory;
use Matheusmarnt\LiveCharts\Livewire\LiveChartsComponent;
use Matheusmarnt\LiveCharts\Commands\InstallCommand;
use Livewire\Livewire;

class LiveChartsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('livecharts')
            ->hasConfigFile()
            ->hasViews()
            ->hasCommand(InstallCommand::class);
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(LiveCharts::class, function () {
            return new LiveCharts();
        });
    }

    public function packageBooted(): void
    {
        foreach (config('livecharts.engines', []) as $name => $adapter) {
            EngineFactory::register($name, $adapter);
        }

        Livewire::component('livecharts', LiveChartsComponent::class);
    }
}
