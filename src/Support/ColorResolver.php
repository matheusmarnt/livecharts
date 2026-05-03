<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Support;

use Matheusmarnt\LiveCharts\Enums\TwColor;

/**
 * Normalizes every color input form into a ColorValue.
 *
 * Accepted forms:
 *  - string hex '#ff0000'            → both themes same hex
 *  - TwColor enum case               → both themes same hex
 *  - ColorValue                      → pass-through
 *  - null                            → null
 */
final class ColorResolver
{
    public static function resolve(
        TwColor|ColorValue|string|null $value,
    ): ?ColorValue {
        if ($value === null) {
            return null;
        }

        return ColorValue::from($value);
    }

    /**
     * Build a theme-aware pair from separate dark / light values.
     * If only $dark is provided (light is null), both themes use $dark.
     */
    public static function resolvePair(
        TwColor|string|null $dark,
        TwColor|string|null $light = null,
    ): ?ColorValue {
        if ($dark === null && $light === null) {
            return null;
        }

        if ($light === null) {
            // $dark must be non-null (both-null eliminated above)
            return ColorValue::from($dark);
        }

        // $light is non-null; $dark may be null → use $light for both themes
        return ColorValue::pair($dark ?? $light, $light);
    }

    /**
     * Normalize a chart-level colors array into a ColorValue list.
     *
     * Each element may be:
     *  - string hex                  → both themes same
     *  - TwColor                     → both themes same
     *  - ColorValue                  → pass-through
     *  - array{dark: ..., light: ...}→ explicit pair
     *
     * @param  array<int, string|TwColor|ColorValue|array{dark?: TwColor|string, light?: TwColor|string}>  $colors
     * @return list<ColorValue>
     */
    public static function resolveList(array $colors): array
    {
        $result = [];

        foreach ($colors as $color) {
            if ($color instanceof ColorValue) {
                $result[] = $color;

                continue;
            }

            if ($color instanceof TwColor) {
                $result[] = ColorValue::from($color);

                continue;
            }

            if (is_string($color)) {
                $result[] = ColorValue::from($color);

                continue;
            }

            // Remaining type: array{dark?: TwColor|string, light?: TwColor|string}
            $dark = $color['dark'] ?? null;
            $light = $color['light'] ?? null;

            $resolved = self::resolvePair(
                $dark  instanceof TwColor ? $dark : (is_string($dark) ? $dark : null),
                $light instanceof TwColor ? $light : (is_string($light) ? $light : null),
            );

            if ($resolved !== null) {
                $result[] = $resolved;
            }
        }

        return $result;
    }
}
