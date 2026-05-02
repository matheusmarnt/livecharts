<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

it('can generate a chart class', function () {
    $chartName = 'TestGeneratedChart';
    $filePath = app_path('Charts/' . $chartName . '.php');

    if (File::exists($filePath)) {
        File::delete($filePath);
    }

    Artisan::call('make:chart', [
        'name' => $chartName,
        '--type' => 'bar',
        '--engine' => 'chartjs',
    ]);

    expect(File::exists($filePath))->toBeTrue();
    
    $content = File::get($filePath);
    expect($content)->toContain('class ' . $chartName);
    expect($content)->toContain("protected string \$type = 'bar'");
    expect($content)->toContain("protected string \$engine = 'chartjs'");

    File::delete($filePath);
});
