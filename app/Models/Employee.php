<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends Model
{
    use HasFactory, Notifiable;

    public function getRouteKeyName()
    {
        return 'id';
    }
    protected $table = 'employees';
    protected $fillable = [
        'name', 'email', 'pin_code'
    ];


    protected $hidden = [
        'pin_code', 'remember_token',
    ];


    public function check()
    {
        return $this->hasMany(Check::class);
    }

    // public function attendance()
    // {
    //     return $this->hasMany(Attendance::class);
    // }

    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'emp_id');
    }

    public function clockout()
    {
        return $this->hasMany(ClockOut::class);
    }
    public function latetime()
    {
        return $this->hasMany(Latetime::class);
    }
    public function leave()
    {
        return $this->hasMany(Leave::class);
    }
    public function overtime()
    {
        return $this->hasMany(Overtime::class);
    }
    public function schedules()
    {
        return $this->belongsToMany('App\Models\Schedule', 'schedule_employees', 'emp_id', 'schedule_id');
    }
    public function roles()
    {
        return $this->belongsToMany('App\Models\Schedule', 'role_users', 'user_id', 'role_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function appResponses()
    {
        return $this->hasMany(AppResponse::class);
    }
}
