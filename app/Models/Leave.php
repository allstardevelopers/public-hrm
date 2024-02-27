<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leave extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'uid', // Add the 'uid' field to the fillable array
        'emp_id',
        'type',
        'leave_date',
        'leave_reason',
        // Other fillable fields
    ];
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_id');
    }
}
