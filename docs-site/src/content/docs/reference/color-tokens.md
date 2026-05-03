---
title: Color Tokens
description: Full TwColor enum reference — all Tailwind v4 families and shades available in LiveCharts.
---

`TwColor` is a PHP backed string enum where each case value is the hex color for that shade.

## Usage

```php
use Matheusmarnt\LiveCharts\Enums\TwColor;

TwColor::Amber500->value;          // '#f59e0b'
TwColor::Amber500->hex();          // same — alias
TwColor::Amber500->withAlpha(0.5); // 'rgba(245,158,11,0.5)'
TwColor::Amber500->family();       // 'amber'
TwColor::Amber500->shadeNumber();  // 500
TwColor::Amber500->lighter(1);     // TwColor::Amber400
TwColor::Amber500->darker(1);      // TwColor::Amber600
TwColor::ramp('amber');            // [Amber50, Amber100, ..., Amber950]
```

## Sentinels

| Case | Hex |
|---|---|
| `Black` | `#000000` |
| `White` | `#ffffff` |
| `Transparent` | `transparent` |

## Standard Tailwind v4 families

Each family has 11 shades: `50, 100, 200, 300, 400, 500, 600, 700, 800, 900, 950`.

| Family | 500 shade | Notes |
|---|---|---|
| `Red` | `#ef4444` | |
| `Orange` | `#f97316` | |
| `Amber` | `#f59e0b` | |
| `Yellow` | `#eab308` | |
| `Lime` | `#84cc16` | |
| `Green` | `#22c55e` | |
| `Emerald` | `#10b981` | |
| `Teal` | `#14b8a6` | |
| `Cyan` | `#06b6d4` | |
| `Sky` | `#0ea5e9` | |
| `Blue` | `#3b82f6` | |
| `Indigo` | `#6366f1` | |
| `Violet` | `#8b5cf6` | |
| `Purple` | `#a855f7` | |
| `Fuchsia` | `#d946ef` | |
| `Pink` | `#ec4899` | |
| `Rose` | `#f43f5e` | |
| `Slate` | `#64748b` | Recommended for UI chrome |
| `Gray` | `#6b7280` | |
| `Zinc` | `#71717a` | |
| `Neutral` | `#737373` | |
| `Stone` | `#78716c` | |

## Extended families (LiveCharts-specific)

| Family | 500 shade | Character |
|---|---|---|
| `Taupe` | `#8c7b6b` | Warm greige |
| `Mauve` | `#9b7fa6` | Muted purple-rose |
| `Mist` | `#6b8fa6` | Cool blue-grey |
| `Olive` | `#7a8c5a` | Earthy green-yellow |

These use the same OKLCH-approach as Tailwind v4 but are LiveCharts-specific extensions, not part of the official Tailwind palette.

## Shade reference for `Slate`

| Case | Hex | Use |
|---|---|---|
| `Slate50` | `#f8fafc` | Background light mode |
| `Slate100` | `#f1f5f9` | Surface light |
| `Slate200` | `#e2e8f0` | Grid / border light |
| `Slate300` | `#cbd5e1` | Muted text light |
| `Slate400` | `#94a3b8` | Labels light |
| `Slate500` | `#64748b` | Neutral text |
| `Slate600` | `#475569` | Labels dark → use as `light:` |
| `Slate700` | `#334155` | Muted dark |
| `Slate800` | `#1e293b` | Grid dark |
| `Slate900` | `#0f172a` | Background dark mode |
| `Slate950` | `#020617` | Deepest dark |

## Recommended dark/light pairs

| Element | `dark:` | `light:` | Notes |
|---|---|---|---|
| Title | `Amber300` | `Amber700` | |
| Legend | `Slate200` | `Slate700` | |
| Axis labels | `Slate400` | `Slate600` | |
| Grid | `Slate800` | `Slate200` | |
| Tooltip text | `White` | `Slate900` | ApexCharts: applied via CSS injection |
| Chart background | `Slate900` | `White` | |
