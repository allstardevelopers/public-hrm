<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClockOut extends Model
{
    use HasFactory;
    protected $table = 'clockouts';
    public function employee()
    {
        return $this->belongsTo(Employee::class,'emp_id');
    }
    public function attendance()
    {
        return $this->belongsTo(Attendance::class,'attendance_id');
    }
}
