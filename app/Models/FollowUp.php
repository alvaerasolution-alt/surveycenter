<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use HasFactory;

class FollowUp extends Model
{

    protected $fillable = ['customer_id','follow_up_date','status','note'];

    public function customer() { return $this->belongsTo(Customer::class); }
}

