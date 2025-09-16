@extends('layouts.admin')

@section('title', 'Dashboard')
@section('subtitle', 'Selamat datang di panel admin sekolah')

@section('content')
<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Posts Count -->
    <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total Berita</p>
                <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Post::count() }}</p>
                <p class="text-xs text-gray-500">+{{ \App\Models\Post::where('created_at', '>=', now()->subDays(30))->count() }} bulan ini</p>
            </div>
        </div>
    </div>

    <!-- Events Count -->
    <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Event & Kegiatan</p>
                <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Event::count() }}</p>
                <p class="text-xs text-gray-500">{{ \App\Models\Event::where('event_date', '>=', now())->count() }} akan datang</p>
            </div>
        </div>
    </div>

    <!-- Messages Count -->
    <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Pesan Masuk</p>
                <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Message::count() }}</p>
                <p class="text-xs text-red-600">{{ \App\Models\Message::where('status', 'unread')->count() }} belum dibaca</p>
            </div>
        </div>
    </div>

    {{-- Enrollment feature removed --}}
</div>

<!-- Quick Management Cards -->
<div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
    <!-- Content Management -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h3 class="ml-3 text-lg font-semibold text-gray-900">Kelola Konten</h3>
        </div>
        <p class="text-gray-600 text-sm mb-4">Kelola semua konten website sekolah</p>
        <div class="space-y-2">
            <a href="{{ route('admin.posts.index') }}" class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                <div class="flex items-center">
                    <span class="text-sm mr-2">📰</span>
                    <span class="text-sm font-medium text-gray-700">Berita & Artikel</span>
                </div>
                <span class="text-xs text-gray-500">{{ \App\Models\Post::count() }}</span>
            </a>
            <a href="{{ route('admin.pages.index') }}" class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                <div class="flex items-center">
                    <span class="text-sm mr-2">📄</span>
                    <span class="text-sm font-medium text-gray-700">Halaman</span>
                </div>
                <span class="text-xs text-gray-500">{{ \App\Models\Page::count() }}</span>
            </a>
            <a href="{{ route('admin.pages.index') }}" class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                <div class="flex items-center">
                    <span class="text-sm mr-2">📅</span>
                    <span class="text-sm font-medium text-gray-700">Event & Kegiatan</span>
                </div>
                <span class="text-xs text-gray-500">{{ \App\Models\Event::count() }}</span>
            </a>
            <a href="{{ route('admin.galleries.index') }}" class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                <div class="flex items-center">
                    <span class="text-sm mr-2">🖼️</span>
                    <span class="text-sm font-medium text-gray-700">Galeri</span>
                </div>
                <span class="text-xs text-gray-500">{{ \App\Models\Gallery::count() }}</span>
            </a>
        </div>
    </div>

    <!-- School Features -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <h3 class="ml-3 text-lg font-semibold text-gray-900">Fitur Sekolah</h3>
        </div>
        <p class="text-gray-600 text-sm mb-4">Kelola informasi dan fitur sekolah</p>
        <div class="space-y-2">
            <a href="{{ route('admin.facilities.index') }}" class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                <div class="flex items-center">
                    <span class="text-sm mr-2">🏫</span>
                    <span class="text-sm font-medium text-gray-700">Fasilitas</span>
                </div>
                <span class="text-xs text-gray-500">{{ \App\Models\Facility::count() }}</span>
            </a>
            <a href="{{ route('admin.programs.index') }}" class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                <div class="flex items-center">
                    <span class="text-sm mr-2">🎯</span>
                    <span class="text-sm font-medium text-gray-700">Program Unggulan</span>
                </div>
                <span class="text-xs text-gray-500">{{ \App\Models\Program::count() }}</span>
            </a>
            <a href="{{ route('admin.achievements.index') }}" class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                <div class="flex items-center">
                    <span class="text-sm mr-2">🏆</span>
                    <span class="text-sm font-medium text-gray-700">Prestasi</span>
                </div>
                <span class="text-xs text-gray-500">{{ \App\Models\Achievement::count() }}</span>
            </a>
            <a href="{{ route('admin.testimonials.index') }}" class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                <div class="flex items-center">
                    <span class="text-sm mr-2">💬</span>
                    <span class="text-sm font-medium text-gray-700">Testimoni</span>
                </div>
                <span class="text-xs text-gray-500">{{ \App\Models\Testimonial::count() }}</span>
            </a>
        </div>
    </div>

    <!-- Advanced Features -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <h3 class="ml-3 text-lg font-semibold text-gray-900">Pengaturan Lanjutan</h3>
        </div>
        <p class="text-gray-600 text-sm mb-4">Kelola template dan pengaturan sistem</p>
        <div class="space-y-2">
            <a href="{{ route('admin.templates.index') }}" class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                <div class="flex items-center">
                    <span class="text-sm mr-2">🎨</span>
                    <span class="text-sm font-medium text-gray-700">Template Builder</span>
                </div>
                <span class="text-xs text-gray-500">{{ \App\Models\Template::count() }}</span>
            </a>
            <a href="{{ route('admin.menus.index') }}" class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                <div class="flex items-center">
                    <span class="text-sm mr-2">🧭</span>
                    <span class="text-sm font-medium text-gray-700">Menu Management</span>
                </div>
                <span class="text-xs text-gray-500">{{ \App\Models\Menu::count() }}</span>
            </a>
            <a href="{{ route('admin.settings.index') }}" class="flex items-center justify-between p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors">
                <div class="flex items-center">
                    <span class="text-sm mr-2">⚙️</span>
                    <span class="text-sm font-medium text-gray-700">Pengaturan Sistem</span>
                </div>
                <span class="text-xs text-gray-500">{{ \App\Models\Setting::count() }}</span>
            </a>
        </div>
    </div>
