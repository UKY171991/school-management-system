<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use \App\Traits\SchoolScoped;
    protected $fillable = ['school_id', 'branch_id', 'name', 'section', 'teacher_id'];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
