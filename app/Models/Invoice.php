<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use HasFactory;

class Invoice extends Model
{

    protected $fillable = ['customer_id', 'project_id', 'invoice_number', 'amount', 'status', 'due_date'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
