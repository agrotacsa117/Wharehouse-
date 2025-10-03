<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bodega extends Model
{
    use HasFactory;

    protected $table = 'rack';

    protected $fillable = [
        'bodega',
    ];

    public static function listaBodegas()
    {
        return self::query()->select('bodega')->distinct()->pluck('bodega');
    }
}
