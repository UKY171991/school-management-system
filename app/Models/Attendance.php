<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use \App\Traits\SchoolScoped;
    protected $fillable = ['school_id', 'student_id', 'date', 'status', 'remarks'];

    public function school() { return $this->belongsTo(School::class); }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
