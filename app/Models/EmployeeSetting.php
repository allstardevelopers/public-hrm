<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSetting extends Model
{
    protected $table = 'employee_settings';
    protected $fillable = [
        'emp_id',
        'setting_name',
        'setting_value',
        'updated_by',
    ];

}
