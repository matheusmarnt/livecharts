<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Enums;

enum TwPalette: string
{
    case Vibrant = 'vibrant';
    case Muted = 'muted';
    case Monochrome = 'monochrome';
    case Pastel = 'pastel';
    case Neon = 'neon';

    /**
     * Return theme-aware color pairs for this palette.
     * Each entry has a 'dark' shade (lighter = more visible on dark bg)
     * and a 'light' shade (darker = more visible on light bg).
     *
     * @return list<array{dark: TwColor, light: TwColor}>
     */
    public function pairs(): array
    {
        return match ($this) {
            self::Vibrant => [
                ['dark' => TwColor::Red400,     'light' => TwColor::Red600],
                ['dark' => TwColor::Amber400,   'light' => TwColor::Amber600],
                ['dark' => TwColor::Emerald400, 'light' => TwColor::Emerald600],
                ['dark' => TwColor::Sky400,     'light' => TwColor::Sky600],
                ['dark' => TwColor::Violet400,  'light' => TwColor::Violet600],
                ['dark' => TwColor::Pink400,    'light' => TwColor::Pink600],
            ],
            self::Muted => [
                ['dark' => TwColor::Slate400,   'light' => TwColor::Slate600],
                ['dark' => TwColor::Stone400,   'light' => TwColor::Stone600],
                ['dark' => TwColor::Zinc400,    'light' => TwColor::Zinc600],
                ['dark' => TwColor::Neutral400, 'light' => TwColor::Neutral600],
                ['dark' => TwColor::Gray400,    'light' => TwColor::Gray600],
                ['dark' => TwColor::Mauve400,   'light' => TwColor::Mauve600],
            ],
            self::Monochrome => [
                ['dark' => TwColor::Slate200,   'light' => TwColor::Slate800],
                ['dark' => TwColor::Slate300,   'light' => TwColor::Slate700],
                ['dark' => TwColor::Slate400,   'light' => TwColor::Slate600],
                ['dark' => TwColor::Slate500,   'light' => TwColor::Slate500],
                ['dark' => TwColor::Slate600,   'light' => TwColor::Slate400],
                ['dark' => TwColor::Slate700,   'light' => TwColor::Slate300],
            ],
            self::Pastel => [
                ['dark' => TwColor::Red200,     'light' => TwColor::Red400],
                ['dark' => TwColor::Amber200,   'light' => TwColor::Amber400],
                ['dark' => TwColor::Emerald200, 'light' => TwColor::Emerald400],
                ['dark' => TwColor::Sky200,     'light' => TwColor::Sky400],
                ['dark' => TwColor::Violet200,  'light' => TwColor::Violet400],
                ['dark' => TwColor::Pink200,    'light' => TwColor::Pink400],
            ],
            self::Neon => [
                ['dark' => TwColor::Lime400,    'light' => TwColor::Lime600],
                ['dark' => TwColor::Cyan400,    'light' => TwColor::Cyan600],
                ['dark' => TwColor::Fuchsia400, 'light' => TwColor::Fuchsia600],
                ['dark' => TwColor::Yellow400,  'light' => TwColor::Yellow600],
                ['dark' => TwColor::Emerald400, 'light' => TwColor::Emerald600],
                ['dark' => TwColor::Rose400,    'light' => TwColor::Rose600],
            ],
        };
    }

    /** @return list<string> */
    public function darkHexList(): array
    {
        return array_map(fn (array $pair) => $pair['dark']->value, $this->pairs());
    }

    /** @return list<string> */
    public function lightHexList(): array
    {
        return array_map(fn (array $pair) => $pair['light']->value, $this->pairs());
    }
}
