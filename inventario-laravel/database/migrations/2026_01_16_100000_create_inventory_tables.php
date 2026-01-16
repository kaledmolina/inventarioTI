<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Sucursales
        Schema::create('sucursales', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 150)->unique();
            $table->text('direccion')->nullable();
            $table->string('telefono', 50)->nullable();
            $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo');
            // No timestamps in legacy, but good to have. Using legacy schema strictness? 
            // Legacy didn't have created_at/updated_at. I'll add them to be modern.
            $table->timestamps();
        });

        // Add foreign key to users now that sucursales exists
        Schema::table('users', function (Blueprint $table) {
            $table->foreign('id_sucursal')->references('id')->on('sucursales')->nullOnDelete();
        });

        // 2. Areas
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo');
            $table->timestamps();
        });

        // 3. Cargos
        Schema::create('cargos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_area');
            $table->string('nombre', 100)->unique();
            $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo');
            $table->timestamps();

            $table->foreign('id_area')->references('id')->on('areas')->onDelete('cascade');
        });

        // 4. Marcas
        Schema::create('marcas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo');
            $table->timestamps();
        });

        // 5. Modelos
        Schema::create('modelos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_marca');
            $table->string('nombre', 100);
            $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo');
            $table->timestamps();

            $table->foreign('id_marca')->references('id')->on('marcas')->onDelete('cascade');
        });

        // 6. Tipos Equipo
        Schema::create('tipos_equipo', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo');
            $table->timestamps();
        });

        // 7. Roles
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_rol', 50)->unique();
            $table->timestamps();
        });

        // 8. Empleados
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_sucursal');
            $table->string('dni', 20)->unique();
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->unsignedBigInteger('id_cargo')->nullable();
            $table->unsignedBigInteger('id_area')->nullable();
            $table->enum('estado', ['Activo', 'Inactivo'])->default('Activo');
            $table->timestamps();

            $table->foreign('id_sucursal')->references('id')->on('sucursales');
            $table->foreign('id_cargo')->references('id')->on('cargos')->nullOnDelete();
            $table->foreign('id_area')->references('id')->on('areas')->nullOnDelete();
        });

        // 9. Equipos
        Schema::create('equipos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_sucursal');
            $table->string('codigo_inventario', 50)->unique();
            $table->unsignedBigInteger('id_tipo_equipo');
            $table->unsignedBigInteger('id_marca');
            $table->unsignedBigInteger('id_modelo');
            $table->string('numero_serie', 100)->unique();
            $table->text('caracteristicas')->nullable();
            $table->enum('tipo_adquisicion', ['Propio', 'Arrendado', 'Prestamo']);
            $table->date('fecha_adquisicion')->nullable();
            $table->string('proveedor', 150)->nullable();
            $table->enum('estado', ['Disponible', 'Asignado', 'En Reparacion', 'De Baja'])->default('Disponible');
            $table->text('observaciones')->nullable();
            // Legacy uses fecha_registro as timestamp default current.
            // Laravel created_at/updated_at covers this.
            $table->timestamps();

            $table->foreign('id_sucursal')->references('id')->on('sucursales');
            $table->foreign('id_tipo_equipo')->references('id')->on('tipos_equipo');
            $table->foreign('id_marca')->references('id')->on('marcas');
            $table->foreign('id_modelo')->references('id')->on('modelos');
        });

        // 10. Asignaciones
        Schema::create('asignaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_equipo');
            $table->unsignedBigInteger('id_empleado');
            $table->dateTime('fecha_entrega');
            $table->dateTime('fecha_devolucion')->nullable();
            $table->enum('estado_asignacion', ['Activa', 'Finalizada'])->default('Activa');
            $table->text('observaciones_entrega')->nullable();
            $table->text('observaciones_devolucion')->nullable();
            $table->string('acta_firmada_path')->nullable();
            $table->string('acta_devolucion_path')->nullable();
            $table->string('imagen_devolucion_1')->nullable();
            $table->string('imagen_devolucion_2')->nullable();
            $table->string('imagen_devolucion_3')->nullable();
            $table->timestamps();

            $table->foreign('id_equipo')->references('id')->on('equipos');
            $table->foreign('id_empleado')->references('id')->on('empleados');
        });

        // 11. Bajas
        Schema::create('bajas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_equipo');
            $table->date('fecha_baja');
            $table->string('motivo', 255);
            $table->text('observaciones')->nullable();
            $table->string('acta_baja_path')->nullable();
            $table->text('descripcion_motivo')->nullable();
            $table->unsignedBigInteger('id_usuario_responsable')->nullable(); // Just ID, maybe not FK to users if user deleted?
            $table->timestamps();

            $table->foreign('id_equipo')->references('id')->on('equipos');
        });

        // 12. Reparaciones
        Schema::create('reparaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_equipo');
            $table->date('fecha_ingreso');
            $table->date('fecha_salida')->nullable();
            $table->text('motivo');
            $table->string('proveedor_servicio')->nullable();
            $table->decimal('costo', 10, 2)->default(0);
            $table->text('observaciones_salida')->nullable();
            $table->enum('estado_reparacion', ['En Proceso', 'Finalizada'])->default('En Proceso');
            $table->timestamps();

            $table->foreign('id_equipo')->references('id')->on('equipos');
        });

        // 13. Configuracion
        Schema::create('configuracion', function (Blueprint $table) {
            $table->id();
            $table->string('clave', 100)->unique();
            $table->string('valor', 255);
            $table->timestamps();
        });

        // 14. Usuario Roles (Pivot) - Adapting to Laravel 'role_user' naming or legacy 'usuario_roles'
        // Let's use legacy name 'usuario_roles' to avoid confusion for now, or new. 
        // Plan says: "role_user (migrating usuario_roles)". Laravel Pivot convention is alphabetical: role_user.
        // I will use role_user for Laravel standard, but map fields.
        Schema::create('role_user', function (Blueprint $table) {
            $table->primary(['user_id', 'role_id']);
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('role_id');
            //$table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop in reverse order
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('configuracion');
        Schema::dropIfExists('reparaciones');
        Schema::dropIfExists('bajas');
        Schema::dropIfExists('asignaciones');
        Schema::dropIfExists('equipos');
        Schema::dropIfExists('empleados');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('tipos_equipo');
        Schema::dropIfExists('modelos');
        Schema::dropIfExists('marcas');
        Schema::dropIfExists('cargos');
        Schema::dropIfExists('areas');

        // Remove FK from users before dropping sucursales
        Schema::table('users', function (Blueprint $table) {
            // Assuming default index name users_id_sucursal_foreign
            $table->dropForeign(['id_sucursal']);
        });

        Schema::dropIfExists('sucursales');
    }
};
