@extends('layouts.admin')

@section('title', 'Template Exports')

@section('header')
<div class="flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Template Exports</h1>
        <p class="text-gray-600">Manage your exported templates and download history</p>
    </div>

    <div class="flex space-x-3">
        <form method="POST" action="{{ route('admin.templates.exports.cleanup-expired') }}" class="inline">
            @csrf
            <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50"
                    onclick="return confirm('Cleanup expired exports?')">
                <span class="mr-2">🗑️</span>
                Cleanup Expired
            </button>
        </form>
    </div>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                            <span class="text-white text-sm">📦</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Exports</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['total'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                            <span class="text-white text-sm">✅</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Active</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['active'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                            <span class="text-white text-sm">⏰</span>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Expired</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['expired'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white shadow rounded-lg p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">All Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Format</label>
                <select name="format" class="block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">All Formats</option>
                    <option value="json" {{ request('format') === 'json' ? 'selected' : '' }}>JSON</option>
                    <option value="zip" {{ request('format') === 'zip' ? 'selected' : '' }}>ZIP</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Template name or filename..."
                       class="block w-full border-gray-300 rounded-md shadow-sm">
            </div>

            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Exports List -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        @if($exports->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" id="select-all" class="rounded border-gray-300">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Template</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Filename</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Format</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Size</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expires</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($exports as $export)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" name="export_ids[]" value="{{ $export->id }}"
                                   class="export-checkbox rounded border-gray-300">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $export->userTemplate->name ?? 'Deleted Template' }}
                            </div>
                            @if($export->userTemplate)
                            <div class="text-sm text-gray-500">{{ $export->userTemplate->source }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $export->filename }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $export->format === 'zip' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ strtoupper($export->format) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $export->file_size ? number_format($export->file_size / 1024, 1) . ' KB' : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($export->isExpired())
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Expired
                            </span>
                            @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                Active
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $export->created_at->format('M j, Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $export->expires_at ? $export->expires_at->format('M j, Y H:i') : 'Never' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                @if(!$export->isExpired())
                                <a href="{{ route('admin.templates.exports.download', $export) }}"
                                   class="text-blue-600 hover:text-blue-900">
                                    Download
                                </a>
                                @endif

                                <form method="POST" action="{{ route('admin.templates.exports.destroy', $export) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-red-600 hover:text-red-900"
                                            onclick="return confirm('Delete this export?')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Bulk Actions -->
        <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <span id="selected-count" class="text-sm text-gray-700">0 selected</span>
                    <form method="POST" action="{{ route('admin.templates.exports.bulk-download') }}" class="inline" id="bulk-download-form">
                        @csrf
                        <input type="hidden" name="export_ids" id="bulk-export-ids">
                        <button type="submit"
                                class="text-blue-600 hover:text-blue-900 disabled:text-gray-400"
                                id="bulk-download-btn" disabled>
                            Bulk Download
                        </button>
                    </form>
                </div>

                <div class="flex items-center">
                    {{ $exports->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        @else
        <div class="text-center py-12">
            <div class="text-6xl mb-4">📦</div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No exports yet</h3>
            <p class="text-gray-500 mb-6">Export some templates to see them here.</p>
            <a href="{{ route('admin.templates.my-templates.index') }}"
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                Go to My Templates
            </a>
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('select-all');
    const exportCheckboxes = document.querySelectorAll('.export-checkbox');
    const selectedCountElement = document.getElementById('selected-count');
    const bulkDownloadBtn = document.getElementById('bulk-download-btn');
    const bulkExportIdsInput = document.getElementById('bulk-export-ids');

    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.export-checkbox:checked');
        const count = checkedBoxes.length;

        selectedCountElement.textContent = `${count} selected`;
        bulkDownloadBtn.disabled = count === 0;

        if (count > 0) {
            const ids = Array.from(checkedBoxes).map(cb => cb.value);
            bulkExportIdsInput.value = JSON.stringify(ids);
        }
    }

    selectAllCheckbox.addEventListener('change', function() {
        exportCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateBulkActions();
    });

    exportCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateBulkActions);
    });

    updateBulkActions();
});
</script>
@endsection
