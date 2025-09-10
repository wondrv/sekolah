@extends('layouts.admin')

@section('title', 'Menu Management')

@section('content')
@include('components.admin.alerts')

<div class="mb-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Menu Management</h1>
        <a href="{{ route('admin.menus.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Buat Menu Baru
        </a>
    </div>
    <p class="text-gray-600 mt-2">Kelola navigasi dan menu website sekolah</p>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Menu
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Location
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Items
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Aksi
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($menus as $menu)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $menu->name }}</div>
                            @if($menu->description)
                            <div class="text-sm text-gray-500">{{ $menu->description }}</div>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-medium bg-gray-100 text-gray-800 rounded-full">
                            {{ ucfirst($menu->location) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $menu->items->count() }} items</div>
                        <div class="text-sm text-gray-500">{{ $menu->items->where('is_active', true)->count() }} active</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            {{ $menu->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $menu->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.menus.show', $menu) }}"
                               class="text-blue-600 hover:text-blue-900">View</a>
                            <a href="{{ route('admin.menus.edit', $menu) }}"
                               class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            <form action="{{ route('admin.menus.destroy', $menu) }}"
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
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No menus found</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new menu.</p>
                        <div class="mt-6">
                            <a href="{{ route('admin.menus.create') }}"
                               class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                New Menu
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Menu Types Info -->
<div class="mt-8 grid md:grid-cols-2 gap-6">
    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-green-900 mb-3">Menu Locations</h3>
        <div class="text-green-800 text-sm space-y-2">
            <div class="flex items-center">
                <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                <strong>Header:</strong> Navigasi utama di atas halaman
            </div>
            <div class="flex items-center">
                <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                <strong>Footer:</strong> Menu di bagian bawah halaman
            </div>
            <div class="flex items-center">
                <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                <strong>Sidebar:</strong> Menu samping (jika ada)
            </div>
        </div>
    </div>

    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-3">Menu Features</h3>
        <div class="text-blue-800 text-sm space-y-2">
            <div class="flex items-center">
                <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                Hierarchical menu structure
            </div>
            <div class="flex items-center">
                <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                Drag & drop ordering
            </div>
            <div class="flex items-center">
                <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                Custom URLs and internal links
            </div>
            <div class="flex items-center">
                <span class="w-3 h-3 bg-blue-500 rounded-full mr-2"></span>
                Active/inactive menu items
            </div>
        </div>
    </div>
</div>
@endsection
