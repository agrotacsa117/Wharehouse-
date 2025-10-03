<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rack', function (Blueprint $table) {
            $table->string('bodega', 100)->after('cantidad_max');
            $table->foreignId('user_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('rack', function (Blueprint $table) {
            $table->dropColumn('bodega');
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};
