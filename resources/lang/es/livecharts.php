<?php

declare(strict_types=1);

return [
    'exceptions' => [
        'invalid_chart_type' => 'El tipo de gráfico [:type] no es compatible con el motor [:engine]. Tipos compatibles: :supported.',
        'empty_dataset_chart' => 'El gráfico del tipo [:type] no tiene datasets. Añade al menos un dataset mediante dataset() o datasets().',
        'empty_dataset_named' => 'El dataset [:name] no tiene puntos de datos. Proporciona valores mediante Dataset::data().',
        'data_shape_mismatch' => 'El conteo de datos del dataset [:name] no coincide: se esperaban :expected puntos (según las etiquetas), se obtuvieron :actual.',
        'unknown_engine' => 'Motor [:name] no registrado. Disponibles: [:registered].',
        'no_engine_for_type' => 'El tipo de gráfico [:type] no es compatible con ningún motor registrado.',
    ],

    'install' => [
        'starting' => 'Instalando LiveCharts...',
        'completed' => 'LiveCharts se instaló correctamente.',
        'publishing_assets' => 'Publicando assets...',
        'overwrite_js' => 'livecharts.js ya existe. ¿Sobrescribir?',
        'js_published' => 'Assets publicados en [:path]',
        'publish_stubs' => '¿Publicar stubs de clases de gráfico en stubs/livecharts?',
        'stubs_published' => 'Stubs publicados en [:path]',
    ],

    'preview' => [
        'opening_at' => 'Abriendo la previsualización en: :url',
        'serve_warning' => 'Nota: asegúrate de que tu servidor local esté en ejecución (php artisan serve).',
        'open_failed' => 'No se pudo abrir el navegador automáticamente (:error). Abre la URL manualmente.',
    ],
];
