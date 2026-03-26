<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentTransfer extends Model
{
    use \App\Traits\SchoolScoped;
    protected $fillable = ['school_id', 'student_id', 'transfer_date', 'reason', 'to_school', 'lc_number'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
