<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'is_published',
        'published_at',
        'excerpt',
        'content',
        'category',
        'image',
        'meta_title',
        'meta_description',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    // optional: supaya URL otomatis pakai slug
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
