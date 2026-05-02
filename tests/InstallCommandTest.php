<?php

use Illuminate\Support\Facades\File;

it('can install configuration and assets', function () {
    $jsPath = resource_path('js/livecharts.js');

    if (File::exists($jsPath)) {
        File::delete($jsPath);
    }

    $this->artisan('livecharts:install')
        ->expectsConfirmation('Publish chart class stubs to stubs/livecharts?', 'no')
        ->assertExitCode(0);

    expect(File::exists($jsPath))->toBeTrue();
});

it('asks before overwriting existing assets', function () {
    $jsPath = resource_path('js/livecharts.js');
    File::ensureDirectoryExists(dirname($jsPath));
    File::put($jsPath, 'old content');

    $this->artisan('livecharts:install')
        ->expectsConfirmation('livecharts.js already exists. Overwrite?', 'no')
        ->expectsConfirmation('Publish chart class stubs to stubs/livecharts?', 'no')
        ->assertExitCode(0);

    expect(File::get($jsPath))->toBe('old content');
});

it('publishes chart class stubs when confirmed', function () {
    $stubsPath = base_path('stubs/livecharts');
    $stubFile = $stubsPath.'/chart.php.stub';

    if (File::isDirectory($stubsPath)) {
        File::deleteDirectory($stubsPath);
    }

    $jsPath = resource_path('js/livecharts.js');
    if (File::exists($jsPath)) {
        File::delete($jsPath);
    }

    $this->artisan('livecharts:install')
        ->expectsConfirmation('Publish chart class stubs to stubs/livecharts?', 'yes')
        ->assertExitCode(0);

    expect(File::exists($stubFile))->toBeTrue()
        ->and(File::get($stubFile))->toContain('extends Chart');

    File::deleteDirectory($stubsPath);
});

it('skips stub publishing when declined', function () {
    $stubsPath = base_path('stubs/livecharts');

    if (File::isDirectory($stubsPath)) {
        File::deleteDirectory($stubsPath);
    }

    $jsPath = resource_path('js/livecharts.js');
    if (File::exists($jsPath)) {
        File::delete($jsPath);
    }

    $this->artisan('livecharts:install')
        ->expectsConfirmation('Publish chart class stubs to stubs/livecharts?', 'no')
        ->assertExitCode(0);

    expect(File::isDirectory($stubsPath))->toBeFalse();
});