</div>

<!-- Communication Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Recent Messages -->
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Pesan Terbaru</h3>
            <a href="{{ route('admin.messages.index') }}" class="text-blue-600 hover:text-blue-800 text-sm">Lihat Semua</a>
        </div>
        <div class="space-y-3">
            @php
                $recentMessages = \App\Models\Message::latest()->take(5)->get();
            @endphp
            @forelse($recentMessages as $message)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-900">{{ $message->name }}</p>
                    <p class="text-xs text-gray-600 truncate">{{ Str::limit($message->subject, 40) }}</p>
                    <p class="text-xs text-gray-500">{{ $message->created_at->diffForHumans() }}</p>
                </div>
                <div class="flex items-center space-x-2">
                    @if($message->status === 'unread')
                        <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                    @endif
                    <span class="px-2 py-1 text-xs rounded-full {{ $message->status === 'unread' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ ucfirst($message->status) }}
                    </span>
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <p class="text-sm">Belum ada pesan masuk</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Recent enrollments removed --}}

    <!-- Recent Posts Section -->
    <div class="bg-white rounded-lg shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Berita Terbaru</h3>
                <a href="{{ route('admin.posts.index') }}" class="text-sm text-blue-600 hover:underline">
                    Lihat Semua
                </a>
            </div>
        </div>
        <div class="p-6">
            @php
                $recentPosts = \App\Models\Post::latest()->limit(5)->get();
            @endphp

            @if($recentPosts->count() > 0)
                <div class="space-y-4">
                    @foreach($recentPosts as $post)
                    <div class="flex items-start space-x-3">
                        <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-gray-900">{{ $post->title }}</h4>
                            <p class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Belum ada berita</p>
            @endif
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="mt-8">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Settings Link -->
            <a href="{{ route('admin.settings.index') }}"
               class="flex items-center justify-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg text-blue-600 hover:text-blue-700 transition-colors">
                <div class="text-center">
                    <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="text-xs font-medium">Pengaturan</span>
                </div>
            </a>

            <!-- View Site Link -->
            <a href="{{ route('home') }}" target="_blank"
               class="flex items-center justify-center p-4 bg-green-50 hover:bg-green-100 rounded-lg text-green-600 hover:text-green-700 transition-colors">
                <div class="text-center">
                    <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                    </svg>
                    <span class="text-xs font-medium">Lihat Website</span>
                </div>
            </a>

            <!-- Posts Management -->
            <a href="{{ route('admin.posts.index') }}"
               class="flex items-center justify-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg text-purple-600 hover:text-purple-700 transition-colors">
                <div class="text-center">
                    <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="text-xs font-medium">Kelola Konten</span>
                </div>
            </a>

            <!-- Template Builder -->
            <a href="{{ route('admin.templates.index') }}"
               class="flex items-center justify-center p-4 bg-orange-50 hover:bg-orange-100 rounded-lg text-orange-600 hover:text-orange-700 transition-colors">
                <div class="text-center">
                    <svg class="w-8 h-8 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                    </svg>
                    <span class="text-xs font-medium">Template Builder</span>
                </div>
            </a>
        </div>

        <div class="mt-4 p-4 bg-blue-50 rounded-lg">
            <h4 class="text-sm font-medium text-blue-900 mb-2">🎉 CMS Ready!</h4>
            <p class="text-sm text-blue-700">Your Content Management System is set up and ready. Use the Settings page to customize your site's appearance and content.</p>
        </div>
    </div>
</div>
@endsection
