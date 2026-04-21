<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'category',
        'image',
        'meta_title',
        'meta_description',
        'created_at',
        'updated_at',
    ];

    public function scopePublished($query)
    {
        return $query;
    }

    // optional: supaya URL otomatis pakai slug
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
