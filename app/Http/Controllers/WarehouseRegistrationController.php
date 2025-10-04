<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warehouse;

class WarehouseRegistrationController extends Controller
{
    public function index()
    {
        return view('module.warehouses.create');
    }

    
}
