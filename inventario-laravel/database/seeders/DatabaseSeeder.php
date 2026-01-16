<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Sucursal;
use App\Models\Area;
use App\Models\Cargo;
use App\Models\TipoEquipo;
use App\Models\Marca;
use App\Models\Modelo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Crear Sucursal Principal
        $sucursal = Sucursal::create([
            'nombre' => 'Sede Central',
            'direccion' => 'Av. Principal 123',
            'telefono' => '555-0199',
            'estado' => 'Activo'
        ]);

        // 2. Crear Roles
        $adminRole = Role::create(['nombre_rol' => 'Administrador']);
        $userRole = Role::create(['nombre_rol' => 'Usuario']);

        // 3. Crear Usuario Administrador
        $user = User::create([
            'name' => 'Administrador',
            'email' => 'admin@inventario.com',
            'password' => Hash::make('password'), // Default password
            'id_sucursal' => $sucursal->id,
            'activo' => true,
        ]);

        // Asignar rol (Pivot manual si no hay relación Eloquent definida aun, o usar attach si existe)
        // Como definimos la tabla 'role_user' pero no necesariamente la relación en el modelo User todavía:
        DB::table('role_user')->insert([
            'user_id' => $user->id,
            'role_id' => $adminRole->id
        ]);

        // 4. Datos de Ejemplo para Catálogos

        // Areas
        $areaTi = Area::create(['nombre' => 'Tecnología']);
        Area::create(['nombre' => 'Recursos Humanos']);

        // Cargos
        Cargo::create(['nombre' => 'Jefe de TI', 'id_area' => $areaTi->id]);
        Cargo::create(['nombre' => 'Soporte Técnico', 'id_area' => $areaTi->id]);

        // Tipos de Equipo
        $tipoLaptop = TipoEquipo::create(['nombre' => 'Laptop']);
        TipoEquipo::create(['nombre' => 'Monitor']);
        TipoEquipo::create(['nombre' => 'Teclado']);

        // Marcas y Modelos
        $marcaDell = Marca::create(['nombre' => 'Dell']);
        $marcaHp = Marca::create(['nombre' => 'HP']);

        Modelo::create(['nombre' => 'Latitude 5420', 'id_marca' => $marcaDell->id]);
        Modelo::create(['nombre' => 'ProBook 450', 'id_marca' => $marcaHp->id]);

        $this->command->info('Base de datos inicializada con:');
        $this->command->info('- Usuario: admin@inventario.com');
        $this->command->info('- Password: password');
    }
}
