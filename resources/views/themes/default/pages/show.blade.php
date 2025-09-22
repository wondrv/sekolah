@extends('themes.default.layouts.app')

@section('content')
<div class="min-h-screen">
    @if($page->use_page_builder)
        <!-- Page Builder Content -->
        {!! $page->rendered_content !!}
    @else
        <!-- Traditional Content -->
        <div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-6">{{ $page->title }}</h1>
                
                <div class="prose prose-lg max-w-none">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    @endif
</div>
@endsection