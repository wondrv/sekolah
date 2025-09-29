@extends('layouts.admin')

@section('title', 'Template Gallery')

@section('header')
<h1 class="text-2xl font-bold text-gray-900">Template Gallery</h1>
<p class="text-gray-600">Pilih template yang sesuai untuk sekolah Anda</p>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Bulk Actions -->
    <div class="flex flex-wrap items-center gap-3">
        <form method="POST" action="{{ route('admin.templates.gallery.bulk-install') }}" onsubmit="return confirm('Install semua template gallery yang belum terpasang?')">
            @csrf
            <input type="hidden" name="activate_first" value="1" />
            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Install Semua Starter
            </button>
        </form>
        <p class="text-xs text-gray-500">Tombol ini akan meng-clone semua template aktif di gallery ke koleksi Anda.</p>
    </div>
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Template</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Featured</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['featured'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Kategori</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['categories'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Terinstall</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['installed'] }}</p>
                </div>
            </div>
        </div>

        @if(isset($stats['external_discovered']) && $stats['external_discovered'] > 0)
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"/>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">External Discovered</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stats['external_discovered'] }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Categories -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Kategori Template</h3>
        </div>
        <div class="p-6">
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.templates.gallery.index') }}"
                   class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium {{ !request('category') ? 'bg-blue-100 text-blue-800 border border-blue-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition-colors">
                    Semua Template
                </a>
                @foreach($categories as $category)
                    <a href="{{ route('admin.templates.gallery.index', ['category' => $category->slug]) }}"
                       class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium {{ request('category') === $category->slug ? 'bg-blue-100 text-blue-800 border border-blue-200' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }} transition-colors">
                        <div class="w-3 h-3 rounded-full mr-2" style="background-color: {{ $category->color }}"></div>
                        {{ $category->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Filters & Search -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6">
            <form method="GET" class="flex flex-wrap gap-4 items-end">
                <!-- Search -->
                <div class="flex-1 min-w-64">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Template</label>
                    <input type="text"
                           name="search"
                           id="search"
                           value="{{ request('search') }}"
                           placeholder="Nama template atau fitur..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Type Filter -->
                <div class="min-w-32">
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Tipe</label>
                    <select name="type" id="type" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua</option>
                        <option value="featured" {{ request('type') === 'featured' ? 'selected' : '' }}>Featured</option>
                        <option value="free" {{ request('type') === 'free' ? 'selected' : '' }}>Gratis</option>
                        <option value="premium" {{ request('type') === 'premium' ? 'selected' : '' }}>Premium</option>
                    </select>
                </div>

                <!-- Sort -->
                <div class="min-w-32">
                    <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Urutkan</label>
                    <select name="sort" id="sort" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="featured" {{ request('sort') === 'featured' ? 'selected' : '' }}>Featured</option>
                        <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Nama A-Z</option>
                        <option value="downloads" {{ request('sort') === 'downloads' ? 'selected' : '' }}>Paling Populer</option>
                        <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Terbaru</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'type', 'sort', 'category']))
                        <a href="{{ route('admin.templates.gallery.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Templates Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @forelse($templates as $template)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md hover:border-gray-200 transition-all duration-200 group">
                <!-- Preview Image -->
                <div class="aspect-[4/3] bg-gray-50 relative overflow-hidden">
                    @if($template->preview_image)
                        <img src="{{ $template->preview_image_url }}"
                             alt="{{ $template->name }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        @php
                            // Create unique but consistent placeholder based on template ID
                            $placeholderImages = [
                                'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=600&h=400&fit=crop&crop=entropy&auto=format&q=80',
                                'https://images.unsplash.com/photo-1541829070764-84a7d30dd3f3?w=600&h=400&fit=crop&crop=entropy&auto=format&q=80',
                                'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=600&h=400&fit=crop&crop=entropy&auto=format&q=80',
                                'https://images.unsplash.com/photo-1571260899304-425eee4c7efc?w=600&h=400&fit=crop&crop=entropy&auto=format&q=80',
                                'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=600&h=400&fit=crop&crop=entropy&auto=format&q=80',
                                'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=600&h=400&fit=crop&crop=entropy&auto=format&q=80',
                                'https://images.unsplash.com/photo-1497486751825-1233686d5d80?w=600&h=400&fit=crop&crop=entropy&auto=format&q=80',
                                'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=600&h=400&fit=crop&crop=entropy&auto=format&q=80',
                                'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600&h=400&fit=crop&crop=entropy&auto=format&q=80',
                                'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&h=400&fit=crop&crop=entropy&auto=format&q=80'
                            ];
                            $imageIndex = $template->id % count($placeholderImages);
                            $placeholderUrl = $placeholderImages[$imageIndex];
                        @endphp
                        <img src="{{ $placeholderUrl }}"
                             alt="{{ $template->name }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        <!-- Overlay to indicate it's a placeholder -->
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                    @endif

                    <!-- Overlay badges -->
                    <div class="absolute top-2 left-2 flex flex-col gap-1">
                        @if($template->featured)
                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-yellow-500 text-white shadow-sm">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                                Featured
                            </span>
                        @endif
                        @if($template->premium)
                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-purple-500 text-white shadow-sm">
                                Premium
                            </span>
                        @endif
                        @if($template->isInstalled())
                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-green-500 text-white shadow-sm">
                                ✓ Installed
                            </span>
                        @endif
                    </div>

                    <!-- Category badge -->
                    <div class="absolute top-2 right-2">
                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium text-white shadow-sm"
                              style="background-color: {{ $template->category->color ?? '#6366f1' }}">
                            {{ $template->category->name ?? 'General' }}
                        </span>
                    </div>
                </div>

                <!-- Content -->
                <div class="p-4">
                    <div class="mb-3">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="text-base font-semibold text-gray-900 line-clamp-1 flex-1 pr-2">{{ $template->name }}</h3>
                            <div class="flex items-center text-sm text-gray-500 flex-shrink-0">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-3 h-3 {{ $i <= $template->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                @endfor
                                <span class="ml-1 text-xs font-medium">{{ number_format($template->rating, 1) }}</span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500">oleh {{ $template->author }}</p>
                    </div>

                    <p class="text-sm text-gray-600 line-clamp-2 mb-3">{{ $template->description }}</p>

                    <!-- Features -->
                    @if($template->features && count($template->features) > 0)
                        <div class="flex flex-wrap gap-1 mb-3">
                            @foreach(array_slice($template->features, 0, 4) as $feature)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                    {{ $feature }}
                                </span>
                            @endforeach
                            @if(count($template->features) > 4)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-50 text-gray-600 border border-gray-200">
                                    +{{ count($template->features) - 4 }} lainnya
                                </span>
                            @endif
                        </div>
                    @endif

                    <!-- Stats -->
                    <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                        <span class="flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                            </svg>
                            {{ number_format($template->downloads) }} download
                        </span>
                        <span class="font-medium">v{{ $template->version }}</span>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2">
                        <a href="{{ route('admin.templates.gallery.show', $template) }}"
                           class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-all duration-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Lihat Detail
                        </a>
                        <button onclick="previewTemplate({{ $template->id }})"
                                class="px-3 py-2 bg-gray-100 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-1 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                        </button>
                        <a href="{{ route('admin.templates.gallery.live-preview', $template) }}"
                           class="px-3 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1 transition-all duration-200">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-.376a1 1 0 01.894 1.447l-3.342 6.685A2 2 0 0115.276 19H8.724a2 2 0 01-1.829-1.244l-3.342-6.685A1 1 0 014.447 9.624L9 10"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10V5a3 3 0 016 0v5"/></svg>
                            Live
                        </a>
                        @if(!$template->isInstalled())
                        <form method="POST" action="{{ route('admin.templates.gallery.install', $template) }}" class="inline">
                            @csrf
                            <input type="hidden" name="activate" value="1" />
                            <button type="submit"
                                    class="px-3 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-1 transition-all duration-200"
                                    title="Install & Aktifkan">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                                Install & Aktifkan
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </path>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada template</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        @if(request()->hasAny(['search', 'type', 'sort', 'category']))
                            Tidak ada template yang sesuai dengan filter Anda.
                        @else
                            Belum ada template tersedia.
                        @endif
                    </p>
                    @if(request()->hasAny(['search', 'type', 'sort', 'category']))
                        <div class="mt-6">
                            <a href="{{ route('admin.templates.gallery.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Lihat Semua Template
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($templates->hasPages())
        <div class="bg-white px-4 py-3 rounded-lg shadow">
            {{ $templates->links() }}
        </div>
    @endif

    <!-- External Templates Section -->
    @if(!empty($externalTemplates) && count($externalTemplates) > 0)
        <div class="border-t-4 border-indigo-500 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg shadow-sm">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"/>
                            </svg>
                            External Templates Discovery
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">Real-time templates from GitHub and other sources • Auto-discovered</p>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-indigo-600 font-medium">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        Live Discovery
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                    @foreach($externalTemplates as $template)
                        <div class="bg-white rounded-xl shadow-sm border-2 border-indigo-100 overflow-hidden hover:shadow-md hover:border-indigo-200 transition-all duration-200 group">
                            <!-- Preview Image -->
                            <div class="aspect-[4/3] bg-gradient-to-br from-indigo-50 to-purple-50 relative overflow-hidden">
                                <img src="{{ $template['preview_image'] ?? 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=600&h=400' }}"
                                     alt="{{ $template['name'] }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">

                                <!-- External badge -->
                                <div class="absolute top-2 left-2">
                                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-indigo-600 text-white shadow-sm">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.083 9h1.946c.089-1.546.383-2.97.837-4.118A6.004 6.004 0 004.083 9zM10 2a8 8 0 100 16 8 8 0 000-16zm0 2c-.076 0-.232.032-.465.262-.238.234-.497.623-.737 1.182-.389.907-.673 2.142-.766 3.556h3.936c-.093-1.414-.377-2.649-.766-3.556-.24-.559-.499-.948-.737-1.182C10.232 4.032 10.076 4 10 4zm3.971 5c-.089-1.546-.383-2.97-.837-4.118A6.004 6.004 0 0115.917 9h-1.946zm-2.003 2H8.032c.093 1.414.377 2.649.766 3.556.24.559.499.948.737 1.182.233.23.389.262.465.262.076 0 .232-.032.465-.262.238-.234.497-.623.737-1.182.389-.907.673-2.142.766-3.556zm1.166 4.118c.454-1.148.748-2.572.837-4.118h1.946a6.004 6.004 0 01-2.783 4.118zm-6.268 0C6.412 13.97 6.118 12.546 6.032 11H4.083a6.004 6.004 0 002.851 4.118z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ ucfirst($template['source_type'] ?? 'external') }}
                                    </span>
                                </div>

                                <!-- Source info -->
                                <div class="absolute top-2 right-2">
                                    @if(($template['source_type'] ?? '') === 'github')
                                        <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-900 text-white shadow-sm">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 0C4.477 0 0 4.484 0 10.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0110 4.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.203 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.942.359.31.678.921.678 1.856 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0020 10.017C20 4.484 15.522 0 10 0z" clip-rule="evenodd"/>
                                            </svg>
                                            GitHub
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-4">
                                <div class="mb-3">
                                    <div class="flex items-start justify-between mb-2">
                                        <h3 class="text-base font-semibold text-gray-900 line-clamp-1 flex-1 pr-2">{{ $template['name'] }}</h3>
                                        <div class="flex items-center text-sm text-gray-500 flex-shrink-0">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="w-3 h-3 {{ $i <= ($template['rating'] ?? 4) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                                </svg>
                                            @endfor
                                            <span class="ml-1 text-xs font-medium">{{ number_format($template['rating'] ?? 4, 1) }}</span>
                                        </div>
                                    </div>
                                    <p class="text-xs text-gray-500">by {{ $template['author'] ?? 'External Author' }}</p>
                                </div>

                                <p class="text-sm text-gray-600 line-clamp-2 mb-3">{{ $template['description'] }}</p>

                                <!-- Features -->
                                @if(isset($template['features']) && count($template['features']) > 0)
                                    <div class="flex flex-wrap gap-1 mb-3">
                                        @foreach(array_slice($template['features'], 0, 3) as $feature)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                {{ $feature }}
                                            </span>
                                        @endforeach
                                        @if(count($template['features']) > 3)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-50 text-gray-600 border border-gray-200">
                                                +{{ count($template['features']) - 3 }}
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                <!-- External Stats -->
                                <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                                    @if(isset($template['stars']))
                                        <span class="flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                            </svg>
                                            {{ $template['stars'] }} stars
                                        </span>
                                    @endif
                                    @if(isset($template['language']))
                                        <span class="font-medium">{{ $template['language'] }}</span>
                                    @endif
                                </div>

                                <!-- Actions -->
                                <div class="flex gap-2">
                                    <button onclick="previewExternalTemplate({{ json_encode($template) }})"
                                            class="flex-1 inline-flex items-center justify-center px-3 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1 transition-all duration-200">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        Preview
                                    </button>
                                    @if(isset($template['source_url']))
                                        <a href="{{ $template['source_url'] }}" target="_blank"
                                           class="px-3 py-2 bg-gray-100 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-1 transition-all duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                            </svg>
                                        </a>
                                    @endif
                                    <button onclick="installExternalTemplate({{ json_encode($template) }})"
                                            class="px-3 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-1 transition-all duration-200"
                                            title="Install External Template">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 p-4 bg-indigo-50 rounded-lg border border-indigo-200">
                    <p class="text-xs text-indigo-700">
                        <strong>Note:</strong> External templates are discovered in real-time from open-source repositories.
                        They will be converted to match your CMS structure during installation.
                    </p>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Preview Modal -->
<div id="previewModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900" id="modal-title">Preview Template</h3>
                    <button onclick="closePreviewModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div id="previewContent" class="p-6">
                    <!-- Preview content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewTemplate(templateId) {
    const modal = document.getElementById('previewModal');
    const content = document.getElementById('previewContent');

    modal.classList.remove('hidden');
    content.innerHTML = '<div class="text-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto"></div><p class="mt-2 text-gray-600">Loading preview...</p></div>';

    fetch(`/admin/templates/gallery/${templateId}/preview`)
        .then(response => response.json())
        .then(data => {
            let previewHtml = `
                <div class="space-y-6">
                    <div class="text-center">
                        <h4 class="text-xl font-semibold text-gray-900">${data.template.name}</h4>
                        <p class="text-gray-600">${data.template.description}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            `;

            data.preview_images.forEach(image => {
                previewHtml += `
                    <div class="aspect-video bg-gray-100 rounded-lg overflow-hidden">
                        <img src="${image}" alt="Preview" class="w-full h-full object-cover">
                    </div>
                `;
            });

            previewHtml += `
                    </div>

                    <div class="text-center">
                        <a href="/admin/templates/gallery/${data.template.id}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                            Lihat Detail Lengkap
                        </a>
                    </div>
                </div>
            `;

            content.innerHTML = previewHtml;
        })
        .catch(error => {
            content.innerHTML = '<div class="text-center py-8 text-red-600">Gagal memuat preview</div>';
        });
}

function closePreviewModal() {
    document.getElementById('previewModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('previewModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closePreviewModal();
    }
});

// External template functions
function previewExternalTemplate(template) {
    const params = new URLSearchParams({
        name: template.name,
        description: template.description,
        source_type: template.source_type || 'external',
        preview_image: template.preview_image || '',
        features: JSON.stringify(template.features || [])
    });

    window.open(`{{ route('admin.templates.gallery.external.preview') }}?${params}`, '_blank');
}

function installExternalTemplate(template) {
    if (!confirm(`Install external template "${template.name}"?\n\nThis will convert the template to match your CMS structure.`)) {
        return;
    }

    // Create a form to submit the external template data
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("admin.templates.gallery.external.install") }}';
    form.style.display = 'none';

    // Add CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken.getAttribute('content');
        form.appendChild(csrfInput);
    }

    // Add template data
    const fields = {
        external_id: template.external_id || 'ext_' + Date.now(),
        source_type: template.source_type || 'external',
        name: template.name,
        description: template.description,
        source_url: template.source_url || '',
        preview_image: template.preview_image || '',
        author: template.author || 'External',
        rating: template.rating || 4.0,
        activate: '1' // Auto-activate external templates
    };

    // Add features array
    if (template.features && Array.isArray(template.features)) {
        template.features.forEach((feature, index) => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `features[${index}]`;
            input.value = feature;
            form.appendChild(input);
        });
    }

    // Add other fields
    Object.entries(fields).forEach(([key, value]) => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = value;
        form.appendChild(input);
    });

    document.body.appendChild(form);
    form.submit();
}
</script>
@endpush



