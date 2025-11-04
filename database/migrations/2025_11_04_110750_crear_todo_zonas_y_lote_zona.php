<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. ZONAS
        Schema::create('zonas', function (Blueprint $table) {
            $table->bigIncrements('id_zona');                    // PK correcta
            $table->string('nombre', 100);
            $table->double('precio_m2', 10, 2)->default(0);
            $table->unsignedBigInteger('id_fraccionamiento')->nullable();
            $table->foreign('id_fraccionamiento')
                  ->references('id_fraccionamiento')
                  ->on('fraccionamientos')
                  ->onDelete('cascade');
            $table->timestamps();
        });

        // 2. LOTE_ZONA (¡FIJATE EN constrained('zonas', 'id_zona')!)
        Schema::create('lote_zona', function (Blueprint $table) {
            $table->id();
            $table->string('id_lote')->unique();
            $table->unsignedBigInteger('id_zona')->nullable();

            $table->foreign('id_lote')
                  ->references('id_lote')
                  ->on('lotes')
                  ->onDelete('cascade');

            // ¡¡AQUÍ ESTABA EL ERROR!!
            $table->foreign('id_zona')
                  ->references('id_zona')    // ← apuntamos a id_zona
                  ->on('zonas')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lote_zona');
        Schema::dropIfExists('zonas');
    }
};