<?php

declare(strict_types=1);

use Matheusmarnt\LiveCharts\Support\ColorValue;

it('withAlpha() returns new ColorValue with alpha clamped to [0,1]', function () {
    $cv = ColorValue::pair('#ff0000', '#00ff00');
    $withAlpha = $cv->withAlpha(0.5);

    expect($withAlpha)->not->toBe($cv);
    expect($withAlpha->alpha)->toBe(0.5);
    expect($withAlpha->dark)->toBe('#ff0000');
    expect($withAlpha->light)->toBe('#00ff00');
});

it('withAlpha() clamps values above 1 to 1.0', function () {
    $cv = ColorValue::from('#ffffff')->withAlpha(2.0);
    expect($cv->alpha)->toBe(1.0);
});

it('withAlpha() clamps values below 0 to 0.0', function () {
    $cv = ColorValue::from('#ffffff')->withAlpha(-0.5);
    expect($cv->alpha)->toBe(0.0);
});

it('darkHex() applies alpha when set', function () {
    $cv = ColorValue::pair('#3b82f6', '#1d4ed8')->withAlpha(0.6);
    expect($cv->darkHex())->toBe('rgba(59,130,246,0.6)');
});

it('lightHex() applies alpha when set', function () {
    $cv = ColorValue::pair('#3b82f6', '#1d4ed8')->withAlpha(0.4);
    expect($cv->lightHex())->toBe('rgba(29,78,216,0.4)');
});

it('forTheme() returns darkHex for dark theme', function () {
    $cv = ColorValue::pair('#111111', '#eeeeee');
    expect($cv->forTheme('dark'))->toBe('#111111');
});

it('forTheme() returns lightHex for light theme', function () {
    $cv = ColorValue::pair('#111111', '#eeeeee');
    expect($cv->forTheme('light'))->toBe('#eeeeee');
});

it('applyAlpha() returns raw hex unchanged when hex is not 6 chars', function () {
    $cv = new ColorValue('transparent', 'transparent', 0.5);
    expect($cv->darkHex())->toBe('transparent');
});
