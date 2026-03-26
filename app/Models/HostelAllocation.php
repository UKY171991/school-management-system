<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HostelAllocation extends Model
{
    protected $fillable = ['student_id', 'hostel_room_id', 'allocation_date', 'status'];

    public function student() { return $this->belongsTo(Student::class); }
    public function room() { return $this->belongsTo(HostelRoom::class, 'hostel_room_id'); }
}
