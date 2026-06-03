<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    // Indicamos explícitamente el nombre de la tabla
    protected $table = 'branches';

    // Definimos los campos que se pueden llenar de forma masiva
    protected $fillable = [
        'branch_name',
    ];

    // Si tu tabla no tiene las columnas 'created_at' y 'updated_at', desactívalas:
    public $timestamps = false;
}
