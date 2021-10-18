<?php

namespace App\Http\Controllers\Api;

use DB;
use Log;
use App\User;
use Validator;
use App\PriceType;
use App\ItemPrice;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ItemPricesController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $itemPrices = ItemPrice::where('item_id', $request->get('item_id'))
                               ->get();
        return $this->json($request, $itemPrices, 200);
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
        try {
            $this->_method = $request->method();
            $validator = Validator::make($request->all(), [
                'item_id'       => 'required|integer',
                'price_type_id' => 'required|integer'
            ],
            [
                'item_id:required' => 'Es necesario especificar el item',
                'item_id:integer'  => 'El id de item debe ser un numero entero',
                'price_type_id.required'    => 'Es necesario especificar el tipo de precio',
                'price_type_id.integer'     => 'El id de tipo de precio debe ser un numero entero'
            ]);
            if($validator->fails()) {
                Log::info("message=[{$validator->errors()}]");
                $errors = ['errors' => $validator->errors()];
                return $this->json($request,$errors,403);
            }
            $data = new ItemPrice();
            $data->fill($request->all());
            $data->price = 0;
            $data->item_active = 0;
            $data->created_by  = $request->user()->id;
            $data->save();
            return $this->json($request, $data, 201);
        } catch(\Exception $e) {
            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
            return $this->json($request, null, 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        Log::info(__FUNCTION__ . " ItemPricesController ");

        $data = ItemPrice::find($id);

        Log::info(__FUNCTION__ . " data1 : " . json_encode($data) );

        if ($data === null) {
            return $this->json($request, $data, 404);
        }

        $validator = Validator::make($request->all(), [
                'item_active' => 'sometimes|integer',
                'price'       => 'required|integer|between:1,999999'
            ],
            [
                'item_active:required' => 'Es necesario especificar el item',
                'item_active:integer'  => 'El id de item debe ser un numero entero',
                'price.required'    => 'Es necesario especificar el precio',
                'price.integer'     => 'El precio debe ser un numero entero',
                'price.between'     => 'El precio no puede ser menor a 1 ni exceder 999.999'
            ]
        );

        if ($validator->fails()) {
            Log::info("message=[{$validator->errors()}]");
            $errors = ['errors' => $validator->errors()];
            return $this->json($request,$errors,403);
        }

        $data->fill($request->all());
        $data->save();

        Log::info(__FUNCTION__ . " data2 : " . json_encode($data) );

        if ($request->get('item_active') == 1) {
            ItemPrice::where('item_id', $data->item_id)
                ->whereNotIn('id', [$data->id])
                ->update([
                    'item_active' => 0
                ]);
        }
        return $this->json($request,[],201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $itemPrice = ItemPrice::find($id);
        if(count($itemPrice) == 0) {
            return $this->json($request, null, 404);
        }
        if($itemPrice->item_active == 1) {
            return $this->json($request, null, 403);
        }
        $itemPrice->delete();
        return $this->json($request, null, 201);
    }

    public function pricetypes(Request $request) {
        $user                = User::find($request->user()->id);
        $item_prices = ItemPrice::where('item_id', $request->get('item_id'))
                                ->get();
        $types   = [];
        if (count($item_prices) > 0) {
            foreach($item_prices as $ip) {
                $types[] = $ip->price_type_id;
            }
        }
        $item_types = PriceType::where('enterprise_id', $user->enterprise->id)
                               ->whereNotIn('id', $types)->get();
        return $this->json($request, $item_types, 200);
    }
}