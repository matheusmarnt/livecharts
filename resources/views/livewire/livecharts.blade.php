<div
    {{ $attributes->merge([
        'x-data' => "livecharts({
            id: '{$id}',
            options: " . json_encode($options) . ",
            constructor: '{$adapter->jsConstructor()}',
            payload: " . json_encode($payload) . "
        })",
        'id' => $id,
        'class' => 'livecharts-container ' . $class,
    ]) }}
    wire:ignore
    @if($payload['pollEvery'] > 0)
        wire:poll.{{ $payload['pollEvery'] }}ms
    @endif
>
    <div x-ref="chart"></div>
</div>
