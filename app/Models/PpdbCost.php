<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PpdbCost extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name',
        'description',
        'amount',
        'category',
        'is_mandatory',
        'is_active',
        'sort_order',
        'academic_year',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'is_mandatory' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Scope for active costs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope by category
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope by academic year
     */
    public function scopeByAcademicYear($query, string $year)
    {
        return $query->where('academic_year', $year);
    }

    /**
     * Scope for mandatory costs
     */
    public function scopeMandatory($query)
    {
        return $query->where('is_mandatory', true);
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    /**
     * Get costs grouped by category
     */
    public static function getCostsByCategory(?string $academicYear = null): array
    {
        $query = static::active()->orderBy('sort_order')->orderBy('item_name');
        
        if ($academicYear) {
            $query->byAcademicYear($academicYear);
        }

        return $query->get()->groupBy('category')->toArray();
    }

    /**
     * Get current academic year
     */
    public static function getCurrentAcademicYear(): string
    {
        $currentYear = date('Y');
        $currentMonth = date('n');
        
        // Academic year starts in July (month 7)
        if ($currentMonth >= 7) {
            return $currentYear . '/' . ($currentYear + 1);
        } else {
            return ($currentYear - 1) . '/' . $currentYear;
        }
    }

    /**
     * Get total mandatory costs
     */
    public static function getTotalMandatoryCosts(?string $academicYear = null): float
    {
        $query = static::active()->mandatory();
        
        if ($academicYear) {
            $query->byAcademicYear($academicYear);
        }

        return $query->sum('amount');
    }
}
