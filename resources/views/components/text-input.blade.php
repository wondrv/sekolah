@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'block w-full px-4 py-3 text-gray-900 border border-gray-200 rounded-lg bg-white/50 backdrop-blur-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-200 focus:bg-white transition-all duration-200 placeholder-gray-500']) }}>
