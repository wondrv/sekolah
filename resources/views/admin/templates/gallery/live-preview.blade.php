@extends('layouts.admin')

@section('title', 'Live Preview: ' . $galleryTemplate->name)

@section('content')
@php
    // Determine previous/next template ids for navigation
    $all = \App\Models\TemplateGallery::active()->orderBy('id')->pluck('id')->toArray();
    $idx = array_search($galleryTemplate->id, $all, true);
    $prevId = $all[$idx-1] ?? null;
    $nextId = $all[$idx+1] ?? null;
@endphp
<div class="mb-4 p-4 rounded-md bg-indigo-50 border border-indigo-200">
    <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
        <div>
            <h2 class="font-semibold text-indigo-800 flex items-center gap-2">Gallery Live Preview <span class="text-xs px-2 py-0.5 rounded bg-indigo-600 text-white">Uninstalled</span>@if($useSample)<span class="text-[10px] px-2 py-0.5 rounded bg-green-600 text-white">Sample Data</span>@endif @if($compare)<span class="text-[10px] px-2 py-0.5 rounded bg-purple-600 text-white">Compare Mode</span>@endif</h2>
            <p class="text-xs text-indigo-600 mt-1">Preview template <strong>{{ $galleryTemplate->name }}</strong> langsung dari data gallery. Tidak mempengaruhi situs Anda.</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            @if($prevId)
                <a href="{{ route('admin.templates.gallery.live-preview', $prevId) }}" class="text-xs px-3 py-1.5 rounded bg-white border border-indigo-300 text-indigo-700 hover:bg-indigo-100">◀ Prev</a>
            @endif
            @if($nextId)
                <a href="{{ route('admin.templates.gallery.live-preview', $nextId) }}" class="text-xs px-3 py-1.5 rounded bg-white border border-indigo-300 text-indigo-700 hover:bg-indigo-100">Next ▶</a>
            @endif
            <div x-data="{vp:'desktop'}" class="flex items-center gap-1 ml-2">
                <span class="text-[10px] uppercase text-indigo-700 font-semibold mr-1">Viewport:</span>
                <button @click="vp='mobile'" :class="vp==='mobile'?'bg-indigo-600 text-white':'bg-white text-indigo-600'" class="text-[10px] px-2 py-1 rounded border border-indigo-300">Mobile</button>
                <button @click="vp='tablet'" :class="vp==='tablet'?'bg-indigo-600 text-white':'bg-white text-indigo-600'" class="text-[10px] px-2 py-1 rounded border border-indigo-300">Tablet</button>
                <button @click="vp='desktop'" :class="vp==='desktop'?'bg-indigo-600 text-white':'bg-white text-indigo-600'" class="text-[10px] px-2 py-1 rounded border border-indigo-300">Desktop</button>
                <div class="w-full mt-3" x-cloak>
                    <div :class="{'max-w-xs':vp==='mobile','max-w-2xl':vp==='tablet','max-w-none':vp==='desktop'}" class="transition-all duration-300 mx-auto"></div>
                </div>
            </div>
            <a href="{{ route('admin.templates.gallery.live-preview', array_filter([$galleryTemplate->id, 'sample' => $useSample ? 0 : 1, 'compare' => $compare ? 1 : null])) }}" class="text-xs px-3 py-1.5 rounded bg-yellow-500 hover:bg-yellow-600 text-white">{{ $useSample ? 'Matikan Sample' : 'Pakai Sample' }}</a>
            <a href="{{ route('admin.templates.gallery.live-preview', array_filter([$galleryTemplate->id, 'sample' => $useSample ? 1 : null, 'compare' => $compare ? 0 : 1])) }}" class="text-xs px-3 py-1.5 rounded bg-purple-600 hover:bg-purple-700 text-white">{{ $compare ? 'Tutup Compare' : 'Compare Active' }}</a>
            <form method="POST" action="{{ route('admin.templates.gallery.install', $galleryTemplate) }}" onsubmit="return confirm('Install dan buka mode edit?');">
                @csrf
                <input type="hidden" name="activate" value="0" />
                <button formaction="{{ route('admin.templates.gallery.install', $galleryTemplate) }}" name="duplicate_edit" value="1" class="text-xs px-3 py-1.5 rounded bg-blue-600 hover:bg-blue-700 text-white">Duplikat & Edit</button>
            </form>
            <a href="{{ route('admin.templates.gallery.show', $galleryTemplate) }}" class="text-xs px-3 py-1.5 rounded bg-gray-200 hover:bg-gray-300 text-gray-700">Detail</a>
            <form method="POST" action="{{ route('admin.templates.gallery.install', $galleryTemplate) }}">
                @csrf
                <input type="hidden" name="activate" value="1" />
                <button class="text-xs px-3 py-1.5 rounded bg-green-600 hover:bg-green-700 text-white">Install & Aktifkan</button>
            </form>
        </div>
    </div>
</div>

@if(!empty($themeCss))
    <style>
        /* Theme variable simulation for preview */
        :root {
        }
        {!! $themeCss !!}
    </style>
@endif

@if(!$structure)
    <div class="p-6 bg-white rounded shadow text-center text-gray-500">
        Struktur template tidak memiliki data sections.
    </div>
