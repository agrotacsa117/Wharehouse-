<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('warehouse_inventory_movements', function (Blueprint $table) {
            $table->unsignedBigInteger('client_id')->nullable()->after('user_id');
            $table->string('invoice_sap', 50)->nullable()->after('client_id');
            $table->date('operation_date')->nullable()->after('invoice_sap');
            $table->unsignedBigInteger('source_warehouse_id')->nullable()->after('operation_date');
        });

        DB::statement("ALTER TABLE warehouse_inventory_movements 
            MODIFY COLUMN movement_type 
            ENUM('IN', 'OUT', 'ADJUSTMENT', 'TRANSFER', 'SALE', 'RELOCATION') 
            NOT NULL");
    }

    public function down(): void
    {
        Schema::table('warehouse_inventory_movements', function (Blueprint $table) {
            $table->dropColumn(['client_id', 'invoice_sap', 'operation_date', 'source_warehouse_id']);
        });

        DB::statement("ALTER TABLE warehouse_inventory_movements 
            MODIFY COLUMN movement_type 
            ENUM('IN', 'OUT', 'ADJUSTMENT', 'TRANSFER') 
            NOT NULL");
    }
};
