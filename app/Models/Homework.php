<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Homework extends Model
{
    use \App\Traits\SchoolScoped;
    protected $fillable = ['school_id', 'subject_id', 'section_id', 'title', 'description', 'due_date'];

    public function subject() { return $this->belongsTo(Subject::class); }
    public function section() { return $this->belongsTo(Section::class); }
    public function school() { return $this->belongsTo(School::class); }
}
