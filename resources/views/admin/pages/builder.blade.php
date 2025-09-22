@extends('admin.layouts.app')

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
<!-- React and Page Builder -->
<script src="https://unpkg.com/react@18/umd/react.production.min.js"></script>
<script src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js"></script>
@vite(['resources/js/page-builder.jsx'])
@endpush