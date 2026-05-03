<?php

declare(strict_types=1);

namespace Matheusmarnt\LiveCharts\Enums;

enum TwColor: string
{
    // ─── Sentinels ───────────────────────────────────────────────────────────
    case Black = '#000000';
    case White = '#ffffff';
    case Transparent = 'transparent';

    // ─── Red ─────────────────────────────────────────────────────────────────
    case Red50 = '#fef2f2';
    case Red100 = '#fee2e2';
    case Red200 = '#fecaca';
    case Red300 = '#fca5a5';
    case Red400 = '#f87171';
    case Red500 = '#ef4444';
    case Red600 = '#dc2626';
    case Red700 = '#b91c1c';
    case Red800 = '#991b1b';
    case Red900 = '#7f1d1d';
    case Red950 = '#450a0a';

    // ─── Orange ──────────────────────────────────────────────────────────────
    case Orange50 = '#fff7ed';
    case Orange100 = '#ffedd5';
    case Orange200 = '#fed7aa';
    case Orange300 = '#fdba74';
    case Orange400 = '#fb923c';
    case Orange500 = '#f97316';
    case Orange600 = '#ea580c';
    case Orange700 = '#c2410c';
    case Orange800 = '#9a3412';
    case Orange900 = '#7c2d12';
    case Orange950 = '#431407';

    // ─── Amber ───────────────────────────────────────────────────────────────
    case Amber50 = '#fffbeb';
    case Amber100 = '#fef3c7';
    case Amber200 = '#fde68a';
    case Amber300 = '#fcd34d';
    case Amber400 = '#fbbf24';
    case Amber500 = '#f59e0b';
    case Amber600 = '#d97706';
    case Amber700 = '#b45309';
    case Amber800 = '#92400e';
    case Amber900 = '#78350f';
    case Amber950 = '#451a03';

    // ─── Yellow ──────────────────────────────────────────────────────────────
    case Yellow50 = '#fefce8';
    case Yellow100 = '#fef9c3';
    case Yellow200 = '#fef08a';
    case Yellow300 = '#fde047';
    case Yellow400 = '#facc15';
    case Yellow500 = '#eab308';
    case Yellow600 = '#ca8a04';
    case Yellow700 = '#a16207';
    case Yellow800 = '#854d0e';
    case Yellow900 = '#713f12';
    case Yellow950 = '#422006';

    // ─── Lime ────────────────────────────────────────────────────────────────
    case Lime50 = '#f7fee7';
    case Lime100 = '#ecfccb';
    case Lime200 = '#d9f99d';
    case Lime300 = '#bef264';
    case Lime400 = '#a3e635';
    case Lime500 = '#84cc16';
    case Lime600 = '#65a30d';
    case Lime700 = '#4d7c0f';
    case Lime800 = '#3f6212';
    case Lime900 = '#365314';
    case Lime950 = '#1a2e05';

    // ─── Green ───────────────────────────────────────────────────────────────
    case Green50 = '#f0fdf4';
    case Green100 = '#dcfce7';
    case Green200 = '#bbf7d0';
    case Green300 = '#86efac';
    case Green400 = '#4ade80';
    case Green500 = '#22c55e';
    case Green600 = '#16a34a';
    case Green700 = '#15803d';
    case Green800 = '#166534';
    case Green900 = '#14532d';
    case Green950 = '#052e16';

    // ─── Emerald ─────────────────────────────────────────────────────────────
    case Emerald50 = '#ecfdf5';
    case Emerald100 = '#d1fae5';
    case Emerald200 = '#a7f3d0';
    case Emerald300 = '#6ee7b7';
    case Emerald400 = '#34d399';
    case Emerald500 = '#10b981';
    case Emerald600 = '#059669';
    case Emerald700 = '#047857';
    case Emerald800 = '#065f46';
    case Emerald900 = '#064e3b';
    case Emerald950 = '#022c22';

