@extends('layouts.admin')

@section('title', 'Page Builder - ' . $page->title)

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Page Builder will be mounted here -->
    <div id="page-builder-app"></div>
</div>

<!-- Pass data to React component -->
<script>
window.pageBuilderData = {
    page: @json($page),
    blocks: @json($page->content_json ?? []),
    availableBlocks: @json($availableBlocks),
    saveUrl: '{{ route('admin.pages.builder.save', $page) }}',
    previewUrl: '{{ route('pages.show', $page) }}',
    csrfToken: '{{ csrf_token() }}'
};
</script>
@endsection

@push('scripts')
<!-- Page Builder bundle (non-Vite) -->
<script src="/assets/js/page-builder.js" defer></script>
@endpush