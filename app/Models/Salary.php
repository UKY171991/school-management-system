<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    use \App\Traits\SchoolScoped;
    protected $fillable = ['school_id', 'teacher_id', 'amount', 'month', 'year', 'status'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
