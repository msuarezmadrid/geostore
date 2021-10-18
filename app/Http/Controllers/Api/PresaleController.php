<?php

namespace App\Http\Controllers\Api;

use App\Config;
use Log;
use Barryvdh\DomPDF\Facade as PDFDOM;
use DNS1D;
use App\Location;
use App\OrderNote;
use App\StockItem;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use DB;

class PresaleController extends ApiController
{
    public function print(Request $request, $id, $index) 
    {
        Log::info(__FUNCTION__ . " PresaleController ");
        $onote = OrderNote::find($id);
        $printHeaders = Config::where('param', 'VOUCHER_MULTIPRINT')->first();
        if($printHeaders != null) {
            $printHeaders = explode(';', $printHeaders->value);
        }
        else {
            $printHeaders = array();
        }

        if(count($printHeaders) > 0 && count($printHeaders) >= $index && trim($printHeaders[$index]) !== '' && strtoupper($printHeaders[$index]) != 'DEFAULT') {
            $header = $printHeaders[$index];
        }
        else {
            $header = 'DEFAULT';
        }

        $info  = $onote->getPdfInfo();

        $info->header = $header;
        //TO LOCAL DATE (CHILE)
        $info->date = Carbon::createFromFormat('Y-m-d H:i:s', $info->created_at, 'UTC')
            ->setTimezone('America/Santiago')
            ->format('d-m-Y');
        $info->hour = Carbon::createFromFormat('Y-m-d H:i:s', $info->created_at, 'UTC')
            ->setTimezone('America/Santiago')
            ->format('H:i:s');

        $data  = [
            'onote' => $info,
            'id'    => 'ON'.$info->id
        ];

        $addressOverride = config('pdf.pdf_print_folder') == '' ? 'geostore' : config('pdf.pdf_print_folder');
        $path = ".presale.".strtolower($addressOverride);

        Log::info(__FUNCTION__ . " path: " . $path );
        
        $filePath = storage_path('app/public').'/onotes/';
        if(!file_exists($filePath)) {
            mkdir($filePath, 0755, true);
        }

        //40px per item
        $length = 410 + count($info->details)*80;
        //226
        $pdfIndexName = '';
        if(count($printHeaders) > 1) {
            $pdfIndexName = '_'.($index+1);
        }
        $pdf = PDFDOM::loadView('pdf'.$path.'.presale', $data)
            ->setPaper(array(10,0,226.77 , $length));
        $pdf->save(storage_path('app/public').'/onotes/'.$onote->id.$pdfIndexName.'.pdf');
        return $this->json($request, [
            'success' => true,
            'url' => asset('storage/onotes').'/'.$onote->id.$pdfIndexName.'.pdf'
        ], 200);
    }

    public function stock(Request $request) 
    {
        Log::info(__FUNCTION__ . " PresaleController ");
        DB::enableQueryLog();

        $carts = $request->input('cart');
        $carts = json_decode($carts);
        Log::info(__FUNCTION__ . " request: " . $request );
        foreach ($carts as $cart) {

            $location = Location::where('code', $cart->withdraw)->first();
            if ($location) {
                $qty = StockItem::where('item_id', $cart->item_id)
                    ->where('location_id', $location->id)
                    ->sum('qty');
                $cart->stock = $qty != null ? $qty : 0;
            }
            $qrys = DB::getQueryLog();
            ## ULTIMA QUERY EJECUTADA 
            Log::info(__FUNCTION__ . " query: " . json_encode(end($qrys)) );
        }
        return $this->json($request, $carts, 200);
    }
}
