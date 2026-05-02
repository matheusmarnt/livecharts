<?php

namespace Matheusmarnt\LiveCharts;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

use Matheusmarnt\LiveCharts\Engines\EngineFactory;

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
            ->hasViews();
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
    }
}
