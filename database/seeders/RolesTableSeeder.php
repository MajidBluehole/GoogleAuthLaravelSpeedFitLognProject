<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        // Create roles
        $adminRole = Role::create(['name' => 'admin']);
        $customerRole = Role::create(['name' => 'customer']);

        // Assign roles to users
        $admin = User::where('email', 'admin@example.com')->first();
        $admin->assignRole($adminRole);

        $customer = User::where('email', 'customer@example.com')->first();
        $customer->assignRole($customerRole);
    }
}
