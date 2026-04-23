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
    Schema::create('cotizacion_lcl_detalle', function (Blueprint $table) {
        $table->id();
        $table->foreignId('cotizacion_id')->constrained('cotizaciones')->cascadeOnDelete()->unique();

        // Campos exclusivos plantilla LCL
        $table->string('pol')->nullable();
        $table->string('pod')->nullable();
        $table->string('incoterm')->nullable();
        $table->integer('piezas')->nullable();
        $table->float('peso_tons')->nullable();
        $table->float('medidas_cbm')->nullable();
        $table->float('pickup')->nullable();
        $table->float('despacho_mxn')->nullable();
        $table->float('maniobras_mxn')->nullable();
        $table->float('desconsolidacion')->nullable();
        $table->float('transfer_fee')->nullable();
        $table->float('revalidacion')->nullable();
        $table->float('transmision')->nullable();
        $table->float('admon_fee')->nullable();
        $table->float('recargo_imo')->nullable();
        $table->float('total_local')->nullable();
        $table->float('iva')->nullable();
        $table->float('total_iva')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cotizacion_lcl_detalle');
    }
};
