<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
  use HasFactory;

class Project extends Model
{
  

    protected $fillable = ['customer_id','project_name','description','progress','status'];

    public function customer() { return $this->belongsTo(Customer::class); }
    public function invoices() { return $this->hasMany(Invoice::class); }
}

