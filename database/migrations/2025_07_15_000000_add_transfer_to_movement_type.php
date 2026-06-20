<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE warehouse_inventory_movements 
            MODIFY COLUMN movement_type 
            ENUM('IN', 'OUT', 'ADJUSTMENT', 'TRANSFER') 
            NOT NULL");
    }

    public function down()
    {
        DB::statement("ALTER TABLE warehouse_inventory_movements 
            MODIFY COLUMN movement_type 
            ENUM('IN', 'OUT', 'ADJUSTMENT') 
            NOT NULL");
    }
};
