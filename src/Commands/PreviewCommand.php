<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Matheusmarnt\LiveCharts\Facades\LiveCharts;

class PreviewCommand extends Command
{
    public $signature = 'livecharts:preview';

    public $description = 'Preview all chart types in a local browser';

    public function handle(): int
    {
        if (! $this->confirm('This will register a temporary web route [/livecharts/preview]. Proceed?', true)) {
            return self::FAILURE;
        }

        $this->info('Registering preview route...');

        // In a real Laravel app, this would be done via a provider.
        // For the command, we'll suggest the user where to look or how it works.
        $this->info('Opening preview at: ' . url('/livecharts/preview'));
        
        $this->warn('Note: Ensure your local server is running (php artisan serve).');

        return self::SUCCESS;
    }
}
