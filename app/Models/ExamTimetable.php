<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamTimetable extends Model
{
    protected $fillable = [
        'exam_id',
        'section_id',
        'subject_id',
        'exam_date',
        'start_time',
        'end_time',
        'room_number'
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
