<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'blog_category_id', 'author_id', 'title', 'slug', 'excerpt', 'content',
        'featured_image', 'gallery', 'meta_title', 'meta_description', 'meta_keywords',
        'og_image', 'status', 'is_featured', 'allow_comments', 'views_count',
        'reading_time', 'custom_fields', 'published_at', 'scheduled_at'
    ];

    protected function casts(): array
    {
        return [
            'gallery' => 'array',
            'meta_keywords' => 'array',
            'custom_fields' => 'array',
            'is_featured' => 'boolean',
            'allow_comments' => 'boolean',
            'published_at' => 'datetime',
            'scheduled_at' => 'datetime',
        ];
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags()
    {
        return $this->belongsToMany(BlogTag::class, 'blog_tag_pivot');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where(function($q) {
                        $q->whereNull('published_at')
                          ->orWhere('published_at', '<=', now());
                    });
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getFeaturedImageUrlAttribute()
    {
        return $this->featured_image ? asset('storage/blog/' . $this->featured_image) : null;
    }

    public function getReadingTimeTextAttribute()
    {
        return $this->reading_time ? $this->reading_time . ' min read' : null;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($blog) {
            if (!$blog->slug) {
                $blog->slug = Str::slug($blog->title);
            }

            // Calculate reading time
            if ($blog->content && !$blog->reading_time) {
                $wordCount = str_word_count(strip_tags($blog->content));
                $blog->reading_time = max(1, ceil($wordCount / 200)); // 200 words per minute
            }
        });

        static::updating(function ($blog) {
            if ($blog->isDirty('content')) {
                $wordCount = str_word_count(strip_tags($blog->content));
                $blog->reading_time = max(1, ceil($wordCount / 200));
            }
        });
    }
}
