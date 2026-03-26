<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeePayment extends Model
{
    use \App\Traits\SchoolScoped;
    protected $fillable = ['student_id', 'fee_structure_id', 'amount_paid', 'payment_date', 'status'];

    public function student() { return $this->belongsTo(Student::class); }
    public function feeStructure() { return $this->belongsTo(FeeStructure::class); }
}
