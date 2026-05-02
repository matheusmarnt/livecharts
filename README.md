# LiveCharts

[![Latest Version on Packagist](https://img.shields.io/packagist/v/matheusmarnt/livecharts.svg?style=flat-square)](https://packagist.org/packages/matheusmarnt/livecharts)
[![GitHub Tests Action Status](https://github.com/matheusmarnt/livecharts/actions/workflows/run-tests.yml/badge.svg)](https://github.com/matheusmarnt/livecharts/actions/workflows/run-tests.yml)
[![GitHub Code Style Action Status](https://github.com/matheusmarnt/livecharts/actions/workflows/fix-php-code-style-issues.yml/badge.svg)](https://github.com/matheusmarnt/livecharts/actions/workflows/fix-php-code-style-issues.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/matheusmarnt/livecharts.svg?style=flat-square)](https://packagist.org/packages/matheusmarnt/livecharts)

Elevate your data visualization with reactive charts in Laravel.

LiveCharts is a unified chart abstraction layer for the Laravel framework. It eliminates the friction of integrating JavaScript charting libraries by providing a pure PHP API to define, configure, and render charts—abstracting ApexCharts and Chart.js—and delivering them to the browser via a single Livewire component.

## Installation

You can install the package via composer:

```bash
composer require matheusmarnt/livecharts
```

Install assets and configuration:

```bash
php artisan livecharts:install
```

## Basic Usage

### Fluent Builder

```php
use Matheusmarnt\LiveCharts\Facades\LiveCharts;

$chart = LiveCharts::line()
    ->title('Monthly Revenue')
    ->labels(['Jan', 'Feb', 'Mar'])
    ->dataset('2024', [100, 200, 150])
    ->colors(['#3B82F6']);
```

In your Blade view:

```blade
<livewire:livecharts :chart="$chart" />
```

### Class-Based Charts

Generate a new chart class:

```bash
php artisan make:chart RevenueChart --type=bar
```

Then define your logic in `app/Charts/RevenueChart.php`:

```php
namespace App\Charts;

use Matheusmarnt\LiveCharts\Charts\Chart;
use Matheusmarnt\LiveCharts\Charts\Dataset;

class RevenueChart extends Chart
{
    protected string $type = 'bar';

    public function datasets(): array
    {
        return [
            Dataset::make('Revenue')
                ->data([400, 300, 600])
                ->color('#10B981'),
        ];
    }
}
```

## Features

### Polling
Keep your charts updated automatically:

```php
$chart->pollEvery(5000); // 5 seconds
```

### Events
Interact with data points from Livewire:

```php
$chart->onDataPointClick('chart-clicked');
```

In your parent Livewire component:

```php
#[On('chart-clicked')]
public function handleChartClick($data)
{
    // $data contains: seriesIndex, dataPointIndex, value, label
}
```

### Dark Mode
Automatic dark mode detection based on Tailwind's `.dark` class.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email matheusmarnt@gmail.com instead of using the issue tracker.

## Credits

- [Matheus Mariano](https://github.com/matheusmarnt)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
