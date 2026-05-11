<?php

namespace App\Models;

use App\Models\WarehouseInventoryMovementsModel;
use Illuminate\Database\Eloquent\Model;

class WarehouseSalesModel extends Model
{
    protected $table = "warehouse_sales";

    protected $fillable = [
        "movement_id",
        "client_id",
        "invoice_sap",
    ];

    public function movement()
    {
        return  $this->belongsTo(
            WarehouseInventoryMovementsModel::class,
            "movement_id",
            'folio'
        );
    }

     
}
