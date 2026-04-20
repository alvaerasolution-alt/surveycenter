<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Article extends Model
{
    private static ?bool $hasIsPublishedColumn = null;

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
        if (!self::hasIsPublishedColumn()) {
            return $query;
        }

        return $query->where('is_published', true);
    }

    // optional: supaya URL otomatis pakai slug
    public function getRouteKeyName()
    {
        return 'slug';
    }

    private static function hasIsPublishedColumn(): bool
    {
        if (self::$hasIsPublishedColumn !== null) {
            return self::$hasIsPublishedColumn;
        }

        try {
            self::$hasIsPublishedColumn = Schema::hasColumn((new static())->getTable(), 'is_published');
        } catch (\Throwable $e) {
            self::$hasIsPublishedColumn = false;
        }

        return self::$hasIsPublishedColumn;
    }
}
