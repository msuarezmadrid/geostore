<?php

namespace App;

use DB;
use Log;
use App\OrderNote;
use App\OrderNoteDetail;
use Illuminate\Database\Eloquent\Model;

class PriceQuote extends Model
{
    //

    public function getResumeInfoQuotePrice() {
        $pquote = OrderNote::where('id', $this->id)
                            ->where('quote_type','1')
                          ->select([
                              'client_id',
                              'id'
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
                                                DB::raw('IFNULL(items.id,0) as item_id')
                                            ])
                                            ->get(); 
                                            print_r();
                                            exit;
        return $pquote;
    }
}
