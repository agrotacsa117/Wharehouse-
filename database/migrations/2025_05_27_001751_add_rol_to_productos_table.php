<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
        // Verificar si la columna 'rol' ya existe
        if (!Schema::hasColumn('productos', 'rol')) {
            Schema::table('productos', function (Blueprint $table) {
                $table->string('rol')->nullable()->after('user_id');
            });
        }

        // Actualizar los productos existentes con un valor predeterminado si no lo tienen
        DB::table('productos')->whereNull('rol')->update(['rol' => 'tapachula']);

        // Hacer que el campo sea obligatorio después de la migración
        if (Schema::hasColumn('productos', 'rol')) {
            Schema::table('productos', function (Blueprint $table) {
                $table->string('rol')->nullable(false)->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn('rol');
        });
    }
};
