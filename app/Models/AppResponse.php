<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppResponse extends Model
{
    use HasFactory;
    protected $table = 'app_response';
    protected $fillable = ['emp_id', 'host_name', 'response_update'];
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
