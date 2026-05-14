<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        $adminRole = Role::where('nombre', 'administrador')->first();

        User::create([
            'name' => 'Santiago',
            'apellido' => 'Sánchez',
            'email' => 'santiagoasanchez@ucundinamarca.edu.co',
            'password' => bcrypt('12345678'),
            'role_id' => $adminRole->id,
        ]);
    }
}