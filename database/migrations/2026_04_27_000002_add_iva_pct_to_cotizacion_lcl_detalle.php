<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cotizacion_lcl_detalle', function (Blueprint $table) {
            $table->float('iva_pct')->default(16)->after('recargo_imo');
        });
    }

    public function down(): void
    {
        Schema::table('cotizacion_lcl_detalle', function (Blueprint $table) {
            $table->dropColumn('iva_pct');
        });
    }
};
