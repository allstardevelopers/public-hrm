<?php

namespace App\Models;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model

{

    protected $table = 'attendances';


    protected $casts = [
        // 'attendance_date' => 'datetime',
    ];
    
    
    public function employee()
    {
        return $this->belongsTo(Employee::class,'emp_id');
    }
    public function check()
    {
        return $this->hasOne(Check::class);
    }
    public function clockout()
    {
        return $this->hasMany(ClockOut::class);
    }
    public function schedule()
    {
        return $this->belongsTo(Employee::class,'emp_id');
    }
    public function latetime()
    {
        return $this->hasOne(Latetime::class);
    }
}
