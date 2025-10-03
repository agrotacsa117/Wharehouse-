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
        Schema::create('rack', function (Blueprint $table) {
            $table->id();
            // Crear FK
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('rack_aduana', 255);
            $table->integer('cantidad_max');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rack');
    }
};
