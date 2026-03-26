<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HostelRoom extends Model
{
    protected $fillable = ['hostel_id', 'room_number', 'capacity'];

    public function hostel()
    {
        return $this->belongsTo(Hostel::class);
    }
}
