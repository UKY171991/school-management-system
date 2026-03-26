<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    use \App\Traits\SchoolScoped;
    protected $fillable = ['school_id', 'student_id', 'exam_type_id', 'subject_id', 'marks_obtained', 'max_marks', 'remarks'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function student() { return $this->belongsTo(Student::class); }
    public function examType() { return $this->belongsTo(ExamType::class, 'exam_type_id'); }
    public function subject() { return $this->belongsTo(Subject::class); }

}
