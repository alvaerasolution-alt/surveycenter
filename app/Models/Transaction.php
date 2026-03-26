<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'survey_id',
        'user_id',
        'amount',
        'status',
        'singapay_ref',
        'payment_method',
        'progress',
        'bill_no',
        'payment_ref',
        'trx_id',
        'qr_data',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
