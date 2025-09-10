@extends('layouts.admin')

@section('title', 'View Enrollment')

@section('content')
@include('components.admin.alerts')

<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.enrollments.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Enrollments
            </a>
        </div>
        <div class="flex space-x-2">
            @if($enrollment->status === 'pending')
            <form action="{{ route('admin.enrollments.approve', $enrollment) }}" method="POST" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" onclick="return confirm('Approve this enrollment?')"
                        class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                    Approve Enrollment
                </button>
            </form>

            <form action="{{ route('admin.enrollments.reject', $enrollment) }}" method="POST" class="inline">
                @csrf
                @method('PATCH')
                <button type="submit" onclick="return confirm('Reject this enrollment?')"
                        class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                    Reject Enrollment
                </button>
            </form>
            @endif

            <form action="{{ route('admin.enrollments.destroy', $enrollment) }}" method="POST"
                  class="inline" onsubmit="return confirm('Are you sure?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow">
    <!-- Enrollment Header -->
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">Enrollment Application</h1>
                <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500">
                    <span>Application ID: <strong>#{{ $enrollment->id }}</strong></span>
                    <span>Submitted: {{ $enrollment->created_at->format('M d, Y \a\t H:i') }}</span>
                </div>
            </div>
            <div class="text-right">
                <span class="px-3 py-1 text-sm rounded-full
                    @if($enrollment->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($enrollment->status === 'approved') bg-green-100 text-green-800
                    @elseif($enrollment->status === 'rejected') bg-red-100 text-red-800
                    @endif">
                    {{ ucfirst($enrollment->status) }}
                </span>
                @if($enrollment->status !== 'pending')
                <div class="text-sm text-gray-500 mt-1">
                    {{ ucfirst($enrollment->status) }} on {{ $enrollment->updated_at->format('M d, Y') }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Student Information -->
    <div class="px-6 py-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Student Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                <p class="text-sm text-gray-900">{{ $enrollment->student_name }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                <p class="text-sm text-gray-900">
                    {{ \Carbon\Carbon::parse($enrollment->date_of_birth)->format('F d, Y') }}
                    <span class="text-gray-500">
                        ({{ \Carbon\Carbon::parse($enrollment->date_of_birth)->age }} years old)
                    </span>
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Gender</label>
                <p class="text-sm text-gray-900">{{ ucfirst($enrollment->gender) }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                <p class="text-sm text-gray-900">{{ $enrollment->address }}</p>
            </div>
        </div>
    </div>

    <!-- Program Information -->
    <div class="px-6 py-6 border-t border-gray-200">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Program Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Program</label>
                <p class="text-sm text-gray-900">{{ $enrollment->program }}</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Grade Level</label>
                <p class="text-sm text-gray-900">{{ $enrollment->grade_level }}</p>
            </div>

            @if($enrollment->previous_school)
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Previous School</label>
                <p class="text-sm text-gray-900">{{ $enrollment->previous_school }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Parent/Guardian Information -->
    <div class="px-6 py-6 border-t border-gray-200">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Parent/Guardian Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @if($enrollment->parent_name)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Parent/Guardian Name</label>
                <p class="text-sm text-gray-900">{{ $enrollment->parent_name }}</p>
            </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <p class="text-sm text-gray-900">
                    <a href="mailto:{{ $enrollment->email }}" class="text-blue-600 hover:text-blue-800">
                        {{ $enrollment->email }}
                    </a>
                </p>
            </div>

            @if($enrollment->phone)
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                <p class="text-sm text-gray-900">
                    <a href="tel:{{ $enrollment->phone }}" class="text-blue-600 hover:text-blue-800">
                        {{ $enrollment->phone }}
                    </a>
                </p>
            </div>
            @endif
        </div>
    </div>

    <!-- Additional Information -->
    @if($enrollment->additional_info)
    <div class="px-6 py-6 border-t border-gray-200">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Additional Information</h2>
        <div class="prose max-w-none">
            {!! nl2br(e($enrollment->additional_info)) !!}
        </div>
    </div>
    @endif

    <!-- Documents -->
    @if($enrollment->documents)
    <div class="px-6 py-6 border-t border-gray-200">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Uploaded Documents</h2>
        <div class="space-y-2">
            @foreach(json_decode($enrollment->documents, true) as $document)
            <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="text-sm text-gray-900">{{ basename($document) }}</span>
                <a href="{{ Storage::url($document) }}"
                   target="_blank"
                   class="text-blue-600 hover:text-blue-800 text-sm ml-auto">
                    Download
                </a>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Admin Notes Section -->
    @if($enrollment->admin_notes)
    <div class="px-6 py-6 border-t border-gray-200 bg-gray-50">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Admin Notes</h2>
        <div class="prose max-w-none">
            {!! nl2br(e($enrollment->admin_notes)) !!}
        </div>
    </div>
    @endif

    <!-- Add Notes Form (for admins) -->
    @if(auth()->user()->hasRole('admin'))
    <div class="px-6 py-6 border-t border-gray-200">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Admin Notes</h3>
        <form action="{{ route('admin.enrollments.update', $enrollment) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="mb-4">
                <label for="admin_notes" class="block text-sm font-medium text-gray-700 mb-2">
                    Internal Notes (not visible to applicant)
                </label>
                <textarea name="admin_notes" id="admin_notes" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Add internal notes about this enrollment...">{{ old('admin_notes', $enrollment->admin_notes) }}</textarea>
                @error('admin_notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Save Notes
                </button>
            </div>
        </form>
    </div>
    @endif
</div>
@endsection
