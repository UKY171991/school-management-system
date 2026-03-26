<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use \App\Traits\SchoolScoped;
    protected $fillable = ['school_id', 'vehicle_number', 'capacity'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
