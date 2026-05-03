<?php

declare(strict_types=1);

use Matheusmarnt\LiveCharts\Enums\ThemeMode;

it('isDark() returns true only for Dark case', function () {
    expect(ThemeMode::Dark->isDark())->toBeTrue();
    expect(ThemeMode::Light->isDark())->toBeFalse();
    expect(ThemeMode::Auto->isDark())->toBeFalse();
});

it('isLight() returns true only for Light case', function () {
    expect(ThemeMode::Light->isLight())->toBeTrue();
    expect(ThemeMode::Dark->isLight())->toBeFalse();
    expect(ThemeMode::Auto->isLight())->toBeFalse();
});

it('isAuto() returns true only for Auto case', function () {
    expect(ThemeMode::Auto->isAuto())->toBeTrue();
    expect(ThemeMode::Dark->isAuto())->toBeFalse();
    expect(ThemeMode::Light->isAuto())->toBeFalse();
});

it('enum values match expected strings', function () {
    expect(ThemeMode::Auto->value)->toBe('auto');
    expect(ThemeMode::Light->value)->toBe('light');
    expect(ThemeMode::Dark->value)->toBe('dark');
});
