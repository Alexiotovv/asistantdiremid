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
        Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->timestamp('email_verified_at')->nullable();
        $table->string('password');
        $table->string('role', 20)->default('personal'); // ← NUEVO
        $table->string('dni', 12)->nullable()->unique();   // ← NUEVO
        $table->string('nombre_completo', 100)->nullable(); // ← NUEVO
        $table->string('oficina', 100)->nullable();        // ← NUEVO
        $table->char('clave_pin', 4)->nullable();          // ← NUEVO
        $table->rememberToken();
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        
    }
};
