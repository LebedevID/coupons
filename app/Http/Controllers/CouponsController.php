<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App;

class CouponsController extends Controller
{
    public function getCoupons($id) {
		$coupons = App\Coupons::where('shop_id',$id)->get();
		return view('coupons', compact('coupons'));
	}
}
