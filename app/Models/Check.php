<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Check extends Model
{
    public function employees()
    {
        return $this->belongsTo(Employee::class, 'emp_id');
    }
    public function attendance()
    {
        return $this->belongsTo(Attendance::class, 'attendance_id');
    }
}
