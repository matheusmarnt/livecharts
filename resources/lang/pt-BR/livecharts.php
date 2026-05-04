<?php

declare(strict_types=1);

return [
    'exceptions' => [
        'invalid_chart_type' => 'Tipo de gráfico [:type] não é suportado pelo motor [:engine]. Tipos suportados: :supported.',
        'empty_dataset_chart' => 'Gráfico do tipo [:type] não possui datasets. Adicione ao menos um dataset via dataset() ou datasets().',
        'empty_dataset_named' => 'Dataset [:name] não possui pontos de dados. Informe valores via Dataset::data().',
        'data_shape_mismatch' => 'Contagem de dados do dataset [:name] não confere: esperados :expected pontos (de acordo com os rótulos), recebidos :actual.',
        'unknown_engine' => 'Motor [:name] não registrado. Disponíveis: [:registered].',
        'no_engine_for_type' => 'Tipo de gráfico [:type] não é suportado por nenhum motor registrado.',
    ],

    'install' => [
        'starting' => 'Instalando o LiveCharts...',
        'completed' => 'LiveCharts instalado com sucesso.',
        'publishing_assets' => 'Publicando assets...',
        'overwrite_js' => 'livecharts.js já existe. Sobrescrever?',
        'publishing_vendor_assets' => 'Publicando assets JS do fornecedor...',
        'vendor_assets_published' => 'Assets JS publicados em public/vendor/livecharts/js',
        'js_published' => 'Assets publicados em [:path]',
        'publish_stubs' => 'Publicar stubs de classes de gráfico em stubs/livecharts?',
        'stubs_published' => 'Stubs publicados em [:path]',
    ],

    'preview' => [
        'opening_at' => 'Abrindo preview em: :url',
        'serve_warning' => 'Atenção: certifique-se de que seu servidor local está rodando (php artisan serve).',
        'open_failed' => 'Não foi possível abrir o navegador automaticamente (:error). Abra a URL manualmente.',
    ],
];
