@extends('layouts.admin')

@section('title', 'Inbox Messages')

@section('content')
@include('components.admin.alerts')

<div class="mb-6 flex justify-between items-center">
    <h2 class="text-2xl font-bold text-gray-900">Inbox Messages</h2>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow mb-6 p-4">
    <form method="GET" class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-64">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search messages..."
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <select name="status" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">All Status</option>
            <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Unread</option>
            <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Read</option>
            <option value="replied" {{ request('status') == 'replied' ? 'selected' : '' }}>Replied</option>
            <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
        </select>
        <select name="type" class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">All Types</option>
            <option value="contact" {{ request('type') == 'contact' ? 'selected' : '' }}>Contact</option>
            <option value="complaint" {{ request('type') == 'complaint' ? 'selected' : '' }}>Complaint</option>
            <option value="suggestion" {{ request('type') == 'suggestion' ? 'selected' : '' }}>Suggestion</option>
        </select>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Filter
        </button>
        <a href="{{ route('admin.messages.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
            Reset
        </a>
    </form>
</div>

<!-- Messages Table -->
<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Sender
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Subject
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Type
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Status
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Date
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($messages as $message)
                <tr class="hover:bg-gray-50 {{ $message->status === 'unread' ? 'bg-blue-50' : '' }}">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div>
                            <div class="text-sm font-medium text-gray-900 {{ $message->status === 'unread' ? 'font-bold' : '' }}">
                                {{ $message->name }}
                            </div>
                            <div class="text-sm text-gray-500">{{ $message->email }}</div>
                            @if($message->phone)
                            <div class="text-sm text-gray-500">{{ $message->phone }}</div>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900 {{ $message->status === 'unread' ? 'font-bold' : '' }}">
                            {{ Str::limit($message->subject, 50) }}
                        </div>
                        <div class="text-sm text-gray-500">
                            {{ Str::limit($message->message, 80) }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full
                            @if($message->type === 'contact') bg-blue-100 text-blue-800
                            @elseif($message->type === 'complaint') bg-red-100 text-red-800
                            @else bg-yellow-100 text-yellow-800 @endif">
                            {{ ucfirst($message->type) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full
                            @if($message->status === 'unread') bg-red-100 text-red-800
                            @elseif($message->status === 'read') bg-yellow-100 text-yellow-800
                            @elseif($message->status === 'replied') bg-green-100 text-green-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($message->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $message->created_at->format('M d, Y') }}
                        <div class="text-xs text-gray-400">{{ $message->created_at->format('H:i') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex items-center justify-end space-x-2">
                            <a href="{{ route('admin.messages.show', $message) }}"
                               class="text-indigo-600 hover:text-indigo-900">View</a>

                            <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" data-confirm="pesan dari: {{ $message->name }}"
                                  class="inline" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <p>No messages found</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($messages->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $messages->links() }}
    </div>
    @endif
</div>
@endsection
