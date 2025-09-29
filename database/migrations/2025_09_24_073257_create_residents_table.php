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
        Schema::create('residents', function (Blueprint $table) {
            $table->id();
            $table->string('identity_card')->unique(); //cedula
            $table->string('first_name'); //primer_nombre
            $table->string('second_name')->nullable(); //segundo_nombre
            $table->string('last_name'); //primer_apellido
            $table->string('second_last_name')->nullable(); //segundo_apellido
            $table->enum('sex', ['Masculino', 'Femenino']); //sexo 'Masculino' o 'Femenino'
            $table->date('birth_date'); //fecha_nacimiento
            $table->date('admission_date'); //fecha_ingreso
            $table->enum('status', ['Activo', 'Inactivo', 'Trasladado', 'Fallecido'])->default('Activo'); //estado 'Activo', 'Inactivo', 'Trasladado', 'Fallecido'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('residents');
    }
};
