<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

/**
 * Class TemplateGallery
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string|null $description
 * @property int|null $category_id
 * @property string|null $preview_image
 * @property array|null $preview_images
 * @property array|null $template_data
 * @property array|null $demo_content
 * @property string|null $author
 * @property string|null $version
 * @property array|null $features
 * @property array|null $color_schemes
 * @property int $downloads
 * @property float|null $rating
 * @property bool $featured
 * @property bool $premium
 * @property bool $active
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property-read TemplateCategory|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection|UserTemplate[] $userTemplates
 * @property-read string $preview_image_url
 * @property-read array $preview_images_urls
 */
class TemplateGallery extends Model
{
    use HasFactory;

    protected $table = 'template_gallery';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'category_id',
        'preview_image',
        'preview_images',
        'template_data',
        'demo_content',
        'author',
        'version',
        'features',
        'color_schemes',
        'downloads',
        'rating',
        'featured',
        'premium',
        'active',
    ];

    protected $casts = [
        'preview_images' => 'array',
        'template_data' => 'array',
        'demo_content' => 'array',
        'features' => 'array',
        'color_schemes' => 'array',
        'downloads' => 'integer',
        'rating' => 'decimal:1',
        'featured' => 'boolean',
        'premium' => 'boolean',
        'active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(TemplateCategory::class);
    }

    public function userTemplates()
    {
        return $this->hasMany(UserTemplate::class, 'gallery_template_id');
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeFree($query)
    {
        return $query->where('premium', false);
    }

    public function scopePremium($query)
    {
        return $query->where('premium', true);
    }

    public function scopeByCategory($query, $categorySlug)
    {
        return $query->whereHas('category', function ($q) use ($categorySlug) {
            $q->where('slug', $categorySlug);
        });
    }

    public function getPreviewImageUrlAttribute()
    {
        if (!$this->preview_image) {
            return asset('images/template-placeholder.jpg');
        }

        if (str_starts_with($this->preview_image, 'http')) {
            return $this->preview_image;
        }

        return Storage::url($this->preview_image);
    }

    public function getPreviewImagesUrlsAttribute()
    {
        if (!$this->preview_images) {
            return [$this->preview_image_url];
        }

        return collect($this->preview_images)->map(function ($image) {
            if (str_starts_with($image, 'http')) {
                return $image;
            }
            return Storage::url($image);
        })->toArray();
    }

    public function incrementDownloads()
    {
        $this->increment('downloads');
    }

    public function isInstalled($userId = null)
    {
        if (!$userId) {
            $userId = \Illuminate\Support\Facades\Auth::id();
        }

        if (!$userId) {
            return false;
        }

        return $this->userTemplates()
            ->where('user_id', $userId)
            ->exists();
    }

    public function createUserTemplate($userId, $customizations = [])
    {
        return UserTemplate::create([
            'user_id' => $userId,
            'gallery_template_id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug . '-' . time(),
            'description' => $this->description,
            'preview_image' => $this->preview_image,
            'template_data' => $this->template_data,
            'source' => 'gallery',
            'customizations' => $customizations,
        ]);
    }
}
