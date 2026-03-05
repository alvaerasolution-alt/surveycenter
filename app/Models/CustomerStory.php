<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerStory extends Model
{
    protected $fillable = [
        'title', 'highlight', 'highlight_color', 'subheading', 'description',
        'image', 'button_text', 'button_link'
    ];
}

