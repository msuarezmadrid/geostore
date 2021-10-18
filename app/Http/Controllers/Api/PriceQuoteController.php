<?php

namespace App\Http\Controllers\Api;

use Log;
use Validator;
use PDF;
use App\PriceQuote;
use App\OrderNote;
use App\OrderNoteDetail;
use App\PriceQuoteDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class PriceQuoteController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    public function printPriceQuotes(Request $request, $id) {


        $onote = OrderNote::find($id);
        
        $info  = $onote->getPdfInfo();


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


        
        $filePath = storage_path('app/public').'/onotes/';
        if(!file_exists($filePath)) {
            mkdir($filePath, 0755, true);
        }

        //TO LOCAL DATE (CHILE)
        
		

        $pdf = PDF::loadView('pdf.price_quote', $data)
                ->setPaper('Letter' );
                $pdf->save(storage_path('app/public').'/onotes/'.$onote->id.'.pdf');
                return $this->json($request, [
                    'success' => true,
                    'url' => asset('storage/onotes').'/'.$onote->id.'.pdf'
                ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        try {
            $validator = Validator::make($request->all(), [
                'client_id'     => 'sometimes|integer',
                'cart'          => 'json|required'
            ]);
            if($validator->fails()){
                Log::info("message=[{$validator->errors()}]");
                throw new \Exception(self::SELL_VALIDATION_ERROR);
            }
            $details          = json_decode($request->input('cart'));
            $pquote            = new OrderNote();
            if ($request->has('client_id')) {
                $pquote->client_id = $request->input('client_id');
            }

            if ($request->has('price_quote_expiration')) {
                $pquote->price_quote_expiration = $request->input('price_quote_expiration');
            }else{
                $pquote->price_quote_expiration = '0';

            }

        $pquote->seller_id                 = '1';
        $pquote->quote_type                 = '1';

        
            $pquote->save();
            foreach ($details as $detail) {
                $pqd                = new OrderNoteDetail();
                if($detail->item_id != 0) {
                    $pqd->item_id       = $detail->item_id;
                } else {
                    $pqd->name = $detail->name;
                }
                if ($request->has('price_quote_description')) {
                    $pqd->price_quote_description = $request->input('price_quote_description');
                }

                // if ( $detail->discount_percent != 0 || $detail->discount_percent != null) {
                //     $pqd->discount_percent = $detail->discount_percent;
                // }
                $pqd->qty           = $detail->qty;
                $pqd->price         = $detail->price_original;
                $pqd->discount_percent = $detail->discount;
                $pqd->order_note_id = $pquote->id;
                $pqd->save();
            }
            return $this->json($request, $pquote, 200);

        } catch (\Exception $e) {
            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
            return $this->json($request, [
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $pquote = OrderNote::find($id);
        if ($pquote == null) {
            return $this->json($request, null, 404);
        }
        $info  = $pquote->getResumeInfoQuotePrice();

        if ($info->quote_type == 0){
            
            return $this->json($request, null, 404);
        }

        return $this->json($request, $info, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
