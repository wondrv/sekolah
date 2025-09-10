<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin Panel</title>

    <!-- Tailwind CSS -->
    <link href="{{ asset('css/tailwind.css') }}" rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-white" x-data="{ open: false }">
            <div class="p-4">
                <h2 class="text-xl font-bold">Admin Panel</h2>
                <p class="text-sm text-gray-300">{{ auth()->user()->name }}</p>
            </div>

            <nav class="mt-4">
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : '' }}">
                    📊 Dashboard
                </a>

                <!-- Working Settings Link -->
                <a href="{{ route('admin.settings.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-700 {{ request()->routeIs('admin.settings.*') ? 'bg-gray-700' : '' }}">
                    ⚙️ Settings
                </a>

                <!-- View Public Site -->
                <a href="{{ route('home') }}" target="_blank" class="block px-4 py-2 text-sm hover:bg-gray-700">
                    🌐 View Website
                </a>

                <!-- Coming Soon Section -->
                <div class="mt-4 pt-4 border-t border-gray-700">
                    <div class="px-4 py-2 text-xs text-gray-400 uppercase tracking-wide">
                        Content Management - Coming Soon
                    </div>

                    <div class="px-4 py-2 text-sm text-gray-500 cursor-not-allowed opacity-50">
                        📰 Berita & Artikel
                    </div>
                    <div class="px-4 py-2 text-sm text-gray-500 cursor-not-allowed opacity-50">
                        📄 Halaman
                    </div>
                    <div class="px-4 py-2 text-sm text-gray-500 cursor-not-allowed opacity-50">
                        📅 Event & Kegiatan
                    </div>
                    <div class="px-4 py-2 text-sm text-gray-500 cursor-not-allowed opacity-50">
                        🖼️ Galeri
                    </div>
                    <div class="px-4 py-2 text-sm text-gray-500 cursor-not-allowed opacity-50">
                        🏫 Fasilitas
                    </div>
                    <div class="px-4 py-2 text-sm text-gray-500 cursor-not-allowed opacity-50">
                        🎯 Program Unggulan
                    </div>
                    <div class="px-4 py-2 text-sm text-gray-500 cursor-not-allowed opacity-50">
                        🏆 Prestasi
                    </div>
                    <div class="px-4 py-2 text-sm text-gray-500 cursor-not-allowed opacity-50">
                        💬 Testimoni
                    </div>
                </div>

                <div class="border-t border-gray-700 mt-4 pt-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-700">
                            🚪 Logout
                        </button>
                    </form>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <h1 class="text-2xl font-semibold text-gray-900">
                            @yield('title', 'Dashboard')
                        </h1>

                        <div class="flex items-center space-x-4">
                            <a href="{{ route('home') }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                Lihat Website
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6">
                @if (session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
