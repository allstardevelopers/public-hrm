<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;


    public function getRouteKeyName()
    {
        return 'id';
    }

    public function roles()
    {
        return $this->belongsToMany('App\Models\Role', 'role_users', 'user_id', 'role_id');
    }
    public function hasAnyRole($roles)
    {
        if (Is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }
        } else {
            if ($this->hasRole($roles)) {
                return true;
            }
        }

        return false;
    }

    public function hasPermission($permission)
    {
        return  $this->roles->pluck('permissions')->map(function ($permissions) use ($permission) {
            return explode(',', $permissions);
        })->flatten()->contains($permission);
    }

    public static function hasRole($role)
    {
        if (auth()->user()->roles()->first()->slug === $role) {
            return true;
        }
        return false;
    }
    public function employee()
    {
        return $this->hasOne(Employee::class);
    }
    // public function teams()
    // {
    //     return $this->belongsToMany(Team::class, 'user_team')
    //         ->withPivot('is_manager')
    //         ->withTimestamps();
    // }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'user_team', 'user_id', 'team_id')
            ->withPivot('is_manager')
            ->withTimestamps();
    }

    public function getUserRoles($userId)
    {
        $user = User::findOrFail($userId);
        $roles = $user->roles; // This will retrieve the roles for the user

        return $roles;
    }


    public function getSecondUserRole($userId)
    {
        $user = User::findOrFail($userId);

        $secondRole = $user->roles()
            ->skip(1) // Skip the first role (zero-based index)
            ->first(); // Retrieve the second role

        return $secondRole;
    }


    protected $fillable = [
        'name', 'email', 'password', 'pin_code',
    ];


    protected $hidden = [
        'pin_code', 'password', 'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
