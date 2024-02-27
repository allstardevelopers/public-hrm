<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    // app/Http/Middleware/CheckPermissions.php

    // app/Http/Middleware/CheckPermissions.php

    public function handle($request, Closure $next, ...$permissions)
    {
        // Get the authenticated user's roles
        $userRoles = auth()->user()->roles;
        // dd($userRoles);
        // die;
        // Create an array to hold permissions from all roles
        $userPermissions = [];

        // Iterate through user's roles and collect permissions
        foreach ($userRoles as $role) {
            $rolePermissions = explode(',', $role->permissions);
            $userPermissions = array_merge($userPermissions, $rolePermissions);
        }


        // Remove duplicate permissions
        $userPermissions = array_unique($userPermissions);
        // dd($permissions);
        // die;

        // Check if the user has any of the required permissions
        foreach ($permissions as $permission) {
            if (!in_array($permission, $userPermissions)) {
                abort(403, 'Unauthorized');
            }
        }

        return $next($request);
    }
}
