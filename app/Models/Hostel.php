<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hostel extends Model
{
    use \App\Traits\SchoolScoped;
    protected $fillable = ['school_id', 'name', 'type', 'address', 'capacity'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function rooms()
    {
        return $this->hasMany(HostelRoom::class);
    }
}
