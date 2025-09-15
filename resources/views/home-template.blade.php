@extends('layouts.app')

@section('title', App\Support\Theme::getSiteInfo()['name'])
@section('meta_description', App\Support\Theme::getSiteInfo()['description'])

@section('content')
@if($template && $template->sections)
    @foreach($template->sections as $section)
        @if($section->is_active && $section->blocks)
            @foreach($section->blocks as $block)
                @if($block->is_active)
                    <x-block-renderer :block="$block" />
                @endif
            @endforeach
        @endif
    @endforeach
@else
    {{-- Fallback content if no template sections --}}
    @php $siteInfo = App\Support\Theme::getSiteInfo(); @endphp

    <!-- Hero Section -->
    <section class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-white to-indigo-50">
        <div class="container mx-auto px-4 text-center">
            <div class="max-w-4xl mx-auto">
                @if($siteInfo['logo'])
                    <img src="{{ asset($siteInfo['logo']) }}" alt="Logo {{ $siteInfo['name'] }}" class="h-24 mx-auto mb-8 object-contain">
                @endif

                <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6" style="color: var(--color-primary);">
                    {{ $siteInfo['name'] }}
                </h1>

                @if($siteInfo['tagline'])
                    <p class="text-xl md:text-2xl text-gray-600 mb-8 font-light">
                        {{ $siteInfo['tagline'] }}
                    </p>
                @endif

                <p class="text-lg text-gray-700 mb-12 max-w-2xl mx-auto leading-relaxed">
                    {{ $siteInfo['description'] }}
                </p>

                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="/profil" class="btn-primary px-8 py-4 text-lg rounded-lg inline-flex items-center">
                        <span class="mr-2">📚</span>
                        Profil Sekolah
                    </a>
                    <a href="/berita" class="btn-secondary px-8 py-4 text-lg rounded-lg inline-flex items-center">
                        <span class="mr-2">📰</span>
                        Berita Terkini
                    </a>
                    <a href="/pendaftaran" class="btn-accent px-8 py-4 text-lg rounded-lg inline-flex items-center">
                        <span class="mr-2">🎓</span>
                        Pendaftaran
                    </a>
                </div>

                @auth
                    @if(auth()->user()->is_admin ?? false)
                        <div class="mt-12 p-6 bg-blue-50 rounded-lg border border-blue-200">
                            <h3 class="text-lg font-semibold text-blue-900 mb-4">🛠️ Panel Admin</h3>
                            <p class="text-blue-700 mb-4">Kelola website sekolah Anda dengan mudah</p>
                            <div class="flex flex-wrap gap-3 justify-center">
                                <a href="{{ route('admin.settings.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                                    ⚙️ Pengaturan
                                </a>
                                <a href="{{ route('admin.templates.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                                    🎨 Template
                                </a>
                                <a href="{{ route('admin.menus.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                                    📋 Menu
                                </a>
                                <a href="{{ route('admin.posts.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm">
                                    📝 Konten
                                </a>
                            </div>
                        </div>
                    @endif
                @endauth
            </div>
        </div>
    </section>

    <!-- Quick Info Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-3 gap-8 text-center">
                <div class="p-6">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">🎓</span>
                    </div>
                    <h3 class="text-xl font-semibold mb-3" style="color: var(--color-primary);">Pendidikan Berkualitas</h3>
                    <p class="text-gray-600">Kurikulum modern yang mempersiapkan siswa menghadapi era digital</p>
                </div>

                <div class="p-6">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">💻</span>
                    </div>
                    <h3 class="text-xl font-semibold mb-3" style="color: var(--color-primary);">Teknologi Terdepan</h3>
                    <p class="text-gray-600">Fasilitas laboratorium dan perangkat teknologi canggih</p>
                </div>

                <div class="p-6">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-2xl">🏆</span>
                    </div>
                    <h3 class="text-xl font-semibold mb-3" style="color: var(--color-primary);">Prestasi Gemilang</h3>
                    <p class="text-gray-600">Track record lulusan yang berhasil di dunia kerja dan pendidikan tinggi</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-16" style="background-color: var(--color-primary);">
        <div class="container mx-auto px-4 text-center">
            <div class="max-w-2xl mx-auto">
                <h2 class="text-3xl font-bold text-white mb-6">Bergabunglah dengan Kami</h2>
                <p class="text-lg text-blue-100 mb-8">Wujudkan impian karir di bidang teknologi bersama kami</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/pendaftaran" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                        Daftar Sekarang
                    </a>
                    <a href="/kontak" class="border border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-colors">
                        Hubungi Kami
                    </a>
                </div>
            </div>
        </div>
    </section>
@endif
@endsection
