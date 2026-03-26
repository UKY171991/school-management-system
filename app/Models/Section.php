<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use \App\Traits\SchoolScoped;
    protected $fillable = ['school_id', 'branch_id', 'name', 'grade_id'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }
}