    // ─── Teal ────────────────────────────────────────────────────────────────
    case Teal50 = '#f0fdfa';
    case Teal100 = '#ccfbf1';
    case Teal200 = '#99f6e4';
    case Teal300 = '#5eead4';
    case Teal400 = '#2dd4bf';
    case Teal500 = '#14b8a6';
    case Teal600 = '#0d9488';
    case Teal700 = '#0f766e';
    case Teal800 = '#115e59';
    case Teal900 = '#134e4a';
    case Teal950 = '#042f2e';

    // ─── Cyan ────────────────────────────────────────────────────────────────
    case Cyan50 = '#ecfeff';
    case Cyan100 = '#cffafe';
    case Cyan200 = '#a5f3fc';
    case Cyan300 = '#67e8f9';
    case Cyan400 = '#22d3ee';
    case Cyan500 = '#06b6d4';
    case Cyan600 = '#0891b2';
    case Cyan700 = '#0e7490';
    case Cyan800 = '#155e75';
    case Cyan900 = '#164e63';
    case Cyan950 = '#083344';

    // ─── Sky ─────────────────────────────────────────────────────────────────
    case Sky50 = '#f0f9ff';
    case Sky100 = '#e0f2fe';
    case Sky200 = '#bae6fd';
    case Sky300 = '#7dd3fc';
    case Sky400 = '#38bdf8';
    case Sky500 = '#0ea5e9';
    case Sky600 = '#0284c7';
    case Sky700 = '#0369a1';
    case Sky800 = '#075985';
    case Sky900 = '#0c4a6e';
    case Sky950 = '#082f49';

    // ─── Blue ────────────────────────────────────────────────────────────────
    case Blue50 = '#eff6ff';
    case Blue100 = '#dbeafe';
    case Blue200 = '#bfdbfe';
    case Blue300 = '#93c5fd';
    case Blue400 = '#60a5fa';
    case Blue500 = '#3b82f6';
    case Blue600 = '#2563eb';
    case Blue700 = '#1d4ed8';
    case Blue800 = '#1e40af';
    case Blue900 = '#1e3a8a';
    case Blue950 = '#172554';

    // ─── Indigo ──────────────────────────────────────────────────────────────
    case Indigo50 = '#eef2ff';
    case Indigo100 = '#e0e7ff';
    case Indigo200 = '#c7d2fe';
    case Indigo300 = '#a5b4fc';
    case Indigo400 = '#818cf8';
    case Indigo500 = '#6366f1';
    case Indigo600 = '#4f46e5';
    case Indigo700 = '#4338ca';
    case Indigo800 = '#3730a3';
    case Indigo900 = '#312e81';
    case Indigo950 = '#1e1b4b';

    // ─── Violet ──────────────────────────────────────────────────────────────
    case Violet50 = '#f5f3ff';
    case Violet100 = '#ede9fe';
    case Violet200 = '#ddd6fe';
    case Violet300 = '#c4b5fd';
    case Violet400 = '#a78bfa';
    case Violet500 = '#8b5cf6';
    case Violet600 = '#7c3aed';
    case Violet700 = '#6d28d9';
    case Violet800 = '#5b21b6';
    case Violet900 = '#4c1d95';
    case Violet950 = '#2e1065';

    // ─── Purple ──────────────────────────────────────────────────────────────
    case Purple50 = '#faf5ff';
    case Purple100 = '#f3e8ff';
    case Purple200 = '#e9d5ff';
    case Purple300 = '#d8b4fe';
    case Purple400 = '#c084fc';
    case Purple500 = '#a855f7';
    case Purple600 = '#9333ea';
    case Purple700 = '#7e22ce';
    case Purple800 = '#6b21a8';
    case Purple900 = '#581c87';
    case Purple950 = '#3b0764';

    // ─── Fuchsia ─────────────────────────────────────────────────────────────
    case Fuchsia50 = '#fdf4ff';
    case Fuchsia100 = '#fae8ff';
    case Fuchsia200 = '#f5d0fe';
    case Fuchsia300 = '#f0abfc';
    case Fuchsia400 = '#e879f9';
    case Fuchsia500 = '#d946ef';
    case Fuchsia600 = '#c026d3';
    case Fuchsia700 = '#a21caf';
    case Fuchsia800 = '#86198f';
    case Fuchsia900 = '#701a75';
    case Fuchsia950 = '#4a044e';

