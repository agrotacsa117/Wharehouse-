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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('categoria_id')->constrained('categorias');
            $table->foreignId('proveedor_id')->constrained('proveedores');
            $table->string('colocado', 10);
            $table->integer('cantidad')->default(0);
            $table->float('precio')->default(0);
            $table->float('precio_total')->default(0);
            $table->date('fecha_ingreso');
            $table->date('fecha_caducidad');
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto');
    }
};
