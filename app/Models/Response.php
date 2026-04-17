<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    use HasFactory;

    // Tambahkan google_form_link
    protected $fillable = [
        'survey_id',
        'user_id',
        'input_by_admin_id',
        'respond_count',
        'google_form_link',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function inputByAdmin()
    {
        return $this->belongsTo(User::class, 'input_by_admin_id');
    }
}
