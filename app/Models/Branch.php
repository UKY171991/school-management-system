<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use \App\Traits\SchoolScoped;
    
    protected $fillable = [
        'school_id',
        'name',
        'code',
        'address',
        'phone',
        'email',
        'is_main',
        'is_active'
    ];

    protected $casts = [
        'is_main' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
