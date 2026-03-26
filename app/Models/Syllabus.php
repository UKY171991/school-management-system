<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Syllabus extends Model
{
    use \App\Traits\SchoolScoped;
    protected $fillable = ['school_id', 'subject_id', 'section_id', 'title', 'file_path'];

    protected $appends = ['file_url'];

    public function getFileUrlAttribute()
    {
        return $this->file_path ? asset('storage/' . $this->file_path) : null;
    }

    public function subject() { return $this->belongsTo(Subject::class); }
    public function section() { return $this->belongsTo(Section::class); }
    public function school() { return $this->belongsTo(School::class); }
}
