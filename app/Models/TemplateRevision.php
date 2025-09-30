<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class TemplateRevision
 *
 * Represents a snapshot (revision) of a user template state at a point in time.
 * The JSON snapshot stores key template fields allowing restore operations.
 *
 * @property int $id
 * @property int $user_template_id
 * @property string $type          Revision action type (e.g., activate, publish_draft, pre_restore)
 * @property array|null $snapshot  JSON snapshot of template fields
 * @property string|null $note     Optional human readable note
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property-read UserTemplate $userTemplate
 */
class TemplateRevision extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_template_id',
        'type',
        'snapshot',
        'note',
    ];

    protected $casts = [
        'snapshot' => 'array',
    ];

    public function userTemplate()
    {
        return $this->belongsTo(UserTemplate::class);
    }
}
