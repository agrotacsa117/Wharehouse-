<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Primero, asegurarse de que la tabla roles existe
        if (Schema::hasTable('roles')) {
            // Agregar la columna rol_id si no existe
            if (!Schema::hasColumn('users', 'rol_id')) {
                Schema::table('users', function (Blueprint $table) {
                    $table->unsignedBigInteger('rol_id')->nullable()->after('rol');
                });
            }

            // Crear la restricción de clave foránea
            Schema::table('users', function (Blueprint $table) {
                $table->foreign('rol_id')
                      ->references('id')
                      ->on('roles')
                      ->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminar la restricción de clave foránea
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['rol_id']);
        });

        // Eliminar la columna rol_id
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('rol_id');
        });
    }
};
