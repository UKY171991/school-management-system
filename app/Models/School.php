<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $fillable = ['name', 'address', 'phone', 'email', 'logo', 'principal_signature', 'domain_name'];

    protected $appends = ['logo_url', 'signature_url'];

    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }

    public function getSignatureUrlAttribute()
    {
        return $this->principal_signature ? asset('storage/' . $this->principal_signature) : null;
    }

    public function admin()
    {
        return $this->hasOne(User::class, 'school_id')->whereHas('role', function($q) {
            $q->where('slug', 'admin');
        });
    }

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

    public function mainBranch()
    {
        return $this->hasOne(Branch::class)->where('is_main', true);
    }
}
