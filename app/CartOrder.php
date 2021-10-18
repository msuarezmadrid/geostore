<?php

namespace App;

use DB;
use App\CartItem;
use Illuminate\Database\Eloquent\Model;

class CartOrder extends Model
{
    function items() {
        return $this->hasMany('App\CartItem');
    }
    function clean($user) {
        $this->items()->delete();
        DB::table('stock_items')
          ->where('order_cart_user_id', $user)
          ->update(['order_cart_user_id' => null]);
    }

}
