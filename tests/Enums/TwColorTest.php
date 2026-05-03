<?php

use Matheusmarnt\LiveCharts\Enums\TwColor;

it('every standard and extended case returns a valid 6-digit hex', function () {
    $shaded = array_filter(
        TwColor::cases(),
        fn ($c) => preg_match('/\d+$/', $c->name),
    );

    foreach ($shaded as $case) {
        expect($case->value)->toMatch('/^#[0-9a-f]{6}$/i', "Case {$case->name} value '{$case->value}' is not a valid hex");
    }
});

it('sentinels have expected values', function () {
    expect(TwColor::Black->value)->toBe('#000000');
    expect(TwColor::White->value)->toBe('#ffffff');
    expect(TwColor::Transparent->value)->toBe('transparent');
});

it('family() extracts color name in lowercase', function () {
    expect(TwColor::Amber500->family())->toBe('amber');
    expect(TwColor::Slate200->family())->toBe('slate');
    expect(TwColor::Olive950->family())->toBe('olive');
});

it('shadeNumber() extracts the numeric shade', function () {
    expect(TwColor::Red50->shadeNumber())->toBe(50);
    expect(TwColor::Emerald300->shadeNumber())->toBe(300);
    expect(TwColor::Blue950->shadeNumber())->toBe(950);
});

it('hex() returns the enum value', function () {
    expect(TwColor::Amber300->hex())->toBe(TwColor::Amber300->value);
});

it('withAlpha() returns a valid rgba string', function () {
    $rgba = TwColor::Amber300->withAlpha(0.5);

    expect($rgba)->toMatch('/^rgba\(\d+,\d+,\d+,0\.5\)$/');
});

it('withAlpha() clamps alpha to [0, 1]', function () {
    expect(TwColor::Red500->withAlpha(-0.5))->toMatch('/rgba\(\d+,\d+,\d+,0\)/');
    expect(TwColor::Red500->withAlpha(1.5))->toMatch('/rgba\(\d+,\d+,\d+,1\)/');
});

it('rgba() is an alias for withAlpha()', function () {
    $color = TwColor::Sky400;
    expect($color->rgba(0.7))->toBe($color->withAlpha(0.7));
});

it('shade() returns the same family with new shade', function () {
    expect(TwColor::Amber500->shade(300))->toBe(TwColor::Amber300);
    expect(TwColor::Emerald500->shade(800))->toBe(TwColor::Emerald800);
});

it('shade() returns self when shade not found', function () {
    expect(TwColor::Amber500->shade(999))->toBe(TwColor::Amber500);
});

it('lighter() moves to a lighter shade', function () {
    expect(TwColor::Amber500->lighter())->toBe(TwColor::Amber400);
    expect(TwColor::Amber500->lighter(2))->toBe(TwColor::Amber300);
});

it('lighter() clamps at shade 50', function () {
    expect(TwColor::Red50->lighter())->toBe(TwColor::Red50);
    expect(TwColor::Red50->lighter(10))->toBe(TwColor::Red50);
});

it('darker() moves to a darker shade', function () {
    expect(TwColor::Amber500->darker())->toBe(TwColor::Amber600);
    expect(TwColor::Amber500->darker(2))->toBe(TwColor::Amber700);
});

it('darker() clamps at shade 950', function () {
    expect(TwColor::Blue950->darker())->toBe(TwColor::Blue950);
    expect(TwColor::Blue950->darker(10))->toBe(TwColor::Blue950);
});

it('ramp() returns all 11 shades for a family in order', function () {
    $ramp = TwColor::ramp('amber');

    expect($ramp)->toHaveCount(11);
    expect($ramp[0])->toBe(TwColor::Amber50);
    expect($ramp[10])->toBe(TwColor::Amber950);
    expect($ramp[3]->shadeNumber())->toBe(300);
});

it('ramp() works for extended families', function () {
    $ramp = TwColor::ramp('olive');
    expect($ramp)->toHaveCount(11);
    expect($ramp[0])->toBe(TwColor::Olive50);
    expect($ramp[10])->toBe(TwColor::Olive950);
});

it('each standard color family has exactly 11 shades', function (string $family) {
    $ramp = TwColor::ramp($family);
    expect($ramp)->toHaveCount(11, "Family '{$family}' should have 11 shades");
})->with(['red', 'orange', 'amber', 'yellow', 'lime', 'green', 'emerald', 'teal',
    'cyan', 'sky', 'blue', 'indigo', 'violet', 'purple', 'fuchsia', 'pink', 'rose',
    'slate', 'gray', 'zinc', 'neutral', 'stone']);

it('each extended color family has exactly 11 shades', function (string $family) {
    $ramp = TwColor::ramp($family);
    expect($ramp)->toHaveCount(11, "Extended family '{$family}' should have 11 shades");
})->with(['taupe', 'mauve', 'mist', 'olive']);
