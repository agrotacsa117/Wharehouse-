<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->json('permissions')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // Insertar roles iniciales usando DB para evitar problemas con el modelo
        DB::table('roles')->insert([
            [
                'name' => 'admin',
                'description' => 'Administrador del sistema con acceso completo',
                'permissions' => json_encode(['*']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'tapachula',
                'description' => 'Usuario de la sucursal Tapachula',
                'permissions' => json_encode(['ver_salidas', 'ver_productos']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'bodega_dorado',
                'description' => 'Usuario de la bodega Dorado',
                'permissions' => json_encode(['ver_salidas', 'ver_productos']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('roles');
    }
};