    // ─── Pink ────────────────────────────────────────────────────────────────
    case Pink50 = '#fdf2f8';
    case Pink100 = '#fce7f3';
    case Pink200 = '#fbcfe8';
    case Pink300 = '#f9a8d4';
    case Pink400 = '#f472b6';
    case Pink500 = '#ec4899';
    case Pink600 = '#db2777';
    case Pink700 = '#be185d';
    case Pink800 = '#9d174d';
    case Pink900 = '#831843';
    case Pink950 = '#500724';

    // ─── Rose ────────────────────────────────────────────────────────────────
    case Rose50 = '#fff1f2';
    case Rose100 = '#ffe4e6';
    case Rose200 = '#fecdd3';
    case Rose300 = '#fda4af';
    case Rose400 = '#fb7185';
    case Rose500 = '#f43f5e';
    case Rose600 = '#e11d48';
    case Rose700 = '#be123c';
    case Rose800 = '#9f1239';
    case Rose900 = '#881337';
    case Rose950 = '#4c0519';

    // ─── Slate ───────────────────────────────────────────────────────────────
    case Slate50 = '#f8fafc';
    case Slate100 = '#f1f5f9';
    case Slate200 = '#e2e8f0';
    case Slate300 = '#cbd5e1';
    case Slate400 = '#94a3b8';
    case Slate500 = '#64748b';
    case Slate600 = '#475569';
    case Slate700 = '#334155';
    case Slate800 = '#1e293b';
    case Slate900 = '#0f172a';
    case Slate950 = '#020617';

    // ─── Gray ────────────────────────────────────────────────────────────────
    case Gray50 = '#f9fafb';
    case Gray100 = '#f3f4f6';
    case Gray200 = '#e5e7eb';
    case Gray300 = '#d1d5db';
    case Gray400 = '#9ca3af';
    case Gray500 = '#6b7280';
    case Gray600 = '#4b5563';
    case Gray700 = '#374151';
    case Gray800 = '#1f2937';
    case Gray900 = '#111827';
    case Gray950 = '#030712';

    // ─── Zinc ────────────────────────────────────────────────────────────────
    case Zinc50 = '#fafafa';
    case Zinc100 = '#f4f4f5';
    case Zinc200 = '#e4e4e7';
    case Zinc300 = '#d4d4d8';
    case Zinc400 = '#a1a1aa';
    case Zinc500 = '#71717a';
    case Zinc600 = '#52525b';
    case Zinc700 = '#3f3f46';
    case Zinc800 = '#27272a';
    case Zinc900 = '#18181b';
    case Zinc950 = '#09090b';

    // ─── Neutral ─────────────────────────────────────────────────────────────
    case Neutral50 = '#f9f9f9';
    case Neutral100 = '#f5f5f5';
    case Neutral200 = '#e5e5e5';
    case Neutral300 = '#d4d4d4';
    case Neutral400 = '#a3a3a3';
    case Neutral500 = '#737373';
    case Neutral600 = '#525252';
    case Neutral700 = '#404040';
    case Neutral800 = '#262626';
    case Neutral900 = '#171717';
    case Neutral950 = '#0a0a0a';

    // ─── Stone ───────────────────────────────────────────────────────────────
    case Stone50 = '#fafaf9';
    case Stone100 = '#f5f5f4';
    case Stone200 = '#e7e5e4';
    case Stone300 = '#d6d3d1';
    case Stone400 = '#a8a29e';
    case Stone500 = '#78716c';
    case Stone600 = '#57534e';
    case Stone700 = '#44403c';
    case Stone800 = '#292524';
    case Stone900 = '#1c1917';
    case Stone950 = '#0c0a09';

    // ─── Taupe (extended — warm brownish gray) ────────────────────────────────
    case Taupe50 = '#faf9f7';
    case Taupe100 = '#f2efec';
    case Taupe200 = '#e5e0da';
    case Taupe300 = '#d3ccc4';
    case Taupe400 = '#b5aa9e';
    case Taupe500 = '#96887c';
    case Taupe600 = '#7a6c62';
    case Taupe700 = '#62564d';
    case Taupe800 = '#4a4039';
    case Taupe900 = '#332d28';
    case Taupe950 = '#1a1512';

