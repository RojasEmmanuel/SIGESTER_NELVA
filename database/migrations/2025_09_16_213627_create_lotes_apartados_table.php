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
        Schema::create('lotes_apartados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("id_apartado");
            $table->foreign('id_apartado')->references('id_apartado')->on('apartados')->onDelete('cascade');

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
        Schema::dropIfExists('lotes_apartados');
    }
};
