<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User; // Tu modelo de usuario
use Spatie\Permission\Models\Role; // Importar el modelo de Role de Spatie

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Asegurar que el rol 'Administrador' exista o crearlo (Spatie Permissions)
        $adminRole = Role::firstOrCreate(['name' => 'Administrador']);

        // 2. Crear o encontrar el usuario administrador
        $user = User::firstOrCreate(
            [
                'email' => 'admin@expediente.com' // <<-- ¡CAMBIAR ESTE EMAIL!
            ],
            [
                'name' => 'Administrador Principal',
                // CAMBIAR 'password' por una contraseña segura y hasheada
                'password' => Hash::make('261996'), 
                'email_verified_at' => now(),
            ]
        );

        // 3. Asignar el rol 'Administrador' al usuario creado
        if (!$user->hasRole('Administrador')) {
            $user->assignRole($adminRole);
        }
        
        $this->command->info("Usuario Administrador Creado/Actualizado: {$user->email} con rol 'Administrador'");
    }
}