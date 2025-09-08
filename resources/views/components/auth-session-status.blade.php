@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'p-4 mb-4 bg-green-50 border border-green-200 rounded-lg']) }}>
        <div class="flex items-center">
            <i class="fas fa-check-circle text-green-500 mr-3"></i>
            <p class="font-medium text-sm text-green-800">{{ $status }}</p>
        </div>
    </div>
@endif
