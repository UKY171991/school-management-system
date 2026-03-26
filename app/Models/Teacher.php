<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use \App\Traits\SchoolScoped;
    protected $fillable = ['school_id', 'branch_id', 'name', 'email', 'phone', 'specialization', 'photo', 'signature'];

    protected $appends = ['photo_url', 'signature_url'];

    public function getPhotoUrlAttribute()
    {
        return $this->photo ? asset('storage/' . $this->photo) : null;
    }

    public function getSignatureUrlAttribute()
    {
        return $this->signature ? asset('storage/' . $this->signature) : null;
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
