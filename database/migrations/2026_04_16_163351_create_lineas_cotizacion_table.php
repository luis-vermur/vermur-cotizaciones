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
    Schema::create('lineas_cotizacion', function (Blueprint $table) {
        $table->id();
        $table->foreignId('cotizacion_id')->constrained('cotizaciones')->cascadeOnDelete();
        $table->foreignId('proveedor_id')->nullable()->constrained('proveedores')->nullOnDelete();
        $table->string('proveedor_nombre'); // snapshot del nombre al momento de cotizar
        $table->string('concepto');
        $table->float('costo')->default(0);
        $table->float('profit')->default(0);
        $table->float('venta')->nullable();     // calculado: costo + profit
        $table->float('margen')->nullable();    // calculado: profit / venta
        $table->float('target')->nullable();
        $table->integer('orden')->default(0);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lineas_cotizacion');
    }
};
