<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WarehouseTypeModel extends Model
{
    protected $table = 'warehouse_type';

    protected $fillable = [
        'category_warehouse',
    ];

    public function warehouses()
    {
        return $this->hasMany(WarehouseModel::class, 'warehouse_type_id', 'id');
    }
}
