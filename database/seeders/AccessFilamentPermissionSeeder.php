<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AccessFilamentPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permission = Permission::firstOrCreate(['name' => 'access-filament']);

        $roles = ['Administrador', 'Enfermero', 'Visitante'];

        foreach ($roles as $roleName) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->givePermissionTo($permission);
        }
    }
}
