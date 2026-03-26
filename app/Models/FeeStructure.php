<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeeStructure extends Model
{
    use \App\Traits\SchoolScoped;
    protected $fillable = ['school_id', 'name', 'amount', 'description'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
