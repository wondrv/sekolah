@extends('layouts.admin')

@section('title', 'View Message')

@section('content')
@include('components.admin.alerts')

<div class="mb-6">
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('admin.messages.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Messages
            </a>
        </div>
        <div class="flex space-x-2">
            @if($message->status !== 'archived')
            <form action="{{ route('admin.messages.update', $message) }}" method="POST" class="inline">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="archived">
                <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                    Archive
                </button>
            </form>
            @endif

            <form action="{{ route('admin.messages.destroy', $message) }}" method="POST"
                  class="inline" onsubmit="return confirm('Are you sure?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow">
    <!-- Message Header -->
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-semibold text-gray-900">{{ $message->subject }}</h1>
                <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500">
                    <span>From: <strong>{{ $message->name }}</strong></span>
                    <span>{{ $message->email }}</span>
                    @if($message->phone)
                    <span>{{ $message->phone }}</span>
                    @endif
                </div>
            </div>
            <div class="text-right">
                <div class="flex space-x-2 mb-2">
                    <span class="px-2 py-1 text-xs rounded-full
                        @if($message->type === 'contact') bg-blue-100 text-blue-800
                        @elseif($message->type === 'enrollment') bg-green-100 text-green-800
                        @elseif($message->type === 'complaint') bg-red-100 text-red-800
                        @else bg-yellow-100 text-yellow-800 @endif">
                        {{ ucfirst($message->type) }}
                    </span>
                    <span class="px-2 py-1 text-xs rounded-full
                        @if($message->status === 'unread') bg-red-100 text-red-800
                        @elseif($message->status === 'read') bg-yellow-100 text-yellow-800
                        @elseif($message->status === 'replied') bg-green-100 text-green-800
                        @else bg-gray-100 text-gray-800 @endif">
                        {{ ucfirst($message->status) }}
                    </span>
                </div>
                <div class="text-sm text-gray-500">
                    {{ $message->created_at->format('M d, Y \a\t H:i') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Message Body -->
    <div class="px-6 py-6">
        <div class="prose max-w-none">
            {!! nl2br(e($message->message)) !!}
        </div>
    </div>

    <!-- Admin Reply Section -->
    @if($message->admin_reply)
    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
        <h3 class="text-lg font-medium text-gray-900 mb-3">Admin Reply</h3>
        <div class="bg-white p-4 rounded-lg border">
            <div class="prose max-w-none">
                {!! nl2br(e($message->admin_reply)) !!}
            </div>
            <div class="mt-4 text-sm text-gray-500">
                Replied by {{ $message->repliedBy->name ?? 'Admin' }} on {{ $message->replied_at->format('M d, Y \a\t H:i') }}
            </div>
        </div>
    </div>
    @endif

    <!-- Reply Form -->
    @if($message->status !== 'replied' && $message->status !== 'archived')
    <div class="px-6 py-6 border-t border-gray-200">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Send Reply</h3>
        <form action="{{ route('admin.messages.reply', $message) }}" method="POST">
            @csrf
            @method('PATCH')

            <div class="mb-4">
                <label for="admin_reply" class="block text-sm font-medium text-gray-700 mb-2">
                    Reply Message
                </label>
                <textarea name="admin_reply" id="admin_reply" rows="5"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                          placeholder="Type your reply here..."
                          required>{{ old('admin_reply') }}</textarea>
                @error('admin_reply')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                    Send Reply
                </button>
            </div>
        </form>
    </div>
    @endif
</div>
@endsection
