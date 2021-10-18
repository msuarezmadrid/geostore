<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Location;
use App\StockItem;
use App\Item;
use Log;
use DB;

class Adjustment extends Model
{
    protected $fillable = ["code", "date", "location_id", "reason", "movement_status_id"];
    protected $controller = 'AdjustmentController';

    public function items()
    {
        return $this->hasMany('App\AdjustmentItem');
    }

    public function makeMovement($item, $price, $quantity) {
        $location = Location::find($this->location_id);
        $item_uom = DB::table('items')->find($item)->unit_of_measure_id;
        $stock = $location->getItemStock($item, $price, $item_uom);
        


        Log::info('item -> ' . $item);
        // Log::info($quantity);
        Log::info($stock);

        if ($stock == 0 && $quantity < 0) {
            return false;
        }
        if ($quantity < 0 && ($quantity + $stock) < 0) {
            return false;
        }

        if ($quantity == 0) {
            return false;
        }

        if ($quantity > 0) {
            if ($item_uom != 1){
                $sItem = new StockItem();
                $sItem->item_id            = $item;
                $sItem->qty                = $quantity;
                $sItem->price              = $price;
                $sItem->location_id        = $this->location_id;
                $sItem->save();
            }else{
                for ($x=0;$x<$quantity;$x++) {
                    $sItem = new StockItem();
                    $sItem->item_id            = $item;
                    $sItem->price              = $price;
                    $sItem->location_id        = $this->location_id;
                    $sItem->save();
                }
            }
        }
        else {
            switch(config('stock.pick_mode')) {
                case 'FIFO':
                    Log::info("Entra");
                    StockItem::where('item_id', $item)
                             ->where('location_id', $this->location_id)
                             ->where('price',$price)
                             ->orderBy('created_at','asc')
                             ->limit($quantity*-1)
                             ->delete();
                break;
            }
        }
        return true;

    } 

    public function validateStock($item, $price, $quantity) {
        $location = Location::find($this->location_id);
        $item_uom = DB::table('items')->find($item)->unit_of_measure_id;
        $stock = $location->getItemStock($item, $price, $item_uom);
        Log::info($this->controller . ' | ' . __FUNCTION__ . ' -> item: '. $item);
        Log::info($this->controller . ' | ' . __FUNCTION__ . ' -> stock: '. $stock);

        if ($stock == 0 && $quantity < 0) {
            return false;
        }
        if ($quantity < 0 && ($quantity + $stock) < 0) {
            return false;
        }

        if ($quantity == 0) {
            return false;
        }

        return true;
    }

}

