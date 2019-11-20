<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ShopsController extends Controller
{
    public function getShops() {       
        $shops = App\Shops::all();
        return view('shops',compact('shops'));
    }
}
