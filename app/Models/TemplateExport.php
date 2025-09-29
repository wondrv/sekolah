<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

/**
 * Class TemplateExport
 *
 * @property int $id
 * @property int $user_id
 * @property int $user_template_id
 * @property string $filename
 * @property string $format
 * @property array|null $export_options
 * @property string $file_path
 * @property int|null $file_size
 * @property \Illuminate\Support\Carbon|null $expires_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property-read User $user
 * @property-read UserTemplate $userTemplate
 */
class TemplateExport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_template_id',
        'filename',
        'format',
        'export_options',
        'file_path',
        'file_size',
        'expires_at',
    ];

    protected $casts = [
        'export_options' => 'array',
        'expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userTemplate()
    {
        return $this->belongsTo(UserTemplate::class);
    }

    public function scopeActive($query)
    {
        return $query->whereNull('expires_at')
            ->orWhere('expires_at', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    public function getDownloadUrlAttribute()
    {
        if (!Storage::exists($this->file_path)) {
            return null;
        }

        return route('admin.templates.download-export', $this->id);
    }

    public function getFileSizeHumanAttribute()
    {
        if (!$this->file_size) {
            return 'Unknown';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    public function isExpired()
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function deleteFile()
    {
        if (Storage::exists($this->file_path)) {
            Storage::delete($this->file_path);
        }
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($export) {
            $export->deleteFile();
        });
    }
}
