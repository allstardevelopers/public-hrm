<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'manage_all']);
        Permission::create(['name' => 'manage_own_team']);
        Permission::create(['name' => 'manage_own']);
    }
}
