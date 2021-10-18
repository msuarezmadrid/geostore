<?php

namespace App;

use Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Item;
use DB;

class StockItem extends Model
{
    use SoftDeletes;
    protected $dates = ["deleted_at"];
    protected $fillable = ["item_id", "price", "location_id"];

    public function getStocks ($item, $locations) {
        $stocks     = [];
        $item_uom   = DB::table('items')->where('id', $item)->first()->unit_of_measure_id;

        foreach ($locations as $location) {
            if ($item_uom != 1) {
                $stocks[] = [
                    'location_id'   => $location->id,
                    'location_name' => $location->name,
                    'count'         => DB::table('stock_items')
                                        ->where('item_id', $item)
                                        ->where('location_id', $location->id)
                                        ->whereNull('order_cart_user_id')
                                        ->whereNull('deleted_at')
                                        ->sum('qty')
                ];
            }else{
                $stocks[] = [
                    'location_id'   => $location->id,
                    'location_name' => $location->name,
                    'count'         => DB::table('stock_items')
                                        ->where('item_id', $item)
                                        ->where('location_id', $location->id)
                                        ->whereNull('order_cart_user_id')
                                        ->whereNull('deleted_at')
                                        ->count()
                ];
            }
        }
                return $stocks;
    }
}
