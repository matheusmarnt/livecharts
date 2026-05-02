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

Add the `@liveChartsScripts` directive to your layout's `<head>` or before the closing `</body>` tag:

```html
<head>
    <!-- ... -->
    @liveChartsScripts
</head>
```

This directive will automatically inject the required charting engine CDNs (or local files) and the Alpine.js integration logic.
