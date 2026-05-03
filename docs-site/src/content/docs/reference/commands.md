---
title: Artisan Commands
description: Reference for every command shipped by LiveCharts.
---

LiveCharts ships four Artisan commands. All command output is translated via `lang/{en,pt-BR,es}/livecharts.php`.

## `livecharts:install`

Interactive installer. Publishes config, assets, stubs, and language files.

```bash
php artisan livecharts:install
```

Prompts:

1. **Publish configuration?** Copies `config/livecharts.php` into your app.
2. **Publish assets?** Copies the IIFE bundles into `public/vendor/livecharts/`.
3. **Publish chart class stubs?** Copies the customizable stubs into `stubs/livecharts/` so `make:chart` uses your conventions.
4. **Publish translations?** Copies the language files into `lang/vendor/livecharts/`.

Run it once after `composer require`. Idempotent — re-running won't overwrite existing files unless you confirm.

## `livecharts:preview`

Boots the Laravel dev server, opens the in-package preview route, and launches your default browser:

```bash
php artisan livecharts:preview
```

| Flag | Default | Description |
|---|---|---|
| `--no-open` | `false` | Skip launching the browser (useful in CI/headless environments). |

The browser opener uses the OS-native command: `open` on macOS, `xdg-open` on Linux, `cmd /c start ""` on Windows. If the opener exits non-zero, the command emits the `open_failed` warning string and prints the URL so you can copy it manually.

The preview route is registered by the service provider — it's always available, not "temporary".

## `make:chart`

Scaffold a new chart class:

```bash
php artisan make:chart RevenueChart
```

| Flag | Description |
|---|---|
| `--type=<type>` | Pre-fill `protected string $type`. Options derived from `Chart::TYPES`. |
| `--engine=<engine>` | Pre-fill `protected string $engine`. Options derived from `EngineFactory::names()`. |
| `--force` | Overwrite the file if it exists. |

The stub is loaded from `stubs/livecharts/chart.stub` if you've published it via `livecharts:install`, otherwise from the package vendor directory.

## Vendor publish tags

For granular control without the interactive installer:

| Tag | Path |
|---|---|
| `livecharts-config` | `config/livecharts.php` |
| `livecharts-assets` | `public/vendor/livecharts/*` |
| `livecharts-stubs` | `stubs/livecharts/chart.stub` |
| `livecharts-lang` | `lang/vendor/livecharts/{en,pt-BR,es}/livecharts.php` |

```bash
php artisan vendor:publish --tag=livecharts-config
```
