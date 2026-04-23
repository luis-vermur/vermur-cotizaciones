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
    Schema::create('cotizaciones', function (Blueprint $table) {
        $table->id();
        $table->foreignId('solicitud_id')->constrained('solicitudes')->cascadeOnDelete();
        $table->foreignId('creado_por')->nullable()->constrained('users')->nullOnDelete();
        $table->string('folio_coti')->nullable();
        $table->string('tipo_plantilla'); // MXN | USD | LCL | terrestre
        $table->integer('version')->default(1);

        // Cabecera general
        $table->float('tc')->nullable();           // Tipo de cambio
        $table->float('margen_deseado')->nullable();
        $table->float('costo_ope')->default(1500); // Costo interno operación

        // Totales calculados
        $table->float('costo_total')->nullable();
        $table->float('profit_total')->nullable();
        $table->float('venta_total')->nullable();
        $table->float('margen_real')->nullable();
        $table->float('comision_pct')->default(0.10);
        $table->float('comision_monto')->nullable();
        $table->float('financiamiento_pct')->nullable();
        $table->float('financiamiento_monto')->nullable();
        $table->float('profit_real_pct')->nullable();
        $table->float('profit_real_monto')->nullable();
        $table->float('ganancia_real')->nullable();

        $table->text('notas')->nullable();
        $table->string('validez')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotizaciones');
    }
};
