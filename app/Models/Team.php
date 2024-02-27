<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_team')
            ->withPivot('is_manager')
            ->withTimestamps();
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'user_team', 'team_id', 'user_id')
            ->withPivot('is_manager')
            ->withTimestamps();
    }


    public function manager()
    {
        return $this->members()->wherePivot('is_manager', 1);
    }
    public function activeMembers()
    {
        return $this->members()->where('users.status', 1);
    }
}
