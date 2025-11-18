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
        Schema::table('zonas', function (Blueprint $table) {
            // Añadimos el campo color, que aceptará valores como #000000 o #12a89c
            // Lo hacemos nullable para que los registros existentes no se rompan
            $table->string('color', 7)->nullable()->after('precio_m2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('zonas', function (Blueprint $table) {
            $table->dropColumn('color');
        });
    }
};