<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Commands;

use Illuminate\Console\Command;

class PreviewCommand extends Command
{
    public $signature = 'livecharts:preview';

    public $description = 'Preview all chart types in a local browser';

    public function handle(): int
    {
        if (! $this->confirm(__('livecharts::livecharts.preview.confirm_register'), true)) {
            return self::FAILURE;
        }

        $this->info(__('livecharts::livecharts.preview.registering'));

        $this->info(__('livecharts::livecharts.preview.opening_at', [
            'url' => url('/livecharts/preview'),
        ]));

        $this->warn(__('livecharts::livecharts.preview.serve_warning'));

        return self::SUCCESS;
    }
}
