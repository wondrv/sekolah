@extends('layouts.app')

@section('title', $template->name ?? 'Page')

@push('styles')
<style>
{!! app(\App\Services\TemplateRenderService::class)->generateCssVariables() !!}
</style>
@endpush

@section('content')
@include('partials.preview-banner')
<div class="template-render" data-template="{{ $template->id }}">
    @foreach($sections as $sectionData)
        @php
            $section = $sectionData['section'];
            $blocks = $sectionData['blocks'];
        @endphp

        <section
            class="template-section {{ $section->css_classes ?? '' }}"
            data-section="{{ $section->id }}"
            @if($section->css_id ?? false) id="{{ $section->css_id }}" @endif
            @if($section->background_color ?? false) style="background-color: {{ $section->background_color }};" @endif
        >
            @if($section->container ?? true)
                <div class="container mx-auto px-4">
            @endif

            <div class="section-content">
                @foreach($blocks as $blockHtml)
                    {!! $blockHtml !!}
                @endforeach
            </div>

            @if($section->container ?? true)
                </div>
            @endif
        </section>
    @endforeach
</div>

@if(auth()->check() && auth()->user()->is_admin)
<div class="template-editor-toggle">
    <button
        id="toggle-page-builder"
        class="fixed bottom-4 right-4 bg-blue-600 text-white px-4 py-2 rounded-lg shadow-lg hover:bg-blue-700 z-50"
        onclick="togglePageBuilder()"
    >
        <i class="fas fa-edit mr-2"></i>
        Edit Page
    </button>
</div>

<div id="page-builder-container" class="hidden">
    <div id="page-builder-root"></div>
</div>

@push('scripts')
<script>
let pageBuilderActive = false;

function togglePageBuilder() {
    const container = document.getElementById('page-builder-container');
    const button = document.getElementById('toggle-page-builder');

    if (!pageBuilderActive) {
        container.classList.remove('hidden');
        button.innerHTML = '<i class="fas fa-times mr-2"></i>Close Editor';
        loadPageBuilder();
        pageBuilderActive = true;
    } else {
        container.classList.add('hidden');
        button.innerHTML = '<i class="fas fa-edit mr-2"></i>Edit Page';
        pageBuilderActive = false;
    }
}

function loadPageBuilder() {
    // This would load the React PageBuilder component
    // For now, we'll show a placeholder
    document.getElementById('page-builder-root').innerHTML = `
        <div class="fixed inset-0 bg-white z-40 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold">Page Builder</h2>
                <button onclick="togglePageBuilder()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="text-center py-20">
                <p class="text-gray-600 mb-4">Page Builder will be loaded here</p>
                <p class="text-sm text-gray-500">This will integrate with the React PageBuilder component</p>
            </div>
        </div>
    `;
}
</script>
@endpush
@endif
@endsection
