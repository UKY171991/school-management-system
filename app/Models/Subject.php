<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use \App\Traits\SchoolScoped;
    protected $fillable = ['school_id', 'grade_id', 'name', 'code'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }
}
