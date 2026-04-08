<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_in_transit', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_id');
            $table->unsignedBigInteger('origin_warehouse_id');
            $table->unsignedBigInteger('destination_warehouse_id');
            $table->integer('quantity');
            $table->enum('status', ['PENDING_RECEPTION', 'RECEIVED', 'CANCELLED'])->default('PENDING_RECEPTION');
            $table->string('folio', 20);
            $table->timestamp('sent_at')->useCurrent();
            $table->timestamp('received_at')->nullable();
            $table->unsignedBigInteger('received_by')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_in_transit');
    }
};
