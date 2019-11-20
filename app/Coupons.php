<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coupons extends Model
{
    public function shops() 
    {
        return $this->belongsTo('App\Shops');
    }
}
