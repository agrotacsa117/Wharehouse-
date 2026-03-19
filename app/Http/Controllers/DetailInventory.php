<?php

namespace App\Http\Controllers;

class DetailInventory extends Controller
{
    public function getView()
    {
        return view('module.inventory_details.expiration_report');
    }
}
