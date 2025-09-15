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
        Schema::create('amenidades_fraccionamiento', function (Blueprint $table) {
            $table->id("id_amenidad");
            $table->string("nombre",150);
            $table->string("descripcion",150)->nullable();
            $table->enum('tipo', ['Característica', 'Servicio'])->default('Característica');
            
            $table->unsignedBigInteger("id_fraccionamiento");
            $table->foreign("id_fraccionamiento")->references("id_fraccionamiento")->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('amenidades_fraccionamiento');
    }
};
