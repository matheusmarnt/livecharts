<?php

declare(strict_types=1);

return [
    'exceptions' => [
        'invalid_chart_type' => 'Chart type [:type] is not supported by engine [:engine]. Supported types: :supported.',
        'empty_dataset_chart' => 'Chart of type [:type] has no datasets. Add at least one dataset via dataset() or datasets().',
        'empty_dataset_named' => 'Dataset [:name] has no data points. Provide values via Dataset::data().',
        'data_shape_mismatch' => 'Dataset [:name] data count mismatch: expected :expected points (matching labels), got :actual.',
        'unknown_engine' => 'Engine [:name] not registered. Available: [:registered].',
    ],

    'install' => [
        'starting' => 'Installing LiveCharts...',
        'completed' => 'LiveCharts installed successfully.',
        'publishing_assets' => 'Publishing assets...',
        'overwrite_js' => 'livecharts.js already exists. Overwrite?',
        'js_published' => 'Assets published to [:path]',
        'publish_stubs' => 'Publish chart class stubs to stubs/livecharts?',
        'stubs_published' => 'Stubs published to [:path]',
    ],

    'preview' => [
        'confirm_register' => 'This will register a temporary web route [/livecharts/preview]. Proceed?',
        'registering' => 'Registering preview route...',
        'opening_at' => 'Opening preview at: :url',
        'serve_warning' => 'Note: ensure your local server is running (php artisan serve).',
    ],
];
