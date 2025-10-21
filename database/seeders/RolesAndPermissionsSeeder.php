<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Crear Permisos
        Permission::firstOrCreate(['name' => 'manage users']);
        Permission::firstOrCreate(['name' => 'manage residents']); // Un permiso general para CRUD
        Permission::firstOrCreate(['name' => 'manage diagnoses']);
        Permission::firstOrCreate(['name' => 'manage treatments']);
        Permission::firstOrCreate(['name' => 'manage medications']);
        Permission::firstOrCreate(['name' => 'manage appointments']);
        Permission::firstOrCreate(['name' => 'manage prescriptions']);
        Permission::firstOrCreate(['name' => 'manage backups']);

        // Crear Roles y asignar permisos
        $adminRole = Role::firstOrCreate(['name' => 'Administrador']);
        $adminRole->syncPermissions(Permission::all()); // El admin puede hacer todo

        $nurseRole = Role::firstOrCreate(['name' => 'Enfermero']);
        $nurseRole->syncPermissions([
            'manage residents',
            'manage diagnoses',
            'manage treatments',
            'manage medications',
            'manage appointments',
            'manage prescriptions',
        ]);

        $adminEmail = 'admin@example.com';

        User::where('email', $adminEmail)->delete();

        $user = User::create([
            'name' => 'Admin User',
            'email' => $adminEmail,
            'password' => Hash::make('261996'), 
        ]);

        $adminRole = Role::where('name', 'Administrador')->first();
        if ($adminRole) {
            $user->assignRole($adminRole);
        }
    }
}
