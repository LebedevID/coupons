<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CouponsController extends Controller
{
    public function getCoupons($id) {
		$coupons = App\Coupons::where('shop_id',$id)->get();
		return view('coupons', compact('coupons'));
	}
}
