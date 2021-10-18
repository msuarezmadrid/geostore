<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiController;
use Log;
use Validator;
use App\User;
use Carbon\Carbon;
use App\Location;
use App\SaleOrder;
use App\Client;
Use App\SaleOrderItem;
use App\Item;
use App\File;
use App\Category;
use App\LocationItem;
use App\ItemAttributeValue;
use App\UnitOfMeasureConversion;
use App\UnitOfMeasure;
use App\Transact;
use App\Attribute;
use App\MovementHistorical;
use App\PriceType;
use App\ItemPrice;
use App\StockItem;

class SaleOrderController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = new \stdClass();

        $start = $request->input('start');
        $length = $request->input('length');
        $draw = $request->input('draw');

        $columns = $request->input('columns');
        $order = $request->input('order');

        $dir = $order[0]['dir'];
        $field = $columns[$order[0]['column']]['data'];

        $filters = $request->input('filters');
        $data->recordsFiltered = 0;


        $datas = new SaleOrder();
        $enterprise = $request->user()->enterprise;
        $datas = $datas->where('enterprise_id', $enterprise->id);
        $data->recordsTotal = $datas->count();
        if($start !== null && $length !== null) {

            if($filters !== null) {
                if($filters['movement_status_id'] !== null) {
                    $datas = $datas->where('movement_status_id',$filters['movement_status_id']);
                    if($filters['movement_status_id'] == 1){
                        $datas = $datas->where('created_by', $request->user()->id);
                    }
                    $data->recordsTotal = $datas->count();
                }
                if($filters['code'] !== null) {
                    $datas = $datas->where('code','LIKE',"%{$filters['code']}%");
                }

                if($filters['client_id'] !== null) {
                    $datas = $datas->where('client_id',$filters['client_id']);
                }
                if($filters['location_id'] !== null) {
                    $datas = $datas->where('location_id',$filters['location_id']);
                }

                if($filters['start_date'] !== null && $filters['end_date'] !== null) {
                    $datas = $datas->whereBetween('date', array($filters['start_date'],$filters['end_date']) );
                }
            }

            if($order !== null) {
                $datas = $datas->orderBy($field, $dir);
            }

            $data->draw = $draw;
            $data->recordsFiltered = $datas->count();
            $datas = $datas->offset($start)->limit($length);
        }

        
        $data->sales = $datas->get();
        if($data->recordsFiltered == 0) $data->recordsFiltered = $datas->count();
        
        foreach ($data->sales as $key => $value) {
           $data->sales[$key]->location = Location::find($value->location_id);
           $data->sales[$key]->client = Client::find($value->client_id);
        }
        
        return $this->json($request, $data, 200);


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function items(Request $request, $id)
    {
        $data = new \stdClass();

        $items = [];
        $saleOrderItems = SaleOrderItem::where('sale_order_id', $id)->get();
        if($saleOrderItems !== null){
            foreach ($saleOrderItems as $key => $value) {
                $i = DB::table('items')->where('id',$value->item_id)->first();
                array_push($items, $i);
            }
        }
        $data->items = $items;
        $total = 0;
        foreach ($data->items as $key => $value) 
        {
            $data->items[$key]->sale_order_item_id = $saleOrderItems[$key]->id;
            $data->items[$key]->quantity = $saleOrderItems[$key]->quantity;
            $data->items[$key]->unit_of_measure_id = $saleOrderItems[$key]->unit_of_measure_id;
            $data->items[$key]->price = $saleOrderItems[$key]->price;
            $total += $saleOrderItems[$key]->price * $saleOrderItems[$key]->quantity;

            $data->items[$key]->price_type_ids = ItemPrice::where('item_id', $value->id)->get();

            $attrValues = ItemAttributeValue::where('item_id', $value->id)->get();
            $attributes = [];
            foreach ($attrValues as $key2 => $value2) {
                $att = new \stdClass();
                $att->id = $value2->id;
                //return $this->json($request, $value2,200);
                $attribute = Attribute::find($value2->attribute_id);
                $att->name = $attribute->name;
                $att->type = $attribute->type;

                if($attribute->type == "integer"){
                    $att->value = $value2->int_val;
                } else if($attribute->type == "float"){
                    $att->value = $value2->float_val;
                } else if($attribute->type == "date"){
                    $att->value = $value2->date_val;
                } else{
                    $att->value = $value2->text_val;
                } 
                array_push($attributes, $att);
            }

            
            $data->items[$key]->attributes = $attributes;

            $img = File::where('object_id', $value->id)
                        ->where('object_type', 'items')
                        ->where('type', "IMG")
                        ->first();
            if($img !== null)
            {
                $data->items[$key]->image_route = $img->filename;
            }
            else
            {
                $data->items[$key]->image_route = "";
            }

            $cat = Category::where('id', $value->category_id)->first();
            $data->items[$key]->category = "";
            if($cat !== null){
                $data->items[$key]->category = $cat->fullRoute();
            }
            
        }
        $data->total_order_price = $total;
        $data->recordsTotal = count($data->items);
        $data->recordsFiltered = count($data->items);
        return $this->json($request, $data, 200);
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

            $code = SaleOrder::where('code', $request->input('code'))
                            ->first();

            
            
            if($code != null) {
                    return $this->json($request,['errors' => [
                       'code' => [
                           'Ya existe codigo en el sistema'
                       ] 
                    ]],200);
            }

            DB::beginTransaction();
            $data = new SaleOrder();
            $data->fill($request->all());
            $user = User::find($request->user()->id);
            $data->enterprise_id = $user->enterprise->id;
            $data->created_by = $request->user()->id;
            $data->updated_by = $request->user()->id;
            $data->save();

            $historical = new MovementHistorical();
            $historical->order_id = $data->id;
            $historical->movement_status_id = 2;
            $historical->movement_type = "sales";
            $historical->location_id = $data->location_id;
            $historical->enterprise_id = $request->user()->enterprise->id;
            $historical->created_by = $request->user()->id;
            $historical->updated_by = $request->user()->id;
            $historical->save();

            DB::commit();
            return $this->json($request, $data, 201);

        } catch(\Exception $e) {
            DB::rollback();
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
        try {
            $datas = new \stdClass();
            $data = SaleOrder::find($id);

            if($data === null) {
                return $this->json($request, $data, 404);
            }

            $datas->sale_order = $data;

            return $this->json($request, $datas, 200);
        } catch(\Exception $e) {
            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
            return $this->json($request, null, 500);
        }
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
        try {
            $data = SaleOrder::find($id);

            if($data === null) {
                return $this->json($request, $data, 404);
            }

            if($data->movement_status_id == 1 && $request->get('movement_status_id')==2){
                DB::beginTransaction();
                $data->movement_status_id = 2;
                $data->save();

                $historical = new MovementHistorical();
                $historical->order_id = $data->id;
                $historical->movement_status_id = 2;
                $historical->movement_type = "sales";
                $historical->location_id = $data->location_id;
                $historical->created_by = $request->user()->id;
                $historical->updated_by = $request->user()->id;
                $historical->enterprise_id = $request->user()->enterprise->id;
                $historical->save();

            }

            if($data->movement_status_id == 2 && $request->get('movement_status_id')==3){
                $saleItems = SaleOrderItem::where('sale_order_id', $data->id)->get();

                DB::beginTransaction();

                
                foreach($saleItems as $key => $value) {

                    $uom_from = $value->unit_of_measure_id;
                    $item = Item::find($value->item_id);
                    $uom_to = $item->unit_of_measure_id;

                    $locationItem = StockItem::where('location_id', $data->location_id)->where('item_id', $value->item_id);
                    
                    $locationItem->qty = $locationItem->sum('qty');
                    

                    if($uom_from != $uom_to){
                        
                        $con = UnitOfMeasureConversion::where('uom_from_id', $uom_from)->where('uom_to_id', $uom_to)->first();
                        $locationItem->qty -= $con->factor*$value->quantity;
                        if($locationItem->qty<0){
                            DB::rollback();
                            $data = new \stdClass();
                            $errors =  new \stdClass();
                            $errors->message = 'El producto "'.$item->name. '" no cuenta con stock suficiente.';
                            $data->errors = $errors;
                            return $this->json($request, $data, 200);

                        }else{
                            if($uom_to == '1'){
                                for ($x=0; $x<$value->quantity; $x++) {
                                    $locationItem = StockItem::where('location_id', $data->location_id)
                                                ->where('item_id', $value->item_id)
                                                ->first()
                                                ->delete();                                
                                }
                            }else{
                                
                                $locationItem = $locationItem->first();
                                                    
                                $validateValue = $locationItem->qty-$value->quantity;
                                while ($validateValue <= 0){
                                    $locationItem = $locationItem->delete();
                                    $locationItem = StockItem::where('location_id', $data->location_id)
                                                    ->where('item_id', $value->item_id)
                                                    ->first();

                                                    
                                    $validateValue = $locationItem->qty+$validateValue;
                                }
                                $locationItem->qty = round($validateValue,2);
                                $locationItem->save();
                            }
                        }
                    }else{
                        $locationItem->qty -= $value->quantity;
                        
                        if($locationItem->qty<0){
                            DB::rollback();
                            $data = new \stdClass();
                            $errors =  new \stdClass();
                            //$errors->message = "Existen productos que no cuentan con stock suficiente.";
                            $errors->message = 'El producto "'.$item->name. '" no cuenta con stock suficiente.';
                            $data->errors = $errors;
                            return $this->json($request, $data, 200);
                        }else{
                            $transact = new Transact();
                            if ($value->quantity == '1') {
                                $uom_name = UnitOfMeasure::find($uom_to)->first()->name;
                                $transact->description = 'Se retiró <b>' . $value->quantity . ' ' . $uom_name . '</b> por orden de salida';
                            }else{
                                $uom_name = UnitOfMeasure::find($uom_to)->first()->plural;
                                $transact->description = 'Se retiraron <b>' . $value->quantity . ' ' . $uom_name . '</b> por orden de salida';
                            }
                            $transact->object_id = $value->item_id;
                            $transact->object_type = "items";
                            $transact->created_by = $request->user()->id;
                            $transact->save();
                            if($uom_to == '1'){
                                for ($x=0; $x<$value->quantity; $x++) {
                                    $locationItem = StockItem::where('location_id', $data->location_id)
                                                ->where('item_id', $value->item_id)
                                                ->first()
                                                ->delete();                                
                                }
                            }else{
                                
                                $locationItem = $locationItem->first();
                                                    
                                $validateValue = $locationItem->qty-$value->quantity;
                                while ($validateValue <= 0){
                                    $locationItem = $locationItem->delete();
                                    $locationItem = StockItem::where('location_id', $data->location_id)
                                                    ->where('item_id', $value->item_id)
                                                    ->first();

                                                    
                                    $validateValue = $locationItem->qty+$validateValue;
                                }
                                $locationItem->qty = round($validateValue,2);
                                $locationItem->save();
                            }
                        }
                    }
                }
              
                $data->movement_status_id = 3;
                $data->save();

                $historical = new MovementHistorical();
                $historical->order_id = $data->id;
                $historical->movement_status_id = 3;
                $historical->movement_type = "sales";
                $historical->location_id = $data->location_id;
                $historical->created_by = $request->user()->id;
                $historical->updated_by = $request->user()->id;
                $historical->enterprise_id = $request->user()->enterprise->id;
                $historical->save();

                
            }

            $data->fill($request->all());
            $data->updated_by = $request->user()->id;
            $data->save();
            DB::commit();
            return $this->json($request, $data, 200);
        } catch(\Exception $e) {
            DB::rollback();
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
            $data = SaleOrder::find($id);

            if($data === null) {
                return $this->json($request, $data, 404);
            }

            $items = SaleOrderItem::where('sale_order_id', $id)->delete();
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
                    'name' => 'required|max:150',
                    'date'            => 'required|date',
                    'code'            => 'required'
                    
                    /*'x' => 'integer',
                    'y' => 'integer',
                    'z' => 'integer',
                    'latitude' => 'float',
                    'longitude' => 'float',*/
                ];
                break;

            case 'PUT':
                return [
                    'name'            => 'required|max:150'
                ];
                break;
        }

    }
}
