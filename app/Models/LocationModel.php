<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LocationModel extends Model
{
    protected $table = "location";

    protected $fillable = [
        'headquarters_name',
        'postal_code',
        'state',
        'city',
        'created_at',
        'updated_at',
        'adress'
    ];
    
}
