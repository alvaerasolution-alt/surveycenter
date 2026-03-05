<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentProof extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'name',
        'phone',
        'file_path',
        'note',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
