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
        Schema::create('treatments', function (Blueprint $table) {
            $table->id();
            // Conecta el tratamiento con un diagnóstico específico.
            $table->foreignId('diagnosis_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->text('description'); 
            $table->date('start_date'); 
            $table->date('end_date')->nullable(); // Fecha de fin (opcional)
            $table->enum('status', ['Prescrito', 'Activo', 'Completado', 'Cancelado'])->default('Prescrito');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatments');
    }
};
