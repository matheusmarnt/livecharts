<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class PreviewCommand extends Command
{
    public $signature = 'livecharts:preview {--no-open : Print the URL without launching the browser}';

    public $description = 'Preview all chart types in a local browser';

    public function handle(): int
    {
        $url = url('/livecharts/preview');

        $this->info(__('livecharts::livecharts.preview.opening_at', ['url' => $url]));
        $this->warn(__('livecharts::livecharts.preview.serve_warning'));

        if ($this->option('no-open')) {
            return self::SUCCESS;
        }

        return $this->openInBrowser($url) ? self::SUCCESS : self::FAILURE;
    }

    protected function openInBrowser(string $url): bool
    {
        $command = match (PHP_OS_FAMILY) {
            'Darwin' => ['open', $url],
            'Windows' => ['cmd', '/c', 'start', '', $url],
            default => ['xdg-open', $url],
        };

        try {
            Process::fromShellCommandline(implode(' ', array_map('escapeshellarg', $command)))
                ->setTimeout(5)
                ->run();

            return true;
        } catch (\Throwable $e) {
            $this->warn(__('livecharts::livecharts.preview.open_failed', ['error' => $e->getMessage()]));

            return false;
        }
    }
}
