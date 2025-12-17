<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create([
            'name' => 'Administrador',
            'slug' => 'admin',
            'description' => 'Acceso completo al sistema',
        ]);

        Role::create([
            'name' => 'Cliente',
            'slug' => 'customer',
            'description' => 'Usuario estándar del ecommerce',
        ]);

        // Usuario admin
        User::create([
            'name' => 'Admin Vitta',
            'email' => 'admin@vittaperfumes.com',
            'password' => bcrypt('admin123'),
            'role_id' => 1,
            'phone' => '+54 9 351 123 4567',
            'email_verified_at' => now(),
        ]);
    }
}