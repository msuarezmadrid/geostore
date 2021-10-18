<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Location;
use App\Item;

class Transfer extends Model
{
    protected $fillable = ["location_from_id", "location_to_id", "date", "code", "movement_status_id"];

    public function items() {
        return $this->hasMany('App\TransferItem');
    }

    public function makeMovement($item, $price, $quantity) {
        $location = Location::find($this->location_from_id);
        $item_uom = Item::find($item)->unit_of_measure_id;
        $stock = $location->getItemStock($item, $price, $item_uom);


        if($stock == 0) {
            $item = Item::find($item);
            return ['success' => false, 
                    'message' => 'No existe stock del item  '.$item->name. ' en '.$location->name];
        }

        if( ($stock - $quantity) < 0) {
            $item = Item::find($item);
            return ['success' => false, 
                    'message' => 'Stock insuficiente del item  '.$item->name. ' en '.$location->name];
        }

        if ($quantity <= 0) {
            $item = Item::find($item);
            return ['success' => false, 
                    'message' => 'Cantidad inválida para el item : '.$item->name];
        }

        switch(config('stock.pick_mode')) {
            case 'FIFO':
                StockItem::where('item_id', $item)
                         ->where('location_id', $this->location_from_id)
                         ->where('price',$price)
                         ->orderBy('created_at','asc')
                         ->limit($quantity)
                         ->delete();
            break;
        }

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
                $sItem->location_id        = $this->location_to_id;
                $sItem->save();
            }
        }

        return ['success'  => true,
                'messsage' => '' ];

    }

}
