@extends('layouts.admin')

@section('title', 'Template Builder')

@section('content')
@include('components.admin.alerts')

<div class="mb-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Template Builder</h1>
        <a href="{{ route('admin.templates.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Buat Template Baru
        </a>
    </div>
    <p class="text-gray-600 mt-2">Kelola template dan layout untuk halaman website</p>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Template
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Sections
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Dibuat
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($templates as $template)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $template->name }}</div>
                            @if($template->description)
                            <div class="text-sm text-gray-500">{{ $template->description }}</div>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $template->sections->count() }} sections</div>
                        <div class="text-sm text-gray-500">{{ $template->sections->sum(function($section) { return $section->blocks->count(); }) }} blocks</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ $template->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $template->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $template->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.templates.show', $template) }}"
                               class="text-blue-600 hover:text-blue-900">View</a>
                            <a href="{{ route('admin.templates.edit', $template) }}"
                               class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            <form action="{{ route('admin.templates.destroy', $template) }}"
                                  method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Are you sure?')"
                                        class="text-red-600 hover:text-red-900">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No templates found</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new template.</p>
                        <div class="mt-6">
                            <a href="{{ route('admin.templates.create') }}"
                               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                New Template
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Quick Info Card -->
<div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
    <h3 class="text-lg font-semibold text-blue-900 mb-3">Template Builder Features</h3>
    <div class="grid md:grid-cols-2 gap-4 text-blue-800 text-sm">
        <div>
            <h4 class="font-medium mb-2">Available Block Types:</h4>
            <ul class="space-y-1">
                <li>• Hero Section - Banner utama</li>
                <li>• Card Grid - Grid dengan kartu</li>
                <li>• Rich Text - Konten teks kaya</li>
                <li>• Statistics - Statistik sekolah</li>
            </ul>
        </div>
        <div>
            <h4 class="font-medium mb-2">Template Features:</h4>
            <ul class="space-y-1">
                <li>• Drag & Drop Sections</li>
                <li>• Responsive Layout</li>
                <li>• Dynamic Content</li>
                <li>• SEO Optimized</li>
            </ul>
        </div>
    </div>
</div>
@endsection
