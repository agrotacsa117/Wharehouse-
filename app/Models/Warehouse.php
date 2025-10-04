<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{

    protected $table = "warehouses";

    protected $fillable = [
        'warehouses_name',
        'user_id',
        'creation_date',
        'last_update_date',
        'user_last_update',
        'warehouses_key',
        'warehouse_manager',
        'phone_number',
        'email',
        'warehouse_type',
        'maximum_capacity'
    ];

    
}
