<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Hash;
use Spatie\Permission\Traits\HasRoles;
use DB;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user= User::create([
            'name' => 'Hrm',
            'email' => 'hr@allstar-technologies.com',
            'password' => Hash::make('hr@ems.com'),
        ]);
        $user= User::create([
            'name' => 'Admin',
            'email' => 'admin@allstar-technologies.com',
            'password' => Hash::make('admin@ems.com'),
        ]);
        $user= User::create([
            'name' => 'Raja Usama',
            'email' => 'rajausama1991@gmail.com',
            'password' => Hash::make('Ast#@2024'),
        ]);
        $role = Role::first();
        $user->roles()->sync($role->id);
    }
}
