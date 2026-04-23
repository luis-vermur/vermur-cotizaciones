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
    Schema::create('tarifas_solicitud', function (Blueprint $table) {
        $table->id();
        $table->foreignId('solicitud_id')->constrained('solicitudes')->cascadeOnDelete()->unique();
        $table->json('datos_json')->nullable(); // Nativo JSON — Eloquent lo castea automáticamente
        $table->foreignId('actualizado_por')->nullable()->constrained('users')->nullOnDelete();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tarifas_solicitud');
    }
};
