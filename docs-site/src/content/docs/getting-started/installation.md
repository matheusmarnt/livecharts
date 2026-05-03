---
title: Installation
description: How to install and configure LiveCharts.
---

## Composer

Install the package via composer:

```bash
composer require matheusmarnt/livecharts
```

## Install Assets

Run the installation command to publish the configuration and essential assets:

```bash
php artisan livecharts:install
```

## Register Scripts

Add `@liveChartsScripts` **before `@livewireScripts`**. The LiveCharts JS runtime registers an Alpine component and must load before Livewire's bundled Alpine initializes:

```html
<body>
    <!-- your chart components here -->
    @liveChartsScripts
    @livewireScripts
</body>
```

:::tip[Using a shared layout with `@extends`?]
When your app uses Blade layouts (`@extends` / `@section`), you can place `@liveChartsScripts` in the layout's `<head>`. The directive uses Blade's push/stack mechanism, so scripts pushed by chart components in child sections are captured before the layout renders.

```html
<!-- layouts/app.blade.php -->
<head>
    @liveChartsScripts
</head>
```
:::

This directive injects the required charting engine scripts (CDN or local) and the Alpine.js integration bundle.
