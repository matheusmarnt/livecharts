<?php

declare(strict_types=1);

return [
    'exceptions' => [
        'invalid_chart_type' => 'Tipo de gráfico [:type] não é suportado pelo motor [:engine]. Tipos suportados: :supported.',
        'empty_dataset_chart' => 'Gráfico do tipo [:type] não possui datasets. Adicione ao menos um dataset via dataset() ou datasets().',
        'empty_dataset_named' => 'Dataset [:name] não possui pontos de dados. Informe valores via Dataset::data().',
        'data_shape_mismatch' => 'Contagem de dados do dataset [:name] não confere: esperados :expected pontos (de acordo com os rótulos), recebidos :actual.',
        'unknown_engine' => 'Motor [:name] não registrado. Disponíveis: [:registered].',
    ],

    'install' => [
        'starting' => 'Instalando o LiveCharts...',
        'completed' => 'LiveCharts instalado com sucesso.',
        'publishing_assets' => 'Publicando assets...',
        'overwrite_js' => 'livecharts.js já existe. Sobrescrever?',
        'js_published' => 'Assets publicados em [:path]',
    ],

    'preview' => [
        'confirm_register' => 'Isto irá registrar uma rota web temporária [/livecharts/preview]. Continuar?',
        'registering' => 'Registrando rota de preview...',
        'opening_at' => 'Abrindo preview em: :url',
        'serve_warning' => 'Atenção: certifique-se de que seu servidor local está rodando (php artisan serve).',
    ],
];
