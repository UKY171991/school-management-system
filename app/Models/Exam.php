<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    use \App\Traits\SchoolScoped;
    protected $fillable = ['school_id', 'name', 'type', 'session', 'grade_id', 'start_date', 'end_date'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }
}
