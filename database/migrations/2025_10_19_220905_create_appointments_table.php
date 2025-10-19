<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')
              ->constrained()
              ->cascadeOnDelete();
            $table->string('doctor_name'); // Nombre del médico
            $table->string('specialty')->nullable(); // Especialidad (opcional)
            $table->dateTime('appointment_datetime'); // Fecha y Hora de la cita
            $table->string('location'); // Lugar
            $table->enum('status', ['Pendiente', 'Completada', 'Cancelada', 'Reprogramada'])->default('Pendiente'); // Estado
            $table->text('notes')->nullable(); //notas
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
