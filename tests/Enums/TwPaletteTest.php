<?php

use Matheusmarnt\LiveCharts\Enums\TwColor;
use Matheusmarnt\LiveCharts\Enums\TwPalette;

it('every palette returns at least 1 pair', function (TwPalette $palette) {
    expect($palette->pairs())->not->toBeEmpty();
})->with(TwPalette::cases());

it('every pair has dark and light keys with TwColor values', function (TwPalette $palette) {
    foreach ($palette->pairs() as $pair) {
        expect($pair)->toHaveKey('dark');
        expect($pair)->toHaveKey('light');
        expect($pair['dark'])->toBeInstanceOf(TwColor::class);
        expect($pair['light'])->toBeInstanceOf(TwColor::class);
    }
})->with(TwPalette::cases());

it('vibrant palette dark shades are lighter than light shades', function () {
    foreach (TwPalette::Vibrant->pairs() as $pair) {
        expect($pair['dark']->shadeNumber())->toBeLessThan($pair['light']->shadeNumber());
    }
});

it('darkHexList() returns only hex strings', function () {
    foreach (TwPalette::Vibrant->darkHexList() as $hex) {
        expect($hex)->toMatch('/^#[0-9a-f]{6}$/i');
    }
});

it('lightHexList() returns only hex strings', function () {
    foreach (TwPalette::Vibrant->lightHexList() as $hex) {
        expect($hex)->toMatch('/^#[0-9a-f]{6}$/i');
    }
});

it('palette cases cover all 5 variants', function () {
    expect(TwPalette::cases())->toHaveCount(5);
    $names = array_map(fn ($c) => $c->name, TwPalette::cases());
    expect($names)->toContain('Vibrant', 'Muted', 'Monochrome', 'Pastel', 'Neon');
});
