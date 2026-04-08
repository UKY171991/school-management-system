<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    protected $fillable = [
        'school_name',
        'school_address',
        'school_phone',
        'school_email',
        'logo',
        'favicon',
        'footer_text',
        'currency_symbol',
        'start_roll_number',
    ];

    protected $appends = ['logo_url', 'favicon_url'];

    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }

    public function getFaviconUrlAttribute()
    {
        return $this->favicon ? asset('storage/' . $this->favicon) : null;
    }
}
