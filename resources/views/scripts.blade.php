@php
    $assetManager = app(Matheusmarnt\LiveCharts\Support\AssetManager::class);
    $scripts = $assetManager->getRequiredScripts();
    $assetManager->markAsRendered();
@endphp

@foreach($scripts as $script)
    @if(isset($script['fallback']))
        <script src="{{ $script['src'] }}" onerror="this.onerror=null;this.src='{{ $script['fallback'] }}';" defer></script>
    @else
        <script src="{{ $script['src'] }}" defer></script>
    @endif
@endforeach

@unless(config('livecharts.assets.auto_inject', true) === false)
    @php
        $built = __DIR__.'/../../resources/dist/livecharts.js';
        $source = __DIR__.'/../../resources/js/livecharts.js';
        $bootstrap = file_exists($built) ? $built : $source;
    @endphp
    <script>
        {!! file_get_contents($bootstrap) !!}
    </script>
@endunless
