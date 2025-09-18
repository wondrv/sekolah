<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin Panel</title>

    <!-- Tailwind CSS -->
    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Left Sidebar -->
        <div class="w-64 shadow-lg border-r border-gray-800 flex flex-col" style="background-color: #36454F;" x-data="{
                content: {{ (request()->routeIs('admin.posts.*') || request()->routeIs('admin.pages.*') || request()->routeIs('admin.events.*') || request()->routeIs('admin.galleries.*')) ? 'true' : 'false' }},
                school: {{ (request()->routeIs('admin.facilities.*') || request()->routeIs('admin.programs.*') || request()->routeIs('admin.achievements.*') || request()->routeIs('admin.testimonials.*')) ? 'true' : 'false' }},
                advanced: {{ (request()->routeIs('admin.templates.*') || request()->routeIs('admin.menus.*') || request()->routeIs('admin.settings.*')) ? 'true' : 'false' }},
                communication: {{ (request()->routeIs('admin.messages.*')) ? 'true' : 'false' }}
            }">
            <!-- Sidebar Header -->
            <div class="p-6 border-b border-gray-800">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold">🏫</span>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold text-white">Admin Panel</h1>
                        <p class="text-xs text-gray-400">{{ auth()->user()->name }}</p>
                    </div>
                </div>
            </div>

            <!-- Sidebar Navigation -->
            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}"
                   class="flex items-center px-3 py-2 text-sm font-medium rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <span class="mr-3">📊</span>
                    Dashboard
                </a>

                <!-- Content Management Dropdown -->
                <div class="space-y-1">
                    <button @click="content = !content"
                            class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg text-gray-300 hover:bg-gray-800 hover:text-white">
                        <div class="flex items-center">
                            <span class="mr-3">📄</span>
                            Content Management
                        </div>
                        <svg class="w-4 h-4 transition-transform" :class="content ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="content" x-transition class="ml-6 space-y-1">
                        <!-- Pages single link (no dropdown) -->
                        <a href="{{ route('admin.pages.index') }}" class="flex items-center px-3 py-2 text-sm text-gray-400 hover:bg-gray-800 hover:text-white rounded-lg {{ request()->routeIs('admin.pages.*') ? 'bg-blue-600 text-white' : '' }}">
                            <span class="mr-2">📄</span>
                            Halaman
                        </a>
                        <a href="{{ route('admin.galleries.index') }}" class="flex items-center px-3 py-2 text-sm text-gray-400 hover:bg-gray-800 hover:text-white rounded-lg {{ request()->routeIs('admin.galleries.*') ? 'bg-blue-600 text-white' : '' }}">
                            <span class="mr-2">🖼️</span>
                            Galeri
                        </a>
                    </div>
                </div>

                <!-- School Features Dropdown -->
                <div class="space-y-1">
                    <button @click="school = !school"
                            class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg text-gray-300 hover:bg-gray-800 hover:text-white">
                        <div class="flex items-center">
                            <span class="mr-3">🏫</span>
                            School Features
                        </div>
                        <svg class="w-4 h-4 transition-transform" :class="school ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="school" x-transition class="ml-6 space-y-1">
                        <a href="{{ route('admin.facilities.index') }}" class="flex items-center px-3 py-2 text-sm text-gray-400 hover:bg-gray-800 hover:text-white rounded-lg {{ request()->routeIs('admin.facilities.*') ? 'bg-blue-600 text-white' : '' }}">
                            <span class="mr-2">🏫</span>
                            Fasilitas
                        </a>
                        <a href="{{ route('admin.programs.index') }}" class="flex items-center px-3 py-2 text-sm text-gray-400 hover:bg-gray-800 hover:text-white rounded-lg {{ request()->routeIs('admin.programs.*') ? 'bg-blue-600 text-white' : '' }}">
                            <span class="mr-2">🎯</span>
                            Program Unggulan
                        </a>
                        <a href="{{ route('admin.achievements.index') }}" class="flex items-center px-3 py-2 text-sm text-gray-400 hover:bg-gray-800 hover:text-white rounded-lg {{ request()->routeIs('admin.achievements.*') ? 'bg-blue-600 text-white' : '' }}">
                            <span class="mr-2">🏆</span>
                            Prestasi
                        </a>
                        <a href="{{ route('admin.testimonials.index') }}" class="flex items-center px-3 py-2 text-sm text-gray-400 hover:bg-gray-800 hover:text-white rounded-lg {{ request()->routeIs('admin.testimonials.*') ? 'bg-blue-600 text-white' : '' }}">
                            <span class="mr-2">💬</span>
                            Testimoni
                        </a>
                    </div>
                </div>

                <!-- Advanced Features Dropdown -->
                <div class="space-y-1">
                    <button @click="advanced = !advanced"
                            class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg text-gray-300 hover:bg-gray-800 hover:text-white">
                        <div class="flex items-center">
                            <span class="mr-3">🛠️</span>
                            Advanced Features
                        </div>
                        <svg class="w-4 h-4 transition-transform" :class="advanced ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="advanced" x-transition class="ml-6 space-y-1">
                        <a href="{{ route('admin.templates.index') }}" class="flex items-center px-3 py-2 text-sm text-gray-400 hover:bg-gray-800 hover:text-white rounded-lg {{ request()->routeIs('admin.templates.*') ? 'bg-blue-600 text-white' : '' }}">
                            <span class="mr-2">🎨</span>
                            Template Builder
                        </a>
                        <a href="{{ route('admin.menus.index') }}" class="flex items-center px-3 py-2 text-sm text-gray-400 hover:bg-gray-800 hover:text-white rounded-lg {{ request()->routeIs('admin.menus.*') ? 'bg-blue-600 text-white' : '' }}">
                            <span class="mr-2">🧭</span>
                            Menu Management
                        </a>
                        <a href="{{ route('admin.settings.index') }}" class="flex items-center px-3 py-2 text-sm text-gray-400 hover:bg-gray-800 hover:text-white rounded-lg {{ request()->routeIs('admin.settings.*') ? 'bg-blue-600 text-white' : '' }}">
                            <span class="mr-2">⚙️</span>
                            Settings
                        </a>
                    </div>
                </div>

                <!-- Communication Dropdown -->
                <div class="space-y-1">
                    @php
                        $unreadCount = \App\Models\Message::where('status', 'unread')->count();
                        $totalNotifications = $unreadCount;
                    @endphp
                    <button @click="communication = !communication"
                            class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium rounded-lg text-gray-300 hover:bg-gray-800 hover:text-white">
                        <div class="flex items-center">
                            <span class="mr-3">📧</span>
                            Communication
                            @if($totalNotifications > 0)
                                <span class="bg-red-500 text-white text-xs rounded-full px-2 py-1 ml-2">{{ $totalNotifications }}</span>
                            @endif
                        </div>
                        <svg class="w-4 h-4 transition-transform" :class="communication ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <div x-show="communication" x-transition class="ml-6 space-y-1">
                        <a href="{{ route('admin.messages.index') }}" class="flex items-center justify-between px-3 py-2 text-sm text-gray-400 hover:bg-gray-800 hover:text-white rounded-lg {{ request()->routeIs('admin.messages.*') ? 'bg-blue-600 text-white' : '' }}">
                            <div class="flex items-center">
                                <span class="mr-2">📧</span>
                                Inbox Messages
                            </div>
                            @if($unreadCount > 0)
                                <span class="bg-red-500 text-white text-xs rounded-full px-2 py-1">{{ $unreadCount }}</span>
                            @endif
                        </a>
                    </div>
                </div>
            </nav>

            <!-- Sidebar Footer -->
            <div class="p-4 border-t border-gray-800">
                <div class="text-center">
                    <p class="text-xs text-gray-400">Admin Panel v1.0</p>
                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0">
            <!-- Top Header -->
            <header class="bg-white shadow-sm border-b border-gray-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">@yield('title', 'Admin Panel')</h1>
                        <p class="text-sm text-gray-600 mt-1">@yield('subtitle', 'Manage your school\'s content and settings')</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        @yield('header-actions')

                        <!-- Clear Cache Button -->
                        <form method="POST" action="{{ route('admin.cache.clear') }}" class="inline">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center px-3 py-1.5 text-sm text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors"
                                    onclick="return confirm('Yakin ingin membersihkan cache?')">
                                <span class="mr-1">🗑️</span>
                                Clear Cache
                            </button>
                        </form>

                        <!-- View Website Link -->
                        <a href="{{ route('home') }}" target="_blank"
                           class="inline-flex items-center px-3 py-1.5 text-sm text-blue-600 hover:text-blue-800 hover:bg-blue-50 rounded-lg transition-colors">
                            <span class="mr-1">🌐</span>
                            View Website
                        </a>

                        <!-- Current Time -->
                        <div class="text-sm text-gray-500 hidden md:block">
                            {{ now()->format('M d, Y - H:i') }}
                        </div>

                        <!-- User Profile Dropdown -->
                        <div class="relative" x-data="{ userMenu: false }" @click.away="userMenu = false">
                            <button @click="userMenu = !userMenu"
                                    class="flex items-center justify-center w-10 h-10 bg-blue-600 rounded-full hover:bg-blue-700 transition-colors shadow-md border-2 border-white">
                                <span class="text-white font-bold text-sm">{{ substr(auth()->user()->name, 0, 2) }}</span>
                            </button>
                            <div x-show="userMenu" x-cloak
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-150"
                                 x-transition:leave-start="opacity-100 scale-100"
                                 x-transition:leave-end="opacity-0 scale-95"
                                 class="absolute top-full right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">

                                <!-- User Info Header -->
                                <div class="px-4 py-3 border-b border-gray-200">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                                            <span class="text-white font-semibold">{{ substr(auth()->user()->name, 0, 2) }}</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                            <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                                            <p class="text-xs text-blue-600">Administrator</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Menu Items -->
                                <div class="py-1">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 text-left">
                                            <span class="mr-3">🚪</span>
                                            Sign Out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <!-- Main Content -->
            <main class="flex-1 overflow-auto p-6 bg-gray-50">
                @if (session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            {{ session('error') }}
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <p class="font-medium">Please fix the following errors:</p>
                                <ul class="mt-1 list-disc list-inside text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Global Delete Confirmation Modal -->
    <div id="globalDeleteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-24 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg font-bold text-gray-900">Konfirmasi Hapus</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Apakah Anda yakin ingin menghapus <span id="globalDeleteLabel">data ini</span>? Tindakan ini tidak dapat dibatalkan.
                    </p>
                </div>
                <div class="flex justify-center space-x-3 px-4 py-3">
                    <button id="globalDeleteCancel"
                            class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg">
                        Batal
                    </button>
                    <form id="globalDeleteForm" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const modal = document.getElementById('globalDeleteModal');
            const form = document.getElementById('globalDeleteForm');
            const label = document.getElementById('globalDeleteLabel');
            const cancelBtn = document.getElementById('globalDeleteCancel');

            function openModal(action, text) {
                form.action = action;
                label.textContent = text || 'data ini';
                modal.classList.remove('hidden');
            }

            function closeModal() {
                modal.classList.add('hidden');
            }

            cancelBtn.addEventListener('click', function(e) {
                e.preventDefault();
                closeModal();
            });

            // Intercept any delete form submission and show modal instead
            document.addEventListener('submit', function(e) {
                const target = e.target;
                if (!(target instanceof HTMLFormElement)) return;
                // Allow the modal's own form to submit normally
                if (target.id === 'globalDeleteForm') return;
                // Only consider this form's own elements (avoid nested forms)
                const isDeleteOverride = Array.from(target.elements || []).some(el => el.name === '_method' && (el.value || '').toUpperCase() === 'DELETE');
                const hasDeleteMethod = isDeleteOverride || (target.method && target.method.toUpperCase() === 'DELETE');
                if (!hasDeleteMethod) return;

                // Use data-confirm label if present
                const confirmText = target.getAttribute('data-confirm') || target.getAttribute('data-label') || 'data ini';

                // Prevent default submit and show modal
                e.preventDefault();
                openModal(target.action, confirmText);
            }, true);

            // Also support elements with data-delete-url attributes (e.g., buttons/links)
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('[data-delete-url]');
                if (!btn) return;
                e.preventDefault();
                const url = btn.getAttribute('data-delete-url');
                const text = btn.getAttribute('data-confirm') || btn.getAttribute('data-label') || btn.getAttribute('data-title') || 'data ini';
                openModal(url, text);
            });
        })();
    </script>
</body>
</html>
