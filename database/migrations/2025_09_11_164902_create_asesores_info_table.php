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
        Schema::create('asesores_info', function (Blueprint $table) {
            $table->id('id_asesor');
            $table->enum('zona', ['Costa', 'Istmo'])->default('Costa');
            $table->string('path_facebook', 400)->nullable();
            $table->string('path_fotografia', 400)->nullable();
            $table->foreignId('id_usuario')->constrained('usuarios', 'id_usuario')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asesores_info');
    }
};
