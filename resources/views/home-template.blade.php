@extends('layouts.app')

@section('title', App\Support\Theme::getSiteInfo()['name'])
@section('meta_description', App\Support\Theme::getSiteInfo()['description'])

@section('content')
@include('partials.preview-banner')
@if($template && $template->sections)
    @foreach($template->sections as $section)
        @if(($section->active ?? false) && $section->blocks)
            @foreach($section->blocks as $block)
                @if(($block->active ?? false))
                    <x-block-renderer :block="$block" />
                @endif
            @endforeach
        @endif
    @endforeach
@else
    {{-- Fallback content if no template sections --}}
    @php
        $siteInfo = App\Support\Theme::getSiteInfo();
        $headerMenu = App\Support\Theme::getMenu('header');
        $ctaItems = ($headerMenu && $headerMenu->count() > 0) ? $headerMenu->take(3) : collect();
        // Find a 'Kontak' link for CTA section
        $contactItem = ($headerMenu && $headerMenu->count() > 0)
            ? $headerMenu->first(function($item){ return stripos($item->title ?? '', 'kontak') !== false; })
            : null;
    @endphp

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

                @if($ctaItems->count() > 0)
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                        @foreach($ctaItems as $i => $item)
                            @php
                                $styleClass = match($i){
                                    0 => 'btn-primary',
                                    1 => 'btn-secondary',
                                    default => 'btn-accent'
                                };
                            @endphp
                            <a href="{{ $item->url ?? '#' }}" target="{{ $item->target ?? '_self' }}" class="{{ $styleClass }} px-8 py-4 text-lg rounded-lg inline-flex items-center">
                                <span class="mr-2">{{ $i === 0 ? '�' : ($i === 1 ? '📰' : '�📩') }}</span>
                                {{ $item->title ?? 'Menu' }}
                            </a>
                        @endforeach
                    </div>
                @endif


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
                @if($contactItem)
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ $contactItem->url ?? '#' }}" target="{{ $contactItem->target ?? '_self' }}" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                            Hubungi Kami
                        </a>
                        <a href="{{ $contactItem->url ?? '#' }}" target="{{ $contactItem->target ?? '_self' }}" class="border border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-colors">
                            Hubungi Kami
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endif
@endsection
