<?php

declare(strict_types=1);

it('forbids __DIR__ and __FILE__ in package blade views', function () {
    $viewsDir = dirname(__DIR__).'/resources/views';

    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($viewsDir));
    $offenders = [];

    foreach ($iterator as $file) {
        if (! $file->isFile() || $file->getExtension() !== 'php') {
            continue;
        }

        $contents = file_get_contents($file->getPathname()) ?: '';

        if (preg_match('/\b__DIR__\b|\b__FILE__\b/', $contents)) {
            $offenders[] = $file->getPathname();
        }
    }

    expect($offenders)->toBe([], sprintf(
        "Blade views must not use __DIR__ or __FILE__ — they resolve to the compiled view cache directory after Blade compilation. Offenders:\n%s",
        implode("\n", $offenders)
    ));
});
