<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_number',
        'name',
        'nickname',
        'gender',
        'birth_date',
        'birth_place',
        'religion',
        'nationality',
        'address',
        'phone',
        'email',
        'father_name',
        'father_occupation',
        'father_phone',
        'mother_name',
        'mother_occupation',
        'mother_phone',
        'guardian_name',
        'guardian_phone',
        'guardian_relation',
        'previous_school',
        'previous_school_address',
        'graduation_year',
        'final_score',
        'photo',
        'birth_certificate',
        'family_card',
        'transcript',
        'other_documents',
        'status',
        'notes',
        'registered_at',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'graduation_year' => 'integer',
        'final_score' => 'decimal:2',
        'other_documents' => 'array',
        'registered_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    /**
     * Get the user who approved this student
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Scope for pending students
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved students
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for enrolled students
     */
    public function scopeEnrolled($query)
    {
        return $query->where('status', 'enrolled');
    }

    /**
     * Generate registration number
     */
    public static function generateRegistrationNumber(): string
    {
        $year = date('Y');
        $count = static::whereYear('created_at', $year)->count() + 1;
        
        return sprintf('PPDB%s%04d', $year, $count);
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'approved' => 'green',
            'rejected' => 'red',
            'enrolled' => 'blue',
            default => 'gray'
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Pending',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            'enrolled' => 'Enrolled',
            default => 'Unknown'
        };
    }

    /**
     * Get full name with nickname
     */
    public function getFullNameAttribute(): string
    {
        return $this->nickname ? "{$this->name} ({$this->nickname})" : $this->name;
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($student) {
            if (empty($student->registration_number)) {
                $student->registration_number = static::generateRegistrationNumber();
            }
            
            if (empty($student->registered_at)) {
                $student->registered_at = now();
            }
        });
    }
}
