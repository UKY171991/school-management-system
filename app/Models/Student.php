<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use \App\Traits\SchoolScoped;
    protected $fillable = ['school_id', 'branch_id', 'name', 'email', 'roll_number', 'dob', 'grade_id', 'section_id', 'photo', 'father_name', 'mother_name', 'gender', 'caste', 'father_phone', 'mother_phone', 'address', 'admission_date', 'previous_school', 'adhaar_number', 'apaar_id'];

    protected $appends = ['photo_url'];

    protected $casts = [
        'dob' => 'date',
        'admission_date' => 'date',
    ];

    public function getDobAttribute($value)
    {
        return $value ? \Carbon\Carbon::parse($value)->format('Y-m-d') : null;
    }

    public function getAdmissionDateAttribute($value)
    {
        return $value ? \Carbon\Carbon::parse($value)->format('Y-m-d') : null;
    }

    public function getPhotoUrlAttribute()
    {
        return $this->photo ? asset('storage/' . $this->photo) : null;
    }

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

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function documents()
    {
        return $this->hasMany(StudentDocument::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }
}
