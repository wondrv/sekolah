@extends('layouts.app')

@section('title', App\Support\Theme::getSiteInfo()['name'])
@section('meta_description', App\Support\Theme::getSiteInfo()['description'])

@section('content')
@if($template && $template->sections)
    @foreach($template->sections as $section)
        @if($section->active && $section->blocks)
            @foreach($section->blocks as $block)
                @if($block->active)
                    <x-block-renderer :block="$block" />
                @endif
            @endforeach
        @endif
    @endforeach
@else
    {{-- Fallback content if no template sections --}}
    <div class="min-h-screen flex items-center justify-center bg-gray-50">
        <div class="text-center">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Welcome to {{ App\Support\Theme::getSiteInfo()['name'] }}</h1>
            <p class="text-gray-600 mb-8">Your homepage template is not configured yet.</p>
            @auth
                @if(auth()->user()->canManageSettings())
                    <a href="{{ route('admin.settings.index') }}" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Configure Site Settings
                    </a>
                @endif
            @endauth
        </div>
    </div>
@endif
@endsection
