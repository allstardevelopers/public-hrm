<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UpcomingEvent extends Model
{
    use HasFactory;
    protected $table = 'upcoming_events';
    public function upcomingEvents()
    {
        return $this->hasMany(UpcomingEvent::class);
    }
}
