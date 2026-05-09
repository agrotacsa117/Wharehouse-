<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\WarehouseSalesModel;

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
        'operation_date',
        'source_warehouse_id',
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

    public function sourceWarehouse()
    {
        return $this->belongsTo(
            WarehouseModel::class,
            'source_warehouse_id', // Tu llave foránea en los movimientos
            'id'                    // La llave primaria en la tabla warehouses
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

    public function sale()
    {
        return $this->hasOne(
            WarehouseSalesModel::class,
            'movement_id',
            'folio'
        );
    }
}
