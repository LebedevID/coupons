<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;

class ShopsController extends Controller
{
    public function getShops() {       
        $shops = App\Shops::all();
        return view('shops',compact('shops'));
    }
}
