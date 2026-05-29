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

it('JS source registers Alpine component via window.Alpine guard not solely inside alpine:init', function () {
    $src = file_get_contents(dirname(__DIR__).'/resources/js/livecharts.js') ?: '';

    // Must have the guard-based registration path
    expect($src)->toContain('window.Alpine.__livechartsRegistered');
    expect($src)->toContain('registerLiveChartsComponent');
    // Must support immediate registration when Alpine is already booted
    expect($src)->toContain('if (window.Alpine) registerLiveChartsComponent');
});

it('JS source registers Livewire hooks via window.Livewire guard not solely inside livewire:init', function () {
    $src = file_get_contents(dirname(__DIR__).'/resources/js/livecharts.js') ?: '';

    expect($src)->toContain('Livewire.__livechartsHooked');
    expect($src)->toContain('registerLivewireHooks');
    expect($src)->toContain('if (typeof Livewire !== \'undefined\') registerLivewireHooks');
});

it('forbids constructor as Alpine data object key in JS source', function () {
    $jsDir = dirname(__DIR__).'/resources/js';

    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($jsDir));
    $offenders = [];

    foreach ($iterator as $file) {
        if (! $file->isFile() || $file->getExtension() !== 'js') {
            continue;
        }

        $contents = file_get_contents($file->getPathname()) ?: '';

        if (preg_match('/\bconstructor\s*:/', $contents)) {
            $offenders[] = $file->getPathname();
        }
    }

    expect($offenders)->toBe([], sprintf(
        "JS source must not use 'constructor' as an Alpine data object key — it collides with Object.prototype.constructor and breaks this-binding inside Alpine scope. Use 'engineCtor' instead. Offenders:\n%s",
        implode("\n", $offenders)
    ));
});
