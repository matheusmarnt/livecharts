<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Matheusmarnt\LiveCharts\Commands\ChartMakeCommand;
use Matheusmarnt\LiveCharts\Commands\InstallCommand;
use Matheusmarnt\LiveCharts\Commands\PreviewCommand;
use Matheusmarnt\LiveCharts\Engines\EngineFactory;
use Matheusmarnt\LiveCharts\Livewire\LiveChartsComponent;
use Matheusmarnt\LiveCharts\Support\AssetManager;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
            ->hasTranslations()
            ->hasCommands([
                InstallCommand::class,
                ChartMakeCommand::class,
                PreviewCommand::class,
            ]);
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(EngineFactory::class, function () {
            return new EngineFactory;
        });

        $this->app->singleton(LiveCharts::class, function ($app) {
            return new LiveCharts($app->make(EngineFactory::class));
        });

        $this->app->singleton(AssetManager::class, function () {
            return new AssetManager;
        });
    }

    public function packageBooted(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'livecharts');

        $factory = $this->app->make(EngineFactory::class);

        foreach (config('livecharts.engines', []) as $name => $adapter) {
            $factory->register($name, $adapter);
        }

        Livewire::component('livecharts', LiveChartsComponent::class);

        Blade::directive('liveChartsScripts', function () {
            return "<?php echo \$__env->yieldPushContent('livecharts-scripts'); ?>";
        });

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../resources/dist' => public_path('vendor/livecharts/js'),
            ], 'livecharts-assets');

            $this->publishes([
                __DIR__.'/../resources/stubs' => base_path('stubs/livecharts'),
            ], 'livecharts-stubs');
        }

        $this->registerRoutes();
    }

    protected function registerRoutes(): void
    {
        Route::get('/livecharts/preview', function () {
            $charts = [
                'line' => Facades\LiveCharts::line()->labels(['Jan', 'Feb', 'Mar'])->dataset('Series 1', [10, 20, 15]),
                'bar' => Facades\LiveCharts::bar()->labels(['A', 'B', 'C'])->dataset('Series 1', [400, 300, 600]),
                'pie' => Facades\LiveCharts::pie()->labels(['Red', 'Blue'])->dataset('Votes', [12, 19]),
                'donut' => Facades\LiveCharts::donut()->labels(['Work', 'Eat', 'Sleep'])->dataset('Day', [8, 2, 8]),
                'radar' => Facades\LiveCharts::radar()->labels(['Speed', 'Power', 'Stamina'])->dataset('Stats', [80, 90, 70]),
            ];

            return view('livecharts::preview', ['charts' => $charts]);
        })->middleware(['web']);
    }
}
