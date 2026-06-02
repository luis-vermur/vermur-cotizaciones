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
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->string('ter_tipo')->nullable()->after('tipo_embarque');       // FTL | LTL
            $table->string('ter_unidad')->nullable()->after('ter_tipo');          // Caja 53', Torton, etc.
            $table->string('ter_mercancia')->nullable()->after('ter_unidad');
            $table->integer('ter_num_pallets')->nullable()->after('ter_mercancia');
            $table->decimal('ter_peso', 10, 2)->nullable()->after('ter_num_pallets');
            $table->string('ter_peso_unidad', 10)->nullable()->after('ter_peso'); // kg | ton | lb
            $table->string('ter_medidas')->nullable()->after('ter_peso_unidad');  // "1.20 x 1.0 x 1.15"
            $table->decimal('ter_volumen', 10, 4)->nullable()->after('ter_medidas'); // CBM
            $table->boolean('ter_estibable')->default(false)->after('ter_volumen');
        });
    }

    public function down(): void
    {
        Schema::table('solicitudes', function (Blueprint $table) {
            $table->dropColumn([
                'ter_tipo','ter_unidad','ter_mercancia','ter_num_pallets',
                'ter_peso','ter_peso_unidad','ter_medidas','ter_volumen','ter_estibable',
            ]);
        });
    }
};
