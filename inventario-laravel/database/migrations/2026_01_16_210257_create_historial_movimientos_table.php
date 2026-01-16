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
        Schema::create('historial_movimientos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_equipo')->nullable(); // Can be null if action is not equipment specific? Ideally always equipment related for this legacy system port.
            $table->unsignedBigInteger('id_user')->nullable(); // Who performed the action
            $table->string('accion', 50); // ASIGNACION, DEVOLUCION, REPARACION, BAJA, EDICION
            $table->text('detalles')->nullable(); // JSON or text summary of what changed
            $table->timestamps();

            $table->foreign('id_equipo')->references('id')->on('equipos')->nullOnDelete();
            $table->foreign('id_user')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_movimientos');
    }
};