@else
    <div x-data="{vp:'desktop'}" class="space-y-10">
        @if($compare)
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-semibold text-indigo-700 mb-2 flex items-center gap-2">Template Gallery <span class="text-[10px] px-2 py-0.5 rounded bg-indigo-600 text-white">{{ $galleryTemplate->name }}</span><span class="text-[10px] px-2 py-0.5 rounded bg-amber-500 text-white">Simulated Menus/Widgets</span></h3>
                    <nav class="mb-4 bg-white border rounded p-3 text-xs flex flex-wrap gap-3">
                        @foreach(($simulatedMenus['primary'] ?? []) as $m)
                            <span class="text-gray-700 hover:text-indigo-600 cursor-default">{{ $m['title'] }}</span>
                        @endforeach
                    </nav>
                    @foreach($sections as $section)
                        <section class="rounded border bg-white shadow-sm p-4 md:p-6 mb-6 transition-all duration-300" :class="{'max-w-xs mx-auto':vp==='mobile','max-w-2xl mx-auto':vp==='tablet','mx-auto':vp==='desktop'}">
                            <h4 class="text-[11px] font-semibold tracking-wide text-gray-500 mb-4 uppercase">Section: {{ $section['name'] }}</h4>
                            <div class="space-y-6">
                                @include('admin.templates.gallery.partials.preview-blocks', ['sectionBlocks' => $section['blocks'], 'useSample' => $useSample])
                            </div>
                        </section>
                    @endforeach
                    <footer class="mt-8 bg-white border rounded p-4">
                        <div class="grid md:grid-cols-2 gap-6">
                            @foreach(($simulatedWidgets['footer'] ?? []) as $w)
                                <div>
                                    <h5 class="text-sm font-semibold mb-2">{{ $w['title'] }}</h5>
                                    <p class="text-xs text-gray-600 whitespace-pre-line">{{ $w['content'] }}</p>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 text-[10px] text-gray-400">Simulated footer widgets</div>
                    </footer>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-purple-700 mb-2 flex items-center gap-2">Active Template <span class="text-[10px] px-2 py-0.5 rounded bg-purple-600 text-white">{{ $activeTemplate?->name ?? 'Tidak Ada' }}</span>@if(($activeMenus['primary'] ?? null))<span class="text-[10px] px-2 py-0.5 rounded bg-green-600 text-white">Real Menu</span>@endif</h3>
                    @if(($activeMenus['primary'] ?? null))
                        <nav class="mb-4 bg-white border rounded p-3 text-xs flex flex-wrap gap-3">
                            @foreach($activeMenus['primary'] as $m)
                                <span class="text-gray-700 hover:text-indigo-600 cursor-default">{{ $m['title'] }}</span>
                            @endforeach
                        </nav>
                    @endif
                    @forelse($activeSections as $section)
                        <section class="rounded border bg-white shadow-sm p-4 md:p-6 mb-6 transition-all duration-300" :class="{'max-w-xs mx-auto':vp==='mobile','max-w-2xl mx-auto':vp==='tablet','mx-auto':vp==='desktop'}">
                            <h4 class="text-[11px] font-semibold tracking-wide text-gray-500 mb-4 uppercase">Section: {{ $section['name'] }}</h4>
                            <div class="space-y-6">
                                @include('admin.templates.gallery.partials.preview-blocks', ['sectionBlocks' => $section['blocks'], 'useSample' => false])
                            </div>
                        </section>
                    @empty
                        <div class="p-4 text-xs text-gray-500 bg-white rounded border">Active template belum memiliki section.</div>
                    @endforelse
                    @if(($activeWidgets['footer'] ?? null))
                        <footer class="mt-8 bg-white border rounded p-4">
                            <div class="grid md:grid-cols-2 gap-6">
                                @foreach($activeWidgets['footer'] as $w)
                                    <div>
                                        <h5 class="text-sm font-semibold mb-2">{{ $w['title'] }}</h5>
                                        <p class="text-xs text-gray-600 whitespace-pre-line">{{ $w['content'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4 text-[10px] text-gray-400">Real footer widgets</div>
                        </footer>
                    @endif
                </div>
            </div>
        @else
            @foreach($sections as $section)
                <section class="rounded border bg-white shadow-sm p-4 md:p-6 transition-all duration-300" :class="{'max-w-xs mx-auto':vp==='mobile','max-w-2xl mx-auto':vp==='tablet','mx-auto':vp==='desktop'}">
                    <h3 class="text-[11px] font-semibold tracking-wide text-gray-500 mb-4 uppercase">Section: {{ $section['name'] }}</h3>
                    <div class="space-y-6">
                        @include('admin.templates.gallery.partials.preview-blocks', ['sectionBlocks' => $section['blocks'], 'useSample' => $useSample])
                    </div>
                </section>
            @endforeach
            <div class="mt-10">
                <nav class="mb-4 bg-white border rounded p-3 text-xs flex flex-wrap gap-3">
                    @foreach(($simulatedMenus['primary'] ?? []) as $m)
                        <span class="text-gray-700 hover:text-indigo-600 cursor-default">{{ $m['title'] }}</span>
                    @endforeach
                </nav>
                <footer class="bg-white border rounded p-4">
                    <div class="grid md:grid-cols-2 gap-6">
                        @foreach(($simulatedWidgets['footer'] ?? []) as $w)
                            <div>
                                <h5 class="text-sm font-semibold mb-2">{{ $w['title'] }}</h5>
                                <p class="text-xs text-gray-600 whitespace-pre-line">{{ $w['content'] }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 text-[10px] text-gray-400">Simulated menus & widgets</div>
                </footer>
            </div>
        @endif
    </div>
@endif
@endsection
