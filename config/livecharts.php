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
            'chartjs-treemap' => 'https://cdn.jsdelivr.net/npm/chartjs-chart-treemap@3.0.0/dist/chartjs-chart-treemap.min.js',
            'chartjs-matrix' => 'https://cdn.jsdelivr.net/npm/chartjs-chart-matrix@2.0.1/dist/chartjs-chart-matrix.min.js',
            'chartjs-sankey' => 'https://cdn.jsdelivr.net/npm/chartjs-chart-sankey@0.12.0/dist/chartjs-chart-sankey.min.js',
            'chartjs-financial' => 'https://cdn.jsdelivr.net/npm/chartjs-chart-financial@0.2.0/dist/chartjs-chart-financial.min.js',
            'chartjs-luxon' => 'https://cdn.jsdelivr.net/npm/luxon@3.4.4/build/global/luxon.min.js',
            'chartjs-adapter-luxon' => 'https://cdn.jsdelivr.net/npm/chartjs-adapter-luxon@1.3.1/dist/chartjs-adapter-luxon.umd.min.js',
        ],
    ],
];
