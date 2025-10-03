<?php

namespace App\Models\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Producto;
use App\Models\User;

class Salida extends Model
{
    protected $fillable      = [
        'producto_id',
        'cantidad',
        'fecha_salida',
        'usuario_id',
        'observaciones',
        'ticket_pdf', // Nuevo campo para la ruta del PDF
        'bodega_destino', // Permitir guardar bodega destino
        'donado_a' // Permitir guardar donado a
    ];

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}