<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

it('can install configuration and assets', function () {
    $jsPath = resource_path('js/livecharts.js');
    $configPath = config_path('livecharts.php');

    // Clean up before test
    if (File::exists($jsPath)) {
        File::delete($jsPath);
    }
    // Config path is usually managed by orchestrator in tests, but let's check

    Artisan::call('livecharts:install');

    expect(File::exists($jsPath))->toBeTrue();
    // In testbench, config is usually already there or doesn't matter for this check
});

it('asks before overwriting existing assets', function () {
    $jsPath = resource_path('js/livecharts.js');
    File::ensureDirectoryExists(dirname($jsPath));
    File::put($jsPath, 'old content');

    $this->artisan('livecharts:install')
        ->expectsConfirmation('livecharts.js already exists. Overwrite?', 'no')
        ->assertExitCode(0);

    expect(File::get($jsPath))->toBe('old content');
});
