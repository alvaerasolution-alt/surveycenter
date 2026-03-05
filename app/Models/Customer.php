<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use HasFactory;


class Customer extends Model
{
    
    protected $fillable = ['full_name', 'email', 'phone', 'status', 'source', 'notes'];

    public function followUps()
    {
        return $this->hasMany(FollowUp::class);
    }
    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
