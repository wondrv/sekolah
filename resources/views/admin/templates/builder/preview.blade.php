@extends('layouts.admin')

@section('title', 'Preview Template - ' . $userTemplate->name)

@section('header')
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Template Preview</h1>
        <p class="text-gray-600 text-sm">Preview of: <strong>{{ $userTemplate->name }}</strong></p>
    </div>
    <div class="flex items-center space-x-2">
        <a href="{{ route('admin.templates.builder.edit', $userTemplate) }}" class="inline-flex items-center px-4 py-2 rounded-md bg-white border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50">
            ← Back to Builder
        </a>
        <a href="{{ route('admin.templates.builder.preview', $userTemplate) }}" class="inline-flex items-center px-4 py-2 rounded-md bg-blue-600 text-white text-sm font-medium hover:bg-blue-700">
            🔄 Refresh
        </a>
    </div>
</div>
@endsection

@section('content')
<div class="bg-white shadow rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Live Preview</h2>
            <p class="text-xs text-gray-500">This shows your current saved template structure. Save changes in builder then refresh.</p>
        </div>
        <div class="flex items-center space-x-2">
            <button id="toggleGrid" class="px-3 py-1.5 text-xs rounded-md bg-gray-100 hover:bg-gray-200 text-gray-700">Toggle Grid</button>
        </div>
    </div>

    <div id="previewArea" class="relative">
        <!-- Optional grid overlay -->
        <div id="gridOverlay" class="absolute inset-0 pointer-events-none hidden" style="background-image: linear-gradient(rgba(0,0,0,0.06) 1px, transparent 1px), linear-gradient(90deg, rgba(0,0,0,0.06) 1px, transparent 1px); background-size: 40px 40px;"></div>

        <div class="min-h-[60vh]">
            @php
                $sections = $previewData['sections'] ?? [];
            @endphp

            @if(is_array($sections) && count($sections))
                @foreach($sections as $sectionIndex => $section)
                    @php
                        $blocks = $section['blocks'] ?? [];
                        $sectionName = $section['name'] ?? 'Section ' . ($sectionIndex + 1);
                        $active = $section['active'] ?? true;
                    @endphp
                    @if($active)
                        <section class="py-12 border-b border-gray-100 last:border-0">
                            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                                <div class="mb-6 flex items-center justify-between">
                                    <h3 class="text-lg font-bold text-gray-800">{{ $sectionName }}</h3>
                                    <span class="text-xs px-2 py-1 rounded bg-gray-100 text-gray-600">#{{ $sectionIndex + 1 }}</span>
                                </div>

                                @if(is_array($blocks) && count($blocks))
                                    <div class="space-y-10">
                                        @foreach($blocks as $blockIndex => $block)
                                            @php
                                                $type = is_array($block) ? ($block['type'] ?? 'unknown') : 'unknown';
                                                $activeBlock = is_array($block) ? ($block['active'] ?? true) : true;
                                            @endphp
                                            @if($activeBlock)
                                                <div class="relative group">
                                                    <div class="absolute -top-3 left-0 z-10">
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-blue-600 text-white text-[10px] font-medium tracking-wide shadow">{{ strtoupper(str_replace('-', ' ', $type)) }}</span>
                                                    </div>
                                                    <div class="block-render-wrapper ring-1 ring-gray-200 rounded-lg overflow-hidden bg-white">
                                                        @switch($type)
                                                            @case('hero')
                                                                @php $blockObj = (object)['data' => ['content' => $block['data'] ?? $block]]; @endphp
                                                                <x-block-renderer :block="$blockObj" />
                                                                @break
                                                            @case('card-grid')
                                                                @php $blockObj = (object)['data' => ['content' => $block['data'] ?? $block]]; @endphp
                                                                <x-block-renderer :block="$blockObj" />
                                                                @break
                                                            @case('rich-text')
                                                                @php $blockObj = (object)['data' => ['content' => $block['data'] ?? $block]]; @endphp
                                                                <x-block-renderer :block="$blockObj" />
                                                                @break
                                                            @case('stats')
                                                                @php $blockObj = (object)['data' => ['content' => $block['data'] ?? $block]]; @endphp
                                                                <x-block-renderer :block="$blockObj" />
                                                                @break
                                                            @case('cta-banner')
                                                                @php $blockObj = (object)['data' => ['content' => $block['data'] ?? $block]]; @endphp
                                                                <x-block-renderer :block="$blockObj" />
                                                                @break
                                                            @case('gallery-teaser')
                                                            @case('events-teaser')
                                                                @php $blockObj = (object)['data' => ['content' => $block['data'] ?? $block]]; @endphp
                                                                <x-block-renderer :block="$blockObj" />
                                                                @break
                                                            @default
                                                                <div class="p-8 bg-gray-50 border border-dashed border-gray-300 text-center text-gray-500 text-sm rounded-lg">
                                                                    <p class="font-medium mb-1">Unsupported block type: <code>{{ $type }}</code></p>
                                                                    <p class="text-xs">Add a renderer for this type in preview view.</p>
                                                                </div>
                                                        @endswitch
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <div class="p-12 text-center text-gray-500 border-2 border-dashed border-gray-200 rounded-lg bg-gray-50">
                                        <p class="font-medium mb-1">No blocks yet</p>
                                        <p class="text-xs">Return to builder and drag blocks into this section.</p>
                                    </div>
                                @endif
                            </div>
                        </section>
                    @endif
                @endforeach
            @else
                <div class="p-16 text-center text-gray-500">
                    <p class="font-medium mb-2">No sections found</p>
                    <p class="text-sm">Create sections in the builder to see them here.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('toggleGrid').addEventListener('click', () => {
            const grid = document.getElementById('gridOverlay');
            grid.classList.toggle('hidden');
        });
    });
</script>
@endsection
