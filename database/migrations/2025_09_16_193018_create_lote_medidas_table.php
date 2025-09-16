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
        Schema::create('lote_medidas', function (Blueprint $table) {
            $table->id('id_medidas');
            $table->int('manzana');
            $table->double('norte');
            $table->double('sur');
            $table->double('poniente');
            $table->double('oriente');
            $table->double('area_metros');

            $table->string('id_lote', 50);
            $table->foreign('id_lote')->references('id_lote')->on('lotes')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lote_medidas');
    }
};
