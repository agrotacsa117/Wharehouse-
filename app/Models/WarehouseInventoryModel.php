<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseInventoryModel extends Model
{
    protected $table = "warehouse_inventory";

    protected $fillable = [
        'warehouse_id',
        'product_id',
        'rack',
        '_level',
        'updated_at',
        'warehouse_name',
        'quantity',
        'lot_number',
        'reason',
        'expiration_date',
        'module',
        'bay',
        'platform',
        'transfer_folio'
    ];


    public function warehouse()
    {
        return $this->belongsTo(
            WarehouseModel::class,
            'warehouse_id',
            'id'
        );
    }
}
