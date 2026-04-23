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
    Schema::create('solicitudes', function (Blueprint $table) {
        $table->id();
        $table->string('folio')->unique();

        // Cliente
        $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();
        $table->string('cliente_nombre');
        $table->integer('dias_credito')->default(0);

        // Usuarios relacionados
        $table->foreignId('creado_por')->nullable()->constrained('users')->nullOnDelete();
        $table->foreignId('asignado_a')->nullable()->constrained('users')->nullOnDelete();

        // Info general
        $table->string('tipo_operacion');
        $table->string('tipo_transporte');
        $table->string('tipo_mercancia');
        $table->string('incoterm')->nullable();
        $table->string('pol_aol')->nullable();
        $table->string('pod_asd')->nullable();

        // Servicios adicionales
        $table->boolean('recoleccion')->default(false);
        $table->text('dir_recoleccion')->nullable();
        $table->boolean('entrega')->default(false);
        $table->text('dir_entrega')->nullable();
        $table->boolean('seguro_mercancia')->default(false);
        $table->boolean('financiamiento')->default(false);
        $table->integer('dias_financiamiento')->nullable();
        $table->boolean('requiere_despacho')->default(false);
        $table->boolean('target')->default(false);
        $table->boolean('embalaje')->default(false);

        // Condiciones comerciales
        $table->integer('volumen_operacion')->default(1);
        $table->float('valor_factura')->nullable();
        $table->float('margen_profit')->nullable();

        // Embarque general
        $table->string('tipo_embarque')->default('ninguno'); // FCL | LCL | ninguno

        // FCL
        $table->string('fcl_contenedor')->nullable();
        $table->float('fcl_peso')->nullable();
        $table->string('fcl_peso_unidad')->nullable();
        $table->text('fcl_reqs')->nullable();
        $table->boolean('fcl_food_grade')->default(false);
        $table->boolean('fcl_reforzado')->default(false);
        $table->boolean('fcl_sobredimension')->default(false);
        $table->boolean('fcl_enlonado')->default(false);
        $table->boolean('fcl_atmos_controlada')->default(false);

        // LCL
        $table->integer('lcl_num_pallets')->nullable();
        $table->boolean('lcl_estibable')->default(false);
        $table->float('lcl_cubicaje_total')->nullable();

        // Metadatos
        $table->text('nota_interna')->nullable();
        $table->string('estado')->default('nueva'); // nueva | en_revision | cotizada | enviada | rechazada

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes');
    }
};
