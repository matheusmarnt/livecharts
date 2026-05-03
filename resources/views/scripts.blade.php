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
    @php($bootstrap = $assetManager->getBootstrapScript())
    @if($bootstrap)
        <script>{!! $bootstrap !!}</script>
    @endif
@endunless
