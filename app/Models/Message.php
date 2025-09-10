<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'type',
        'status',
        'admin_reply',
        'replied_at',
        'replied_by'
    ];

    protected $casts = [
        'replied_at' => 'datetime',
        'submitted_at' => 'datetime'
    ];

    public function repliedBy()
    {
        return $this->belongsTo(User::class, 'replied_by');
    }

    public function scopeUnread($query)
    {
        return $query->where('status', 'unread');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function markAsRead()
    {
        $this->update(['status' => 'read']);
    }

    public function markAsReplied($adminReply, $userId)
    {
        $this->update([
            'status' => 'replied',
            'admin_reply' => $adminReply,
            'replied_at' => now(),
            'replied_by' => $userId
        ]);
    }
}
