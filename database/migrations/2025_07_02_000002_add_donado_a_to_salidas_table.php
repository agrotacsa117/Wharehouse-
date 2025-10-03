<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('salidas', function (Blueprint $table) {
            $table->string('donado_a')->nullable()->after('bodega_destino');
        });
    }

    public function down()
    {
        Schema::table('salidas', function (Blueprint $table) {
            $table->dropColumn('donado_a');
        });
    }
};
