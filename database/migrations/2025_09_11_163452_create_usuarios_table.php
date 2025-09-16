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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->string('nombre', 100);
            $table->string('telefono', 15)->nullable();
            $table->string('email', 150)->unique();
            $table->string('password');
            $table->string('usuario_nombre', 255);
            $table->boolean('estatus')->default(true);

            $table->foreignId('tipo_usuario')->constrained('tipos_usuarios', 'id_tipo')->onDelete('cascade');


            $table->unsignedBigInteger('tipo_usuario');
            $table->foreign('tipo_usuario')->references('id_tipo')->on('tipos_usuarios')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
