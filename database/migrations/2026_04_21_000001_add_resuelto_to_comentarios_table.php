<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('comentarios', function (Blueprint $table) {
            $table->boolean('resuelto')->default(false)->after('rol');
            $table->timestamp('resuelto_en')->nullable()->after('resuelto');
        });
    }

    public function down(): void
    {
        Schema::table('comentarios', function (Blueprint $table) {
            $table->dropColumn(['resuelto', 'resuelto_en']);
        });
    }
};
