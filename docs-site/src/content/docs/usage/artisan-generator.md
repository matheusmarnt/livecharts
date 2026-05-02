---
title: Artisan Generator
description: Speed up your workflow with the make:chart command.
---

Generate a new chart class in your `app/Charts` directory:

```bash
php artisan make:chart UserRetentionChart
```

## Options

### Type
Specify the default chart type:

```bash
php artisan make:chart SalesChart --type=bar
```

### Engine
Specify a specific rendering engine:

```bash
php artisan make:chart AnalyticsChart --engine=chartjs
```

Supported types: `line`, `bar`, `area`, `pie`, `donut`, `radar`, `scatter`, `bubble`, `heatmap`, `treemap`, `candlestick`, `rangeBar`, `radialBar`, `polarArea`.
