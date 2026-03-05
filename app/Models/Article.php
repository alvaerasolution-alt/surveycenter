<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $fillable = [
        'title', 'slug', 'excerpt', 'content', 'category', 'image', 'created_at', 'updated_at'
    ];

    // optional: supaya URL otomatis pakai slug
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
