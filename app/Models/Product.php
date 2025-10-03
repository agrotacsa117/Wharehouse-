<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'categoria_id',
        'proveedor_id',
        'colocado',
        'cantidad',
        'precio',
        'fecha_ingreso',
        'fecha_caducidad',
        'activo'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
