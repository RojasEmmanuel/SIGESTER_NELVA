<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            // Agregar la columna remember_token si no existe
            if (!Schema::hasColumn('usuarios', 'remember_token')) {
                $table->rememberToken();
            }

            // Asegurar que la columna password tenga longitud 255
            $table->string('password', 255)->change();
        });
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            // Revertir la adición de remember_token
            if (Schema::hasColumn('usuarios', 'remember_token')) {
                $table->dropColumn('remember_token');
            }

            // Revertir la longitud de password (ajusta según la longitud original)
            $table->string('password')->change(); // Opcional, ajusta si la longitud original era diferente
        });
    }
};