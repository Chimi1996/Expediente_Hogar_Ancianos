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
        Schema::create('medications', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Nombre del medicamento 
            $table->string('presentation')->nullable(); // Presentacion Ejemplo: Tableta, Jarabe, Inyección
            $table->string('standard_dose')->nullable(); // Dosis estándar Ejemplo: 500mg, 10ml
            $table->text('description')->nullable(); // Notas generales sobre el medicamento
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medications');
    }
};

