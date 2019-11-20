<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Shops extends Model
{
    public function coupons() {

        return $this->hasMany('App\Coupons','shop_id','id')->select(['title','shop_id']);

    } 
	
	public static function unloaded_coupons() {
		
		 return static::where('coupons_loaded',false)->get();
		
	}
    
}
