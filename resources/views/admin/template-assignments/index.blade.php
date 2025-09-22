@extends('layouts.admin')

@section('title', 'Template Assignments')

@section('content')
<div class="bg-white shadow">
    <div class="px-4 py-5 sm:p-6">
        <div class="md:flex md:items-center md:justify-between">
            <div class="min-w-0 flex-1">
                <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                    Template Assignments
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Assign templates to specific routes to control page layouts
                </p>
            </div>
            <div class="mt-4 flex md:ml-4 md:mt-0">
                <button type="button" onclick="openAssignmentModal()" 
                        class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                    Assign Template
                </button>
            </div>
        </div>
    </div>
</div>

<div class="mt-8">
    <!-- Assignment Table -->
    <div class="overflow-hidden bg-white shadow sm:rounded-md">
        <ul role="list" class="divide-y divide-gray-200">
            @forelse($assignments as $assignment)
                <li class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex min-w-0 flex-1 items-center">
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center">
                                    <p class="truncate text-sm font-medium text-gray-900">
                                        {{ $assignment->route_name ?: 'Custom Route' }}
                                    </p>
                                    @if($assignment->priority > 0)
                                        <span class="ml-2 inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                                            Priority: {{ $assignment->priority }}
                                        </span>
                                    @endif
                                    @if(!$assignment->active)
                                        <span class="ml-2 inline-flex items-center rounded-full bg-red-100 px-2.5 py-0.5 text-xs font-medium text-red-800">
                                            Inactive
                                        </span>
                                    @endif
                                </div>
                                <div class="mt-1 flex items-center text-sm text-gray-500">
                                    <p class="truncate">
                                        Route: {{ $assignment->route_pattern }}
                                    </p>
                                    @if($assignment->page_slug)
                                        <span class="mx-2">•</span>
                                        <p class="truncate">
                                            Page: {{ $assignment->page_slug }}
                                        </p>
                                    @endif
                                </div>
                                <div class="mt-1 text-sm text-gray-500">
                                    Template: <span class="font-medium text-gray-900">{{ $assignment->template->name }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="ml-5 flex-shrink-0 flex space-x-2">
                            <button type="button" 
                                    onclick="editAssignment({{ $assignment->id }})"
                                    class="inline-flex items-center rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                Edit
                            </button>
                            <button type="button" 
                                    onclick="deleteAssignment({{ $assignment->id }})"
                                    class="inline-flex items-center rounded-md bg-red-600 px-2.5 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-red-500">
                                Delete
                            </button>
                        </div>
                    </div>
                </li>
            @empty
                <li class="px-6 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-semibold text-gray-900">No template assignments</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by assigning a template to a route.</p>
                    <div class="mt-6">
                        <button type="button" onclick="openAssignmentModal()"
                                class="inline-flex items-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500">
                            <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                            </svg>
                            Assign Template
                        </button>
                    </div>
                </li>
            @endforelse
        </ul>
    </div>
</div>

<!-- Assignment Modal -->
<div id="assignmentModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex min-h-screen items-end justify-center px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeAssignmentModal()"></div>
        
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <form id="assignmentForm" onsubmit="submitAssignment(event)">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modalTitle">Assign Template</h3>
                    <div class="mt-6 space-y-4">
                        <div>
                            <label for="template_id" class="block text-sm font-medium text-gray-700">Template</label>
                            <select id="template_id" name="template_id" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Select a template</option>
                                @foreach($templates as $template)
                                    <option value="{{ $template->id }}">{{ $template->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="route_pattern" class="block text-sm font-medium text-gray-700">Route Pattern</label>
                            <input type="text" id="route_pattern" name="route_pattern" required
                                   placeholder="/example/* or /page/{slug}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <p class="mt-1 text-xs text-gray-500">Use wildcards (*) or Laravel route parameters ({param})</p>
                        </div>

                        <div>
                            <label for="page_slug" class="block text-sm font-medium text-gray-700">Page Slug (Optional)</label>
                            <input type="text" id="page_slug" name="page_slug"
                                   placeholder="specific-page-slug"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <p class="mt-1 text-xs text-gray-500">For specific page assignments</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700">Priority</label>
                                <input type="number" id="priority" name="priority" value="0" min="0"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="active" name="active" checked
                                       class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <label for="active" class="ml-2 block text-sm text-gray-900">Active</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="closeAssignmentModal()"
                            class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Cancel
                    </button>
                    <button type="submit"
                            class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <span id="submitText">Assign Template</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let currentAssignmentId = null;

function openAssignmentModal(assignmentId = null) {
    currentAssignmentId = assignmentId;
    const modal = document.getElementById('assignmentModal');
    const form = document.getElementById('assignmentForm');
    const title = document.getElementById('modalTitle');
    const submitText = document.getElementById('submitText');
    
    if (assignmentId) {
        title.textContent = 'Edit Template Assignment';
        submitText.textContent = 'Update Assignment';
        // Load assignment data
        loadAssignmentData(assignmentId);
    } else {
        title.textContent = 'Assign Template';
        submitText.textContent = 'Assign Template';
        form.reset();
        document.getElementById('active').checked = true;
    }
    
    modal.classList.remove('hidden');
}

function closeAssignmentModal() {
    document.getElementById('assignmentModal').classList.add('hidden');
    currentAssignmentId = null;
}

function loadAssignmentData(assignmentId) {
    // In a real implementation, you'd fetch the assignment data via AJAX
    // For now, this is a placeholder
    console.log('Loading assignment data for ID:', assignmentId);
}

function editAssignment(assignmentId) {
    openAssignmentModal(assignmentId);
}

function submitAssignment(event) {
    event.preventDefault();
    
    const form = event.target;
    const formData = new FormData(form);
    
    // Convert checkbox to boolean
    formData.set('active', document.getElementById('active').checked ? '1' : '0');
    
    const url = currentAssignmentId 
        ? `{{ route('admin.template-assignments.update', ':id') }}`.replace(':id', currentAssignmentId)
        : `{{ route('admin.template-assignments.store') }}`;
    
    const method = currentAssignmentId ? 'PUT' : 'POST';
    
    // Add CSRF token
    formData.append('_token', '{{ csrf_token() }}');
    if (currentAssignmentId) {
        formData.append('_method', 'PUT');
    }
    
    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeAssignmentModal();
            window.location.reload();
        } else {
            alert('Error: ' + (data.message || 'Something went wrong'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while saving the assignment');
    });
}

function deleteAssignment(assignmentId) {
    if (confirm('Are you sure you want to delete this template assignment?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('admin.template-assignments.destroy', ':id') }}`.replace(':id', assignmentId);
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodInput);
        document.body.appendChild(form);
        form.submit();
    }
}

// Close modal on escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeAssignmentModal();
    }
});
</script>
@endpush