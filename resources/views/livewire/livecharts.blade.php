<div
    x-data="livecharts({
        id: '{{ $id }}',
        options: @js($options),
        constructor: '{{ $adapter->jsConstructor() }}',
        payload: @js($payload)
    })"
    id="{{ $id }}"
    class="livecharts-container {{ $class }}"
    wire:ignore
    @if($payload['pollEvery'] > 0)
        wire:poll.{{ $payload['pollEvery'] }}ms
    @endif
>
    <div x-ref="chart"></div>
</div>
