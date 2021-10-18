<?php

namespace App;

use DB;
use Log;
use App\CartItem;
use App\Discount;
use App\OrderNoteDetail;
use App\SaleBox;
use Illuminate\Database\Eloquent\Model;

class OrderNote extends Model
{
    const IVA                        = 0.19;

    public function getPdfInfo() {

        
        $info = DB::table('order_notes')
                  ->join('sellers', 'order_notes.seller_id', '=', 'sellers.id')
                  ->leftJoin('clients', 'order_notes.client_id', '=', 'clients.id')
                  ->where('order_notes.id', $this->id)
                  ->select([
                      'order_notes.id',
                      'sellers.name as sellername',
                      'clients.name as clientname',
                      'clients.address as address',
                      DB::raw('CONCAT(clients.rut, "-", clients.rut_dv) as clientid'),
                      'order_notes.created_at',
                      'order_notes.type',
                      'order_notes.quote_type',
                      'order_notes.price_quote_expiration'
                  ])
                  ->first();

        $info->details = DB::table('order_note_details')
                            ->leftJoin('items', 'order_note_details.item_id', '=', 'items.id')
                            ->where('order_note_id', $this->id)
                            ->select([
                                'order_note_details.price',
                                'order_note_details.price_quote_description',
                                'order_note_details.discount_percent',
                                'order_note_details.qty',
                                DB::raw('IFNULL(items.name,order_note_details.name) as name'),
                                'items.manufacturer_sku',
                                'order_note_details.withdraw'
                            ])
                            ->get();
        $total  = 0;
        foreach ($info->details as $detail) {
            $total += round($detail->price*$detail->qty);
        }
        $info->total = $total;

        if ($info->type){
        $info->type  = $info->type == 1 ? 'BOLETA' : 'FACTURA';
        }

        if ($info->quote_type == '1') {
            $price_quote_description = [];
            $total = round(($total/1.19),0);
            foreach ($info->details as $key => $detail) {
                $total -= round((($detail->price/1.19)*$detail->qty*($detail->discount_percent/100)),0);
                $item_price = round((($detail->price/1.19) - (($detail->price/1.19) * ($detail->discount_percent/100)) ),2);
                $item_total = round(($detail->qty*$item_price) ,0);
                $detail->item_price = $item_price;
                $detail->item_total = $item_total;

                array_push($price_quote_description, $detail->price_quote_description);
            }


            $info->price_quote_description = $price_quote_description[0];

            $totalTax = round(($total*self::IVA), 0);
            $info->net = number_format($total, 0, ',', '.');
            $info->tax = number_format($totalTax, 0, ',', '.');
            $total = $totalTax + $total;
            $info->total = number_format($total, 0, ',', '.');

        }

        return $info;
    }

    public function getResumeInfo() {
        $onote = OrderNote::where('id', $this->id)
                          ->select([
                              'client_id',
                              'seller_id',
                              'type',
                              'quote_type',
                              'id',
                              'price_quote_expiration'
                          ])
                          ->first();
                          
        $onote->details = OrderNoteDetail::where('order_note_id', $this->id)
                                            ->leftJoin('items', 'order_note_details.item_id', '=', 'items.id')
                                            ->where('order_note_id', $this->id)
                                            ->select([
                                                'order_note_details.price',
                                                'order_note_details.qty',
                                                DB::raw('IFNULL(items.name,order_note_details.name) as name'),
                                                DB::raw('IFNULL(items.manufacturer_sku,"") as manufacturer_sku'),
                                                'order_note_details.withdraw',
                                                DB::raw('IFNULL(items.id,0) as item_id'),
                                                DB::raw('IFNULL(items.block_discount,"") as block_discount')
                                                
                                            ])
                                            ->get(); 
        return $onote;
    }

    public function getResumeInfoQuotePrice() {
        $pquote = OrderNote::where('id', $this->id)
                            // ->where('quote_type','1')
                          ->select([
                              'client_id',
                              'id',
                              'quote_type',
                              'price_quote_expiration'
                          ])
                          ->first();

                            
        $pquote->details = OrderNoteDetail::where('order_note_id', $this->id)
                                            ->leftJoin('items', 'order_note_details.item_id', '=', 'items.id')
                                            ->where('order_note_id', $this->id)
                                            ->select([
                                                'order_note_details.price_quote_description',
                                                'order_note_details.discount_percent',
                                                'order_note_details.price',
                                                'order_note_details.qty',
                                                DB::raw('IFNULL(items.name,order_note_details.name) as name'),
                                                DB::raw('IFNULL(items.manufacturer_sku,"") as manufacturer_sku'),
                                                DB::raw('IFNULL(items.id,0) as item_id'),
                                                DB::raw('IFNULL(items.block_discount,"") as block_discount')
                                            ])
                                            ->get(); 
                                            
        return $pquote;
    }

    public function makeOrder($cartorder_id, $user_id) {
        $observations = [];
        $onitems = OrderNoteDetail::where('order_note_id', $this->id)
                                ->leftJoin('items', 'order_note_details.item_id', '=', 'items.id')
                                ->leftJoin('item_prices', function($join) {
                                    $join->on('item_prices.item_id', '=', 'items.id');
                                    $join->on('item_prices.item_active', '=', DB::raw(1));
                                })
                                ->select([
                                    'order_note_details.item_id',
                                    DB::raw('IFNULL(items.name, order_note_details.name) as name'),
                                    DB::raw('IFNULL(item_prices.price, order_note_details.price) as price'),
                                    'order_note_details.qty',
                                    'order_note_details.withdraw',
                                    'order_note_details.discount_percent',
                                    'order_note_details.price_quote_description'
                                ])
                                ->get();
        $empty = false;
        if($onitems->count() == 0) {
            $empty = true;
        }

        $onitems = $onitems->filter(function($item) {
            return ($item->name !== null && trim($item->name) !== "") || $item->id !== null;
        });

        if($onitems->count() == 0 && !$empty) {
            $observations['INVALID_NAME'] = true;
        }
                            
        foreach($onitems as $item) {
            if (!$item->withdraw){
                $item->withdraw = OrderNoteDetail::where('withdraw','!=','')->limit('1')->first()->withdraw;
            }

           

            $location = Location::where('code', $item->withdraw)->first();
        
            $cartItem                   = new CartItem();
            $cartItem->price            = $item->price;
            $cartItem->name             = $item->name;
            $cartItem->quantity         = $item->qty;
            $cartItem->item_id          = $item->item_id;

            if ($item->discount_percent){
                $cartItem->discount_percent = $item->discount_percent;
            }

            if(!$location) {
                $location = SaleBox::where('status', 1)
                ->where('seller', $user_id)
                ->first()->location_id;
            }else{
                $location = $location->id;
            }

            $cartItem->location_id      = $location;
            
            $cartItem->cart_order_id    = $cartorder_id;
            
            $cartItem->discount_id      = Discount::where('name','Sin Descuento')->first()->id;
            $cartItem->save();
        }
        return $observations;
    }
}
