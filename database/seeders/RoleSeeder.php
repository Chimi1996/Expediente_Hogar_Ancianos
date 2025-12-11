use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role; 

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Crear o encontrar todos los roles utilizados en tu app
        Role::firstOrCreate(['name' => 'Administrador']);
        Role::firstOrCreate(['name' => 'Enfermero']); // Rol faltante
        Role::firstOrCreate(['name' => 'Visitante']); // Rol faltante
    }
}