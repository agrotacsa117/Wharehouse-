<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rack extends Model
{
    use HasFactory;

    protected $table = 'rack';

    protected $fillable = [
        'rack_aduana',
        'cantidad_max',
        'user_id',
        'bodega',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function productosCount()
    {
        // Relación para contar productos en este rack
        return $this->hasMany(Producto::class, 'rack_id');
    }
}
