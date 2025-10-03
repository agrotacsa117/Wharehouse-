<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    use HasFactory;
    
    protected $table = 'roles';
    
    protected $fillable = [
        'name',
        'description',
        'permissions'
    ];

    protected $casts = [
        'permissions' => 'array'
    ];

    // Relación con usuarios
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Verifica si el rol tiene un permiso específico
     */
    public function tienePermiso($permiso)
    {
        return in_array($permiso, $this->permissions ?? []);
    }
}
