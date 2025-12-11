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
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            // Conecta con el Residente
            $table->foreignId('resident_id')
              ->constrained()
              ->cascadeOnDelete();

            // Conecta con el Medicamento del catálogo
            $table->foreignId('medication_id')
                ->constrained()
                ->cascadeOnDelete(); // Si se borra un medicamento del catálogo, se borran sus prescripciones

            $table->string('dose'); // Dosis específica (Ej: "1 tableta", "5 ml")
            $table->string('frequency'); // Frecuencia (Ej: "Cada 8 horas", "Una vez al día con desayuno")
            $table->date('start_date'); // Fecha de inicio
            $table->date('end_date')->nullable(); // Fecha de fin (opcional)
            $table->enum('status', ['Activa', 'Completada', 'Suspendida'])->default('Activa'); // Estado
            $table->text('notes')->nullable(); // Indicaciones especiales
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prescriptions');
    }
};

