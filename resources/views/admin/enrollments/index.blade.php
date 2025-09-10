@extends('layouts.admin')

@section('title', 'Student Enrollments')

@section('content')
@include('components.admin.alerts')

<div class="mb-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Student Enrollments</h1>
        <div class="flex items-center space-x-4">
            <!-- Status Filter -->
            <select id="statusFilter" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>

            <!-- Search -->
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Search enrollments..."
                       value="{{ request('search') }}"
                       class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center">
            <div class="p-2 bg-yellow-100 rounded-lg">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Pending</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center">
            <div class="p-2 bg-green-100 rounded-lg">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Approved</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['approved'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center">
            <div class="p-2 bg-red-100 rounded-lg">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Rejected</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $stats['rejected'] ?? 0 }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center">
            <div class="p-2 bg-blue-100 rounded-lg">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">Total</p>
                <p class="text-2xl font-semibold text-gray-900">{{ $enrollments->total() }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Enrollments Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Student Info
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Program
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Contact
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Applied
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($enrollments as $enrollment)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <div class="text-sm font-medium text-gray-900">
                                {{ $enrollment->student_name }}
                            </div>
                            <div class="text-sm text-gray-500">
                                Born: {{ \Carbon\Carbon::parse($enrollment->date_of_birth)->format('M d, Y') }}
                            </div>
                            @if($enrollment->parent_name)
                            <div class="text-xs text-gray-400">
                                Parent: {{ $enrollment->parent_name }}
                            </div>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $enrollment->program }}</div>
                        <div class="text-sm text-gray-500">Grade: {{ $enrollment->grade_level }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $enrollment->email }}</div>
                        @if($enrollment->phone)
                        <div class="text-sm text-gray-500">{{ $enrollment->phone }}</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            @if($enrollment->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($enrollment->status === 'approved') bg-green-100 text-green-800
                            @elseif($enrollment->status === 'rejected') bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($enrollment->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $enrollment->created_at->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.enrollments.show', $enrollment) }}"
                               class="text-blue-600 hover:text-blue-900">View</a>

                            @if($enrollment->status === 'pending')
                            <form action="{{ route('admin.enrollments.approve', $enrollment) }}"
                                  method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        onclick="return confirm('Approve this enrollment?')"
                                        class="text-green-600 hover:text-green-900">
                                    Approve
                                </button>
                            </form>

                            <form action="{{ route('admin.enrollments.reject', $enrollment) }}"
                                  method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                        onclick="return confirm('Reject this enrollment?')"
                                        class="text-red-600 hover:text-red-900">
                                    Reject
                                </button>
                            </form>
                            @endif

                            <form action="{{ route('admin.enrollments.destroy', $enrollment) }}"
                                  method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Are you sure you want to delete this enrollment?')"
                                        class="text-red-600 hover:text-red-900">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No enrollments found</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            @if(request('search') || request('status'))
                                Try adjusting your search or filter criteria.
                            @else
                                No enrollment applications have been submitted yet.
                            @endif
                        </p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($enrollments->hasPages())
    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
        {{ $enrollments->links() }}
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');

    function updateFilters() {
        const url = new URL(window.location);
        const search = searchInput.value;
        const status = statusFilter.value;

        if (search) {
            url.searchParams.set('search', search);
        } else {
            url.searchParams.delete('search');
        }

        if (status) {
            url.searchParams.set('status', status);
        } else {
            url.searchParams.delete('status');
        }

        url.searchParams.delete('page'); // Reset pagination
        window.location = url.toString();
    }

    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(updateFilters, 500);
    });

    statusFilter.addEventListener('change', updateFilters);
});
</script>
@endsection
