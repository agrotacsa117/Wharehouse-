<?php

namespace App\Models;

use App\Enterprise_Layer\Warehouse;
use App\Enterprise_Layer\WarehouseType;
use Illuminate\Database\Eloquent\Model;

class WarehouseModel extends Model
{
    protected $table = "warehouses";

    protected $fillable = [
        'warehouses_name',
        'user_id',
        'warehouses_key',
        'warehouse_manager',
        'phone_number',
        'email',
        'warehouse_type_id',
    ];


    public function warehouseType()
    {
        return $this->belongsTo(
            WarehouseTypeModel::class,
            'warehouse_type_id',
            'id'
        );
    }
}
