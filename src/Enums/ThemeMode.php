<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Enums;

enum ThemeMode: string
{
    case Auto = 'auto';
    case Light = 'light';
    case Dark = 'dark';

    public function isDark(): bool
    {
        return $this === self::Dark;
    }

    public function isLight(): bool
    {
        return $this === self::Light;
    }

    public function isAuto(): bool
    {
        return $this === self::Auto;
    }
}
