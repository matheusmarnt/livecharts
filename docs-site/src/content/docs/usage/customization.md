---
title: Color Customization
description: Per-element theme-aware colors, palettes, shade ramping, opacity, and typography.
---

LiveCharts v2.6+ ships a full Tailwind v4 color token system. Every color slot accepts a `dark` / `light` pair — the chart paints the correct hex on first render and swaps live when the app's theme toggles, without a Livewire roundtrip.

## TwColor enum

`TwColor` covers all 22 standard Tailwind v4 families plus 4 LiveCharts-specific extensions (`Taupe`, `Mauve`, `Mist`, `Olive`), each with 11 shades (50 → 950), plus `Black`, `White`, and `Transparent` sentinels — **289 cases total**.

```php
use Matheusmarnt\LiveCharts\Enums\TwColor;

TwColor::Amber500->value;      // '#f59e0b'
TwColor::Slate900->value;      // '#0f172a'
TwColor::White->value;         // '#ffffff'
```

## Named-arg color API

Every color slot accepts `dark:` and `light:` named arguments:

```php
->titleColor(dark: TwColor::Amber300, light: TwColor::Amber600)
```

Pass a single value to use the same hex for both themes:

```php
->titleColor(TwColor::Slate500)     // same color in dark and light
->titleColor('#6b7280')             // hex string BC — same color both themes
```

## Per-element slots

| Method | What it colors |
|---|---|
| `titleColor()` | Chart title text |
| `subtitleColor()` | Subtitle text (ApexCharts) |
| `legendColor()` | Legend label text |
| `labelsColor()` | Axis tick labels |
| `tooltipColor()` | Tooltip text |
| `axisColor()` | Axis borders and ticks |
| `gridColor()` | Grid lines |
| `dataLabelsColor()` | Data label text (datalabels plugin) |
| `backgroundColor()` | Chart canvas background |

```php
LiveCharts::bar()
    ->titleColor(dark: TwColor::Slate100, light: TwColor::Slate900)
    ->subtitleColor(TwColor::Slate500)
    ->legendColor(dark: TwColor::Slate200, light: TwColor::Slate700)
    ->labelsColor(dark: TwColor::Slate400, light: TwColor::Slate600)
    ->gridColor(dark: TwColor::Slate800, light: TwColor::Slate200)
    ->backgroundColor(dark: TwColor::Slate950, light: TwColor::White);
```

## Dataset colors

Split background and border per dataset with theme-aware pairs:

```php
use Matheusmarnt\LiveCharts\Charts\Dataset;

$chart->datasets([
    Dataset::make('Revenue')
        ->data([100, 200, 150])
        ->backgroundColor(dark: TwColor::Emerald400, light: TwColor::Emerald600)
        ->borderColor(dark: TwColor::Emerald300, light: TwColor::Emerald700),
]);
```

The `color()` sugar still works for single-value (both themes same):

```php
Dataset::make('Revenue')->data([...])->color(TwColor::Emerald500);
Dataset::make('Revenue')->data([...])->color('#10b981'); // hex BC
```

## Palettes

`->palette()` auto-fills dataset colors from a preset theme-aware scheme:

```php
use Matheusmarnt\LiveCharts\Enums\TwPalette;

LiveCharts::line()->palette(TwPalette::Vibrant);
```

| Palette | Colors |
|---|---|
| `Vibrant` | Red, Amber, Emerald, Sky, Violet, Pink (500/400 split) |
| `Muted` | Slate, Stone, Zinc, Neutral, Gray (600/400 split) |
| `Monochrome` | Slate ramp 200 → 700 |
| `Pastel` | Soft 200-shade variants of Vibrant |
| `Neon` | Lime, Cyan, Fuchsia, Yellow, Emerald (400 shades) |

## Shade ramping

Navigate shades programmatically:

```php
TwColor::Sky500->lighter(2);       // Sky300
TwColor::Sky500->darker(1);        // Sky600
TwColor::Sky500->shade(200);       // Sky200
TwColor::Sky500->family();         // 'sky'
TwColor::Sky500->shadeNumber();    // 500

TwColor::ramp('emerald');          // [Emerald50, ..., Emerald950]
```

`lighter()` and `darker()` clamp at the boundary shades (50 / 950).

## Opacity / alpha

Apply alpha to any `TwColor` value:

```php
TwColor::Emerald500->withAlpha(0.6);  // 'rgba(16,185,129,0.6)'
TwColor::Emerald500->rgba(0.4);       // same, alias
```

Use in dataset background for a semi-transparent fill:

```php
Dataset::make('Revenue')
    ->backgroundColor(
        dark:  TwColor::Emerald400->withAlpha(0.5),
        light: TwColor::Emerald600->withAlpha(0.5),
    )
    ->borderColor(dark: TwColor::Emerald300, light: TwColor::Emerald700);
```

## Typography

Set font size, weight, and family for title, legend, and tooltip:

```php
LiveCharts::line()
    ->titleFont(size: 18, weight: 'bold', family: 'Inter')
    ->legendFont(size: 12, family: 'Inter')
    ->tooltipFont(family: 'Inter');
```

## Full example

```php
use Matheusmarnt\LiveCharts\Charts\Dataset;
use Matheusmarnt\LiveCharts\Enums\{TwColor, ThemeMode, TwPalette};
use Matheusmarnt\LiveCharts\Facades\LiveCharts;

$chart = LiveCharts::line()
    ->title('Revenue')
    ->subtitle('Last 30 days')
    ->theme(ThemeMode::Auto)
    ->palette(TwPalette::Vibrant)

    // per-element colors
    ->titleColor(dark: TwColor::Amber300, light: TwColor::Amber600)
    ->legendColor(dark: TwColor::Slate200, light: TwColor::Slate700)
    ->labelsColor(dark: TwColor::Slate400, light: TwColor::Slate600)
    ->gridColor(dark: TwColor::Slate800, light: TwColor::Slate200)
    ->tooltipColor(dark: TwColor::White, light: TwColor::Slate900)
    ->backgroundColor(dark: TwColor::Slate900, light: TwColor::White)

    // typography
    ->titleFont(size: 18, weight: 'bold', family: 'Inter')
    ->legendFont(size: 12, family: 'Inter')

    // datasets
    ->datasets([
        Dataset::make('Direct')
            ->data($direct)
            ->backgroundColor(
                dark:  TwColor::Emerald400->withAlpha(0.6),
                light: TwColor::Emerald600->withAlpha(0.6),
            )
            ->borderColor(dark: TwColor::Emerald300, light: TwColor::Emerald700),

        Dataset::make('Affiliate')
            ->data($affiliate)
            ->backgroundColor(
                dark:  TwColor::Sky400->withAlpha(0.6),
                light: TwColor::Sky600->withAlpha(0.6),
            )
            ->borderColor(dark: TwColor::Sky300, light: TwColor::Sky700),
    ]);
```

## Migration from hex-only

Existing hex strings keep working — no changes required:

```php
// still valid — resolves to same-hex ColorValue (both themes identical)
->colors(['#3B82F6', '#10b981'])
->dataset('S', [...])->color('#ef4444')
```

To adopt theme-aware colors, replace hex strings with `TwColor` named-arg pairs.
See the [Color Tokens reference](/reference/color-tokens) for the full `TwColor` case list.