    // ─── Mauve (extended — soft grayish purple) ───────────────────────────────
    case Mauve50 = '#fdf8ff';
    case Mauve100 = '#f7eeff';
    case Mauve200 = '#edddff';
    case Mauve300 = '#dfc7ff';
    case Mauve400 = '#c9a8f5';
    case Mauve500 = '#b08ae8';
    case Mauve600 = '#8e6ccb';
    case Mauve700 = '#7054a8';
    case Mauve800 = '#553e85';
    case Mauve900 = '#3c2c61';
    case Mauve950 = '#22193a';

    // ─── Mist (extended — cool blue-gray) ────────────────────────────────────
    case Mist50 = '#f7f9fc';
    case Mist100 = '#edf1f7';
    case Mist200 = '#d9e3ef';
    case Mist300 = '#bfcedf';
    case Mist400 = '#96afc8';
    case Mist500 = '#6d90b1';
    case Mist600 = '#527595';
    case Mist700 = '#3e5c77';
    case Mist800 = '#2c4459';
    case Mist900 = '#1d2f40';
    case Mist950 = '#0f1924';

    // ─── Olive (extended — warm yellow-green) ────────────────────────────────
    case Olive50 = '#f8faf0';
    case Olive100 = '#eef3d8';
    case Olive200 = '#dde8b2';
    case Olive300 = '#c4d680';
    case Olive400 = '#a8bf50';
    case Olive500 = '#8aa32f';
    case Olive600 = '#6e831f';
    case Olive700 = '#566617';
    case Olive800 = '#414e13';
    case Olive900 = '#2e3810';
    case Olive950 = '#161c06';

    // ─── Shade indices ────────────────────────────────────────────────────────

    /** @var list<int> */
    private const SHADES = [50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 950];

    // ─── Methods ─────────────────────────────────────────────────────────────

    public function hex(): string
    {
        return $this->value;
    }

    public function withAlpha(float $alpha): string
    {
        $alpha = max(0.0, min(1.0, $alpha));
        $hex = ltrim($this->value, '#');

        if (strlen($hex) !== 6) {
            return $this->value;
        }

        $r = (int) hexdec(substr($hex, 0, 2));
        $g = (int) hexdec(substr($hex, 2, 2));
        $b = (int) hexdec(substr($hex, 4, 2));

        return "rgba({$r},{$g},{$b},{$alpha})";
    }

    public function rgba(float $alpha): string
    {
        return $this->withAlpha($alpha);
    }

    public function family(): string
    {
        preg_match('/^([A-Za-z]+)\d+$/', $this->name, $matches);

        return isset($matches[1]) ? strtolower($matches[1]) : strtolower($this->name);
    }

    public function shadeNumber(): int
    {
        preg_match('/(\d+)$/', $this->name, $matches);

        return isset($matches[1]) ? (int) $matches[1] : 0;
    }

    public function shade(int $shade): self
    {
        $target = ucfirst($this->family()).$shade;

        foreach (self::cases() as $case) {
            if ($case->name === $target) {
                return $case;
            }
        }

        return $this;
    }

    public function lighter(int $steps = 1): self
    {
        $current = array_search($this->shadeNumber(), self::SHADES, true);

        if ($current === false) {
            return $this;
        }

        $target = max(0, (int) $current - $steps);

        return $this->shade(self::SHADES[$target]);
    }

    public function darker(int $steps = 1): self
    {
        $current = array_search($this->shadeNumber(), self::SHADES, true);

        if ($current === false) {
            return $this;
        }

        $target = min(count(self::SHADES) - 1, (int) $current + $steps);

        return $this->shade(self::SHADES[$target]);
    }

    /** @return list<self> */
    public static function ramp(string $family): array
    {
        $prefix = ucfirst(strtolower($family));
        $result = [];

        foreach (self::SHADES as $shade) {
            $name = $prefix.$shade;
            foreach (self::cases() as $case) {
                if ($case->name === $name) {
                    $result[] = $case;
                    break;
                }
            }
        }

        return $result;
    }
}
