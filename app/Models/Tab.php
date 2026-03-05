<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tab extends Model
{
    use HasFactory;

    // Daftar kolom yang boleh di-mass-assign
    protected $fillable = [
        'title',
        'description',
        'button_text',
        'button_link',
        'image',
        'order',
    ];
}
