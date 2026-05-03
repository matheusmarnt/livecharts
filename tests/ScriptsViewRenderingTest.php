<?php

declare(strict_types=1);

use Illuminate\Support\Facades\View;

beforeEach(function () {
    View::getFinder()->flush();

    foreach (glob(storage_path('framework/views/*.php')) ?: [] as $compiled) {
        @unlink($compiled);
    }
});

it('renders livecharts::scripts without resolving __DIR__ to the compiled view cache', function () {
    config()->set('livecharts.assets.auto_inject', true);

    $output = view('livecharts::scripts')->render();

    expect($output)->toContain('<script>');
    expect(trim($output))->not->toBe('');
});

it('renders livecharts::scripts twice — second render hits the compiled cache and still works', function () {
    config()->set('livecharts.assets.auto_inject', true);

    $first = view('livecharts::scripts')->render();
    $second = view('livecharts::scripts')->render();

    expect($first)->toContain('<script>');
    expect($second)->toContain('<script>');
    expect($second)->toBe($first);
});

it('omits the inline bootstrap when auto_inject is disabled', function () {
    config()->set('livecharts.assets.auto_inject', false);

    $output = view('livecharts::scripts')->render();

    expect($output)->not->toContain('<script>');
});
