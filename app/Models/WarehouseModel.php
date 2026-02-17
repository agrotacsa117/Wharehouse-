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
        'created_at',
        'updated_at',
        'user_last_update',
        'warehouses_key',
        'warehouse_manager',
        'phone_number',
        'email',
        'warehouse_type_id',
        'location_id'
    ];


    public function warehouseType()
    {
        return $this->belongsTo(
            WarehouseTypeModel::class,
            'warehouse_type_id',
            'id'
        );
    }

    public function location()
    {
        return $this->belongsTo(
            LocationModel::class,
            'location_id',
            'id'
        );
    }
}
