# LiveCharts

[![Latest Version on Packagist](https://img.shields.io/packagist/v/matheusmarnt/livecharts.svg?style=flat-square)](https://packagist.org/packages/matheusmarnt/livecharts)
[![GitHub Tests Action Status](https://github.com/matheusmarnt/livecharts/actions/workflows/run-tests.yml/badge.svg)](https://github.com/matheusmarnt/livecharts/actions/workflows/run-tests.yml)
[![GitHub Code Style Action Status](https://github.com/matheusmarnt/livecharts/actions/workflows/fix-php-code-style-issues.yml/badge.svg)](https://github.com/matheusmarnt/livecharts/actions/workflows/fix-php-code-style-issues.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/matheusmarnt/livecharts.svg?style=flat-square)](https://packagist.org/packages/matheusmarnt/livecharts)

Eleve sua visualização de dados com gráficos reativos no Laravel.

LiveCharts é uma camada de abstração unificada para gráficos no framework Laravel. Ele elimina a fricção de integrar bibliotecas de gráficos JavaScript ao oferecer uma API puramente PHP para definir, configurar e renderizar gráficos — abstraindo ApexCharts e Chart.js — e entregando-os ao navegador por meio de um único componente Livewire.

## Instalação

Você pode instalar o pacote via composer:

```bash
composer require matheusmarnt/livecharts
```

Você pode publicar o arquivo de configuração com:

```bash
php artisan vendor:publish --tag="livecharts-config"
```

## Uso

```php
use LiveCharts\Facades\LiveCharts;

$chart = LiveCharts::make()
    ->type('line')
    ->title('Receita Mensal')
    ->labels(['Jan', 'Fev', 'Mar'])
    ->dataset('2024', [100, 200, 150]);
```

Em sua view Blade:

```blade
<livewire:livecharts :chart="$chart" />
```

## Testes

```bash
composer test
```

## Changelog

Por favor, veja [CHANGELOG](CHANGELOG.md) para mais informações sobre o que mudou recentemente.

## Contribuindo

Por favor, veja [CONTRIBUTING](CONTRIBUTING.md) para detalhes.

## Segurança

Se você descobrir qualquer problema relacionado à segurança, por favor, envie um e-mail para matheusmarnt@gmail.com em vez de usar o rastreador de problemas.

## Créditos

- [Matheus Mariano](https://github.com/matheusmarnt)
- [Todos os Contribuidores](../../contributors)

## Licença

The MIT License (MIT). Por favor, veja [Arquivo de Licença](LICENSE.md) para mais informações.
