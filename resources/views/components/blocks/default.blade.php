@props(['block', 'data' => []])

<div class="block-default p-4 border-2 border-dashed border-gray-300 bg-gray-50">
    <div class="text-center">
        <h3 class="text-lg font-semibold text-gray-600">{{ $block->name ?? 'Unnamed Block' }}</h3>
        <p class="text-sm text-gray-500">Block type: {{ $block->type }}</p>
        <p class="text-xs text-gray-400">No component found for this block type</p>

        @if(!empty($data))
            <details class="mt-2">
                <summary class="text-xs text-gray-500 cursor-pointer">Show block data</summary>
                <pre class="text-xs text-left mt-2 bg-white p-2 rounded border overflow-auto max-h-32">{{ json_encode($data, JSON_PRETTY_PRINT) }}</pre>
            </details>
        @endif
    </div>
</div>
