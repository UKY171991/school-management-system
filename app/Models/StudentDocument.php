<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentDocument extends Model
{
    protected $fillable = ['student_id', 'name', 'path'];

    protected $appends = ['document_url'];

    public function getDocumentUrlAttribute()
    {
        return $this->path ? asset('storage/' . $this->path) : null;
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
