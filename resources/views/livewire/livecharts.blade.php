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
>
    <div x-ref="chart"></div>
</div>
