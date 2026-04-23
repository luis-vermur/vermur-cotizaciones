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
    Schema::create('pallets', function (Blueprint $table) {
        $table->id();
        $table->foreignId('solicitud_id')->constrained('solicitudes')->cascadeOnDelete();
        $table->integer('numero');
        $table->float('largo_cm')->nullable();
        $table->float('ancho_cm')->nullable();
        $table->float('alto_cm')->nullable();
        $table->float('peso')->nullable();
        $table->string('peso_unidad')->nullable();
        $table->float('cubicaje_m3')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pallets');
    }
};
