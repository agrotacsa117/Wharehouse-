<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockInTransitModel extends Model
{
    protected $table = "stock_in_transit";

    protected $fillable = [
        'inventory_id',
        'origin_warehouse_id',
        'destination_warehouse_id',
        'quantity',
        'status',
        'folio',
        'sent_at',
        'received_at',
        'received_by',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'received_at' => 'datetime',
    ];

    public function inventory()
    {
        return $this->belongsTo(
            WarehouseInventoryModel::class,
            'inventory_id',
            'id'
        );
    }

    public function originWarehouse()
    {
        return $this->belongsTo(
            WarehouseModel::class,
            'origin_warehouse_id',
            'id'
        );
    }

    public function destinationWarehouse()
    {
        return $this->belongsTo(
            WarehouseModel::class,
            'destination_warehouse_id',
            'id'
        );
    }

    public function receivedByUser()
    {
        return $this->belongsTo(
            User::class,
            'received_by',
            'id'
        );
    }

    public function isPending(): bool
    {
        return $this->status === 'PENDING_RECEPTION';
    }

    public function isReceived(): bool
    {
        return $this->status === 'RECEIVED';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'CANCELLED';
    }
}
