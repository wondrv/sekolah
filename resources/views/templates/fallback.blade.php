@extends('layouts.app')

@section('title', 'Page Not Found')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50">
    <div class="max-w-md w-full text-center">
        <h1 class="text-6xl font-bold text-gray-300 mb-4">404</h1>
        <h2 class="text-2xl font-semibold text-gray-800 mb-4">Page Not Found</h2>
        <p class="text-gray-600 mb-8">
            The page you're looking for doesn't exist or no template has been assigned to it.
        </p>
        <a 
            href="{{ route('home') }}" 
            class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
        >
            <i class="fas fa-home mr-2"></i>
            Back to Home
        </a>
        
        @if(auth()->check() && auth()->user()->is_admin)
        <div class="mt-8 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
            <p class="text-sm text-yellow-800">
                <strong>Admin Notice:</strong> You can assign a template to this route in the 
                <a href="{{ route('admin.templates.index') }}" class="underline">Template Builder</a>.
            </p>
        </div>
        @endif
    </div>
</div>
@endsection