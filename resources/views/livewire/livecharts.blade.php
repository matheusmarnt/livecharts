<div
    id="{{ $id }}"
    class="livecharts-container {{ $class }}"
    x-data="livecharts({
        id: '{{ $id }}',
        options: {{ json_encode($options) }},
        constructor: '{{ $adapter->jsConstructor() }}',
        payload: {{ json_encode($payload) }}
    })"
    wire:ignore
    @if($payload['pollEvery'] > 0) wire:poll.{{ $payload['pollEvery'] }}ms @endif
>
    <div x-ref="chart"></div>
</div>
