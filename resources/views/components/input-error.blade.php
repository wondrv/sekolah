@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-sm text-red-600 space-y-1']) }}>
        @foreach ((array) $messages as $message)
            <li class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                {{ $message }}
            </li>
        @endforeach
    </ul>
@endif
