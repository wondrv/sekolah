<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'registration_number',
        'student_name',
        'student_nik',
        'date_of_birth',
        'place_of_birth',
        'gender',
        'religion',
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
        'previous_school',
        'desired_program',
        'academic_year',
        'birth_certificate_path',
        'family_card_path',
        'report_card_path',
        'photo_path',
        'status',
        'notes',
        'submitted_at',
        'processed_at',
        'processed_by'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'submitted_at' => 'datetime',
        'processed_at' => 'datetime'
    ];

    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeByAcademicYear($query, $year)
    {
        return $query->where('academic_year', $year);
    }

    public function approve($userId, $notes = null)
    {
        $this->update([
            'status' => 'approved',
            'processed_at' => now(),
            'processed_by' => $userId,
            'notes' => $notes
        ]);
    }

    public function reject($userId, $notes)
    {
        $this->update([
            'status' => 'rejected',
            'processed_at' => now(),
            'processed_by' => $userId,
            'notes' => $notes
        ]);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($enrollment) {
            if (!$enrollment->registration_number) {
                $enrollment->registration_number = 'REG' . date('Y') . str_pad(static::count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}
