<?php

declare(strict_types=1);

it('prints the preview URL and exits cleanly with --no-open', function () {
    $this->artisan('livecharts:preview', ['--no-open' => true])
        ->expectsOutputToContain('/livecharts/preview')
        ->expectsOutputToContain('php artisan serve')
        ->assertSuccessful();
});

it('registers the preview route at /livecharts/preview', function () {
    $route = collect(\Illuminate\Support\Facades\Route::getRoutes()->getRoutes())
        ->first(fn ($r) => $r->uri() === 'livecharts/preview');

    expect($route)->not->toBeNull();
    expect($route->methods())->toContain('GET');
    expect($route->middleware())->toContain('web');
});
