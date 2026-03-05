<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestimoniImage extends Model
{
    protected $fillable = ['image_path', 'caption', 'sort_order', 'is_active'];
}
