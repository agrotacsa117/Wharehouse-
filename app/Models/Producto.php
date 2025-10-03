<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    
    protected $table = 'productos';
    
    protected $fillable = [
        'user_id',
        'categoria_id',
        'proveedor_id',
        'colocado',
        'cantidad',
        'precio',
        'precio_total',
        'fecha_ingreso',
        'fecha_caducidad',
        'activo',
        'rol',
        'rack_id', // <-- Agregado para permitir asignación masiva
    ];

    protected $dates = [
        'fecha_ingreso',
        'fecha_caducidad',
        'created_at',
        'updated_at'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
 
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function rack()
    {
        return $this->belongsTo(Rack::class);
    }
}
