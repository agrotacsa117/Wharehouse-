<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseInventoryMovementsModel extends Model
{
    protected $table = "warehouse_inventory_movements";

    protected $fillable = [
       'folio',
       'warehouse_inventory_id',
       'movement_type',
       'quantity',
       'reason',
       'user_id',
       'created_at',
       'updated_at'
    ];

    public function inventory()
    {
        return $this->belongsTo(
            WarehouseInventoryModel::class,
            'warehouse_inventory_id',
            'id'
        );
    }

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id',
            'id'
        );
    }
}
