<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransportRoute extends Model
{
    use \App\Traits\SchoolScoped;
    protected $fillable = ['school_id', 'route_name', 'vehicle_id', 'driver_id'];

    public function vehicle() { return $this->belongsTo(Vehicle::class); }
    public function driver() { return $this->belongsTo(Driver::class); }
    public function school() { return $this->belongsTo(School::class); }
}
