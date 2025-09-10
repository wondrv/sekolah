<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display a listing of messages
     */
    public function index(Request $request)
    {
        $query = Message::with('repliedBy')->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('subject', 'LIKE', "%{$search}%")
                  ->orWhere('message', 'LIKE', "%{$search}%");
            });
        }

        $messages = $query->paginate(20);

        return view('admin.messages.index', compact('messages'));
    }

    /**
     * Display the specified message
     */
    public function show(Message $message)
    {
        // Mark as read when viewing
        if ($message->status === 'unread') {
            $message->markAsRead();
        }

        return view('admin.messages.show', compact('message'));
    }

    /**
     * Reply to a message
     */
    public function reply(Request $request, Message $message)
    {
        $request->validate([
            'admin_reply' => 'required|string'
        ]);

        $message->markAsReplied($request->admin_reply, Auth::id());

        return redirect()->route('admin.messages.show', $message)
                        ->with('success', 'Reply sent successfully!');
    }

    /**
     * Update the specified message status
     */
    public function update(Request $request, Message $message)
    {
        $request->validate([
            'status' => 'required|in:unread,read,replied,archived'
        ]);

        $message->update(['status' => $request->status]);

        return redirect()->route('admin.messages.index')
                        ->with('success', 'Message status updated successfully!');
    }

    /**
     * Remove the specified message
     */
    public function destroy(Message $message)
    {
        $message->delete();

        return redirect()->route('admin.messages.index')
                        ->with('success', 'Message deleted successfully!');
    }
}
