<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductModel extends Model
{
    protected $connection = 'sqlsrv';

    protected $table = 'dbo.OITM';

    public $timestamps = false;
}
