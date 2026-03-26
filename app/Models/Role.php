<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use \App\Traits\SchoolScoped;
    protected $fillable = ['school_id', 'name', 'slug'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
