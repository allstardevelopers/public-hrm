<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Latetime extends Model
{
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_id');
    }
    public function attendance()
    {
        return $this->belongsTo(Attendance::class, 'attendance_id');
    }
}
