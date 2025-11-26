<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('licencias', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 10)->unique(); // LV, LE, LG, etc.
            $table->string('nombre', 100); // Nombre completo de la licencia
            $table->string('descripcion')->nullable(); // Descripción opcional
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('licencias');
    }
};