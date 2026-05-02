@php
    $assetManager = app(Matheusmarnt\LiveCharts\Support\AssetManager::class);
    $scripts = $assetManager->getRequiredScripts();
    $assetManager->markAsRendered();
@endphp

@foreach($scripts as $script)
    <script src="{{ $script }}" defer></script>
@endforeach

@unless(config('livecharts.assets.auto_inject', true) === false)
    <script>
        {!! file_get_contents(__DIR__.'/../../resources/js/livecharts.js') !!}
    </script>
@endunless
