<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaffLeave extends Model
{
    use \App\Traits\SchoolScoped;
    protected $fillable = ['teacher_id', 'leave_type', 'start_date', 'end_date', 'reason', 'status'];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
