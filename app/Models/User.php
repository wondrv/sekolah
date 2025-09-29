<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $role
 * @property bool $is_admin
 * @property string|null $password
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|Post[] $posts
 * @property-read \Illuminate\Database\Eloquent\Collection|UserTemplate[] $userTemplates
 * @property-read \Illuminate\Database\Eloquent\Collection|TemplateExport[] $templateExports
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function isEditor(): bool
    {
        return $this->role === 'editor';
    }

    public function canManageSettings(): bool
    {
        return $this->isAdmin();
    }

    public function canManageContent(): bool
    {
        return $this->isAdmin() || $this->isEditor();
    }

    /**
     * Posts created by this user
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * User templates relationship
     */
    public function userTemplates()
    {
        return $this->hasMany(UserTemplate::class);
    }

    /**
     * Template exports relationship
     */
    public function templateExports()
    {
        return $this->hasMany(TemplateExport::class);
    }

    /**
     * Check if this user is the admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->is_admin;
    }

    /**
     * Boot method to ensure only one admin exists
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            // If creating an admin user, ensure no other admin exists
            if ($user->is_admin && $user->email === 'admin@sekolah.local') {
                static::where('email', 'admin@sekolah.local')->delete();
            }
        });
    }
}
