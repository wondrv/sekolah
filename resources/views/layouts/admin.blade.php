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
                    Dashboard
                </a>

                <div x-data="{ expanded: {{ request()->routeIs('admin.posts.*') ? 'true' : 'false' }} }">
                    <button @click="expanded = !expanded" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-700 flex items-center justify-between">
                        <span>Menu</span>
                        <svg :class="{ 'rotate-90': expanded }" class="w-4 h-4 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <div x-show="expanded" class="pl-4">
                        <a href="{{ route('admin.posts.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-700 {{ request()->routeIs('admin.posts.*') ? 'bg-gray-700' : '' }}">
                            Berita & Artikel
                        </a>
                        <a href="{{ route('admin.pages.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-700 {{ request()->routeIs('admin.pages.*') ? 'bg-gray-700' : '' }}">
                            Halaman
                        </a>
                        <a href="{{ route('admin.events.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-700 {{ request()->routeIs('admin.events.*') ? 'bg-gray-700' : '' }}">
                            Event & Kegiatan
                        </a>
                        <a href="{{ route('admin.galleries.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-700 {{ request()->routeIs('admin.galleries.*') ? 'bg-gray-700' : '' }}">
                            Galeri
                        </a>
                    </div>
                </div>

                <div x-data="{ expanded: {{ request()->routeIs('admin.facilities.*', 'admin.programs.*') ? 'true' : 'false' }} }">
                    <button @click="expanded = !expanded" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-700 flex items-center justify-between">
                        <span>Akademik & Fasilitas</span>
                        <svg :class="{ 'rotate-90': expanded }" class="w-4 h-4 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <div x-show="expanded" class="pl-4">
                        <a href="{{ route('admin.facilities.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-700 {{ request()->routeIs('admin.facilities.*') ? 'bg-gray-700' : '' }}">
                            Fasilitas
                        </a>
                        <a href="{{ route('admin.programs.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-700 {{ request()->routeIs('admin.programs.*') ? 'bg-gray-700' : '' }}">
                            Program Unggulan
                        </a>
                    </div>
                </div>

                <div x-data="{ expanded: {{ request()->routeIs('admin.achievements.*', 'admin.testimonials.*') ? 'true' : 'false' }} }">
                    <button @click="expanded = !expanded" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-700 flex items-center justify-between">
                        <span>Prestasi & Testimoni</span>
                        <svg :class="{ 'rotate-90': expanded }" class="w-4 h-4 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                    <div x-show="expanded" class="pl-4">
                        <a href="{{ route('admin.achievements.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-700 {{ request()->routeIs('admin.achievements.*') ? 'bg-gray-700' : '' }}">
                            Prestasi
                        </a>
                        <a href="{{ route('admin.testimonials.index') }}" class="block px-4 py-2 text-sm hover:bg-gray-700 {{ request()->routeIs('admin.testimonials.*') ? 'bg-gray-700' : '' }}">
                            Testimoni
                        </a>
                    </div>
                </div>

                <div class="border-t border-gray-700 mt-4 pt-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm hover:bg-gray-700">
                            Logout
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
