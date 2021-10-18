<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiController;
use Log;
use Validator;
use App\User;
use Carbon\Carbon;
use App\Adjustment;
use App\Location;
use App\Supplier;
use App\AdjustmentItem;
use App\StockItem;
use App\Item;
use App\File;
use App\Category;

class AdjustmentItemController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

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
            $validator = Validator::make($request->all(), $this->rules());

            if($validator->fails()){
                Log::info("message=[{$validator->errors()}]");
                $errors = ['errors' => $validator->errors()];
                return $this->json($request,$errors,200);
            }

            $adjustment = Adjustment::find($request->get('adjustment_id'));

            $data = new AdjustmentItem();
            $data->item_id       = $request->get('item_id');
            $data->adjustment_id = $request->get('adjustment_id');
            $data->quantity      = 1;
            $data->unitary_price = 0;
            $data->unit_of_measure_id = $request->get('unit_of_measure_id');
            $data->created_by = $request->user()->id;
            $data->updated_by = $request->user()->id;
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
    public function show(Request $request, $id)
    {
       
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
  

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $data = AdjustmentItem::find($id);

            if($data === null) {
                return $this->json($request, $data, 404);
            }

            $this->_method = $request->method();
            $validator = Validator::make($request->all(), $this->rules());

            if($validator->fails()){
                Log::info("message=[{$validator->errors()}]");
                $errors = ['errors' => $validator->errors()];
                return $this->json($request,$errors,200);
            }

            $data->fill($request->all());
            $data->updated_by = $request->user()->id;
            $data->save();

            return $this->json($request, $data, 200);
        } catch(\Exception $e) {
            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
            return $this->json($request, null, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try{
            $data = AdjustmentItem::find($id);

            if($data === null) {
                return $this->json($request, $data, 404);
            }

            $data->delete();
            return $this->json($request, $data, 200);
        } catch(\Exception $e) {
            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
            return $this->json($request, null, 500);
        }
    }

    private function rules()
    {
        switch ($this->_method)
        {
            case 'POST':
                return [
                    'item_id' => 'required',
                    'adjustment_id' => 'required',
                    'quantity' => 'required',
                    'unit_of_measure_id' => 'required'
                ];
                break;

            case 'PUT':
                return [
                    'quantity' => 'required',
                    'unit_of_measure_id' => 'required'
                ];
                break;
        }

    }
}
