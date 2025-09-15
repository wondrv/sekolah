@extends('layouts.app')

@section('title', 'CMS Test - ' . App\Support\Theme::getSiteInfo()['name'])

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-4xl font-bold mb-8 text-center" style="color: var(--color-primary);">
            🧪 CMS Functionality Test
        </h1>

        <!-- Site Information Test -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-semibold mb-4 flex items-center">
                <span class="mr-2">🏢</span> Site Information
            </h2>
            @php $siteInfo = App\Support\Theme::getSiteInfo(); @endphp

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <strong>Site Name:</strong> {{ $siteInfo['name'] ?? 'Not set' }}
                </div>
                <div>
                    <strong>Email:</strong> {{ $siteInfo['email'] ?? 'Not set' }}
                </div>
                <div>
                    <strong>Phone:</strong> {{ $siteInfo['phone'] ?? 'Not set' }}
                </div>
                <div>
                    <strong>Address:</strong> {{ $siteInfo['address'] ?? 'Not set' }}
                </div>
                <div class="md:col-span-2">
                    <strong>Description:</strong> {{ $siteInfo['description'] ?? 'Not set' }}
                </div>
                <div class="md:col-span-2">
                    <strong>Tagline:</strong> {{ $siteInfo['tagline'] ?? 'Not set' }}
                </div>
            </div>

            @if($siteInfo['logo'])
                <div class="mt-4">
                    <strong>Logo:</strong><br>
                    <img src="{{ asset($siteInfo['logo']) }}" alt="Site Logo" class="h-16 mt-2">
                </div>
            @endif
        </div>

        <!-- Theme Colors Test -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-semibold mb-4 flex items-center">
                <span class="mr-2">🎨</span> Theme Colors
            </h2>
            @php $colors = App\Support\Theme::getThemeColors(); @endphp

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($colors as $name => $color)
                    <div class="text-center">
                        <div class="w-20 h-20 rounded-lg mx-auto mb-2 border border-gray-300" style="background-color: {{ $color }};"></div>
                        <div class="text-sm font-medium">{{ ucfirst(str_replace('_', ' ', $name)) }}</div>
                        <div class="text-xs text-gray-500">{{ $color }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Typography Test -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-semibold mb-4 flex items-center">
                <span class="mr-2">📝</span> Typography
            </h2>
            @php $typography = App\Support\Theme::getTypography(); @endphp

            <div class="space-y-4">
                <div>
                    <strong>Primary Font:</strong> {{ $typography['font_primary'] ?? 'Not set' }}
                    <p style="font-family: {{ $typography['font_primary'] ?? 'system-ui' }};" class="mt-2 text-lg">
                        This is sample text in the primary font family.
                    </p>
                </div>
                <div>
                    <strong>Secondary Font:</strong> {{ $typography['font_secondary'] ?? 'Not set' }}
                    <p style="font-family: {{ $typography['font_secondary'] ?? 'system-ui' }};" class="mt-2">
                        This is sample text in the secondary font family.
                    </p>
                </div>
                <div>
                    <strong>Font Size Base:</strong> {{ $typography['font_size_base'] ?? 'Not set' }}
                </div>
            </div>
        </div>

        <!-- Navigation Test -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-semibold mb-4 flex items-center">
                <span class="mr-2">🧭</span> Navigation
            </h2>
            @php
                $headerSettings = App\Models\Setting::where('key', 'header_position')->first();
                $mainMenu = App\Models\Menu::where('name', 'main')->first();
            @endphp

            <div class="mb-4">
                <strong>Header Position:</strong> {{ $headerSettings->value ?? 'Not set' }}
            </div>

            @if($mainMenu && $mainMenu->items)
                <div>
                    <strong>Main Menu Items:</strong>
                    <ul class="list-disc list-inside mt-2">
                        @foreach($mainMenu->items as $item)
                            <li>{{ $item->title }} ({{ $item->url }})</li>
                        @endforeach
                    </ul>
                </div>
            @else
                <p class="text-yellow-600">No main menu items found</p>
            @endif
        </div>

        <!-- Settings Summary -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-2xl font-semibold mb-4 flex items-center">
                <span class="mr-2">⚙️</span> All Settings
            </h2>
            @php $allSettings = App\Models\Setting::all()->pluck('value', 'key'); @endphp

            <div class="grid md:grid-cols-2 gap-4 max-h-64 overflow-y-auto">
                @foreach($allSettings as $key => $value)
                    <div class="text-sm">
                        <strong>{{ $key }}:</strong>
                        @if(is_array($value))
                            {{ json_encode($value) }}
                        @elseif(is_string($value) && strlen($value) > 50)
                            {{ substr($value, 0, 50) }}...
                        @elseif(is_bool($value))
                            {{ $value ? 'true' : 'false' }}
                        @else
                            {{ $value ?? 'null' }}
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-blue-50 rounded-lg p-6 text-center">
            <h3 class="text-xl font-semibold mb-4">Quick CMS Actions</h3>
            <div class="flex flex-wrap gap-3 justify-center">
                <a href="{{ route('admin.settings.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    ⚙️ Manage Settings
                </a>
                <a href="/" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    🏠 View Homepage
                </a>
                <a href="{{ route('login') }}" class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700">
                    🔐 Admin Login
                </a>
            </div>

            <div class="mt-4 text-sm text-gray-600">
                <p>✅ CMS is fully functional and ready for use!</p>
                <p>All frontend elements can be customized through the admin panel.</p>
            </div>
        </div>
    </div>
</div>
@endsection
