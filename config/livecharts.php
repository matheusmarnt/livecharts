<?php

use Matheusmarnt\LiveCharts\Engines\ApexChartsAdapter;
use Matheusmarnt\LiveCharts\Engines\ChartJsAdapter;

return [
    /*
    |--------------------------------------------------------------------------
    | Default Engine
    |--------------------------------------------------------------------------
    |
    | This option controls the default chart engine that will be used to
    | render your charts. Supported: "apexcharts", "chartjs"
    |
    */
    'engine' => env('LIVECHARTS_ENGINE', 'apexcharts'),

    /*
    |--------------------------------------------------------------------------
    | Theme Settings
    |--------------------------------------------------------------------------
    |
    | "mode" can be "auto", "light", or "dark".
    | "auto_detect" can be "class" (Tailwind default) or "media".
    |
    */
    'theme' => [
        'mode' => 'auto',
        'auto_detect' => 'class',
    ],

    /*
    |--------------------------------------------------------------------------
    | Chart Defaults
    |--------------------------------------------------------------------------
    */
    'defaults' => [
        'height' => 350,
        'toolbar' => false,
        'zoom' => false,
        'legend' => true,
        'tooltip' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Engine Adapters
    |--------------------------------------------------------------------------
    */
    'engines' => [
        'apexcharts' => ApexChartsAdapter::class,
        'chartjs' => ChartJsAdapter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Assets
    |--------------------------------------------------------------------------
    */
    'assets' => [
        'auto_inject' => true,
        'cdn' => [
            'apexcharts' => 'https://cdn.jsdelivr.net/npm/apexcharts@5.10.6',
            'chartjs' => 'https://cdn.jsdelivr.net/npm/chart.js@4.5.1/dist/chart.umd.min.js',
        ],
    ],
];
