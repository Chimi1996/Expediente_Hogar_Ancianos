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
        Schema::create('diagnoses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id') //id del residente
              ->constrained()
              ->cascadeOnDelete(); // Si se borra un residente, se borran sus diagnósticos.
            $table->string('description'); //descripcion
            $table->date('diagnosis_date'); //fecha_diagnostico
            $table->enum('status', ['Activo', 'Resuelto', 'En seguimiento'])->default('Activo'); //estado
            $table->text('notes')->nullable(); //notas
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnoses');
    }
};

