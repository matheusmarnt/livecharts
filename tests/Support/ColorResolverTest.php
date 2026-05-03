<?php

use Matheusmarnt\LiveCharts\Enums\TwColor;
use Matheusmarnt\LiveCharts\Support\ColorResolver;
use Matheusmarnt\LiveCharts\Support\ColorValue;

it('resolve() returns null for null input', function () {
    expect(ColorResolver::resolve(null))->toBeNull();
});

it('resolve() wraps hex string into a ColorValue with same dark and light', function () {
    $cv = ColorResolver::resolve('#ff0000');

    expect($cv)->toBeInstanceOf(ColorValue::class);
    expect($cv->dark)->toBe('#ff0000');
    expect($cv->light)->toBe('#ff0000');
});

it('resolve() wraps TwColor into a ColorValue with same dark and light', function () {
    $cv = ColorResolver::resolve(TwColor::Amber300);

    expect($cv)->toBeInstanceOf(ColorValue::class);
    expect($cv->dark)->toBe(TwColor::Amber300->value);
    expect($cv->light)->toBe(TwColor::Amber300->value);
});

it('resolve() passes through ColorValue unchanged', function () {
    $original = ColorValue::pair(TwColor::Amber300, TwColor::Amber600);
    $resolved = ColorResolver::resolve($original);

    expect($resolved)->toBe($original);
});

it('resolvePair() returns null when both args null', function () {
    expect(ColorResolver::resolvePair(null, null))->toBeNull();
});

it('resolvePair() uses single value for both themes when only dark provided', function () {
    $cv = ColorResolver::resolvePair(TwColor::Amber500);

    expect($cv->dark)->toBe(TwColor::Amber500->value);
    expect($cv->light)->toBe(TwColor::Amber500->value);
});

it('resolvePair() creates distinct dark and light when both provided', function () {
    $cv = ColorResolver::resolvePair(TwColor::Amber300, TwColor::Amber600);

    expect($cv->dark)->toBe(TwColor::Amber300->value);
    expect($cv->light)->toBe(TwColor::Amber600->value);
});

it('resolvePair() accepts hex strings', function () {
    $cv = ColorResolver::resolvePair('#aaa', '#333');

    expect($cv->dark)->toBe('#aaa');
    expect($cv->light)->toBe('#333');
});

it('resolveList() handles empty array', function () {
    expect(ColorResolver::resolveList([]))->toBe([]);
});

it('resolveList() normalizes hex strings', function () {
    $list = ColorResolver::resolveList(['#f00', '#0f0']);

    expect($list)->toHaveCount(2);
    expect($list[0]->dark)->toBe('#f00');
    expect($list[1]->dark)->toBe('#0f0');
});

it('resolveList() normalizes TwColor entries', function () {
    $list = ColorResolver::resolveList([TwColor::Amber500, TwColor::Sky500]);

    expect($list)->toHaveCount(2);
    expect($list[0]->dark)->toBe(TwColor::Amber500->value);
});

it('resolveList() passes through ColorValue entries', function () {
    $cv = ColorValue::pair('#aaa', '#333');
    $list = ColorResolver::resolveList([$cv]);

    expect($list[0])->toBe($cv);
});

it('resolveList() handles array {dark, light} pairs', function () {
    $list = ColorResolver::resolveList([
        ['dark' => TwColor::Amber300, 'light' => TwColor::Amber600],
    ]);

    expect($list[0]->dark)->toBe(TwColor::Amber300->value);
    expect($list[0]->light)->toBe(TwColor::Amber600->value);
});

it('resolveList() handles mixed input forms', function () {
    $list = ColorResolver::resolveList([
        '#ff0000',
        TwColor::Sky500,
        ColorValue::from('#00ff00'),
        ['dark' => TwColor::Indigo300, 'light' => TwColor::Indigo700],
    ]);

    expect($list)->toHaveCount(4);
});
