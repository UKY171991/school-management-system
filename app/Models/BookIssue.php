<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookIssue extends Model
{
    protected $fillable = ['book_id', 'student_id', 'issue_date', 'due_date', 'return_date'];

    public function book() { return $this->belongsTo(Book::class); }
    public function student() { return $this->belongsTo(Student::class); }
}
