<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiController;
use Log;
use Validator;
use App\User;
use Carbon\Carbon;
use App\PurchaseOrder;
use App\Location;
use App\Supplier;
use App\PurchaseOrderItem;
use App\Item;
use App\File;
use App\Category;
use App\LocationItem;
use App\ItemAttributeValue;
use App\UnitOfMeasureConversion;
use App\Transact;
use App\UnitOfMeasure;
use App\Attribute;
use App\MovementHistorical;
use App\StockItem;

class PurchaseOrderController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Log::info(__FUNCTION__ . " PurchaseOrderController " );
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


        $datas = new PurchaseOrder();
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

                if($filters['supplier_id'] !== null) {
                    $datas = $datas->where('supplier_id',$filters['supplier_id']);
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

        
        $data->purchases = $datas->get();
        if($data->recordsFiltered == 0) $data->recordsFiltered = $datas->count();
        
        foreach ($data->purchases as $key => $value) {
           $data->purchases[$key]->location = Location::find($value->location_id);
           $data->purchases[$key]->supplier = Supplier::find($value->supplier_id);
        }
        
        return $this->json($request, $data, 200);


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
        Log::info(__FUNCTION__ . " PurchaseOrderController " );
        try {
    
            $this->_method = $request->method();
            $validator = Validator::make($request->all(), $this->rules(), $this->message());

            if($validator->fails()){
                Log::info("message=[{$validator->errors()}]");
                $errors = ['errors' => $validator->errors()];
                return $this->json($request,$errors,200);
            }

            $code = PurchaseOrder::where('code', $request->input('code'))
                            ->first();

            
            
            if($code != null) {
                    return $this->json($request,['errors' => [
                       'code' => [
                           'Ya existe codigo en el sistema'
                       ] 
                    ]],200);
            }


            DB::beginTransaction();
            $data = new PurchaseOrder();
            $data->fill($request->all());
            $user = User::find($request->user()->id);
            $data->enterprise_id = $user->enterprise->id;
            $data->created_by = $request->user()->id;
            $data->updated_by = $request->user()->id;
            $data->save();

            $historical = new MovementHistorical();
            $historical->order_id = $data->id;
            $historical->movement_status_id = 2;
            $historical->movement_type = "purchases";
            $historical->location_id = $data->location_id;
            $historical->created_by = $request->user()->id;
            $historical->updated_by = $request->user()->id;
            $historical->enterprise_id = $request->user()->enterprise->id;
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
        Log::info(__FUNCTION__ . " PurchaseOrderController " );
        try {
            $data = PurchaseOrder::find($id);

            if($data === null) {
                return $this->json($request, $data, 404);
            }

            Log::info(__FUNCTION__ . " data: " .json_encode($data) );

            return $this->json($request, $data, 200);
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
    public function items(Request $request, $id)
    {
        Log::info(__FUNCTION__ . " PurchaseOrderController " );
        $data = new \stdClass();

        $items = [];
        $purchaseOrderItems = PurchaseOrderItem::where('purchase_order_id', $id)->get();
        if($purchaseOrderItems !== null){
            foreach ($purchaseOrderItems as $key => $value) {
                $i = DB::table('items')->where('id',$value->item_id)->first();
                array_push($items, $i);
            }
        }
        $data->items = $items;
        $total = 0;
        foreach ($data->items as $key => $value) 
        {
            $data->items[$key]->purchase_order_item_id = $purchaseOrderItems[$key]->id;
            $data->items[$key]->quantity = $purchaseOrderItems[$key]->quantity;
            $data->items[$key]->price = $purchaseOrderItems[$key]->price;
            $total += $purchaseOrderItems[$key]->price * $purchaseOrderItems[$key]->quantity;

            $data->items[$key]->unit_of_measure_id = $purchaseOrderItems[$key]->unit_of_measure_id;
            
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

        Log::info(__FUNCTION__ . json_encode($data));

        $data->total_order_price = $total;
        $data->recordsTotal = count($data->items);
        $data->recordsFiltered = count($data->items);
        return $this->json($request, $data, 200);
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
        Log::info(__FUNCTION__ . " PurchaseOrderController " );

        try {
            $data = PurchaseOrder::find($id);

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
                $historical->movement_type = "purchases";
                $historical->location_id = $data->location_id;
                $historical->created_by = $request->user()->id;
                $historical->updated_by = $request->user()->id;
                $historical->enterprise_id = $request->user()->enterprise->id;
                $historical->save();

                DB::commit();
                return $this->json($request, $data, 200);
            }

            if($data->movement_status_id == 2 && $request->get('movement_status_id')==3){

                DB::beginTransaction();
                $purchaseItems = PurchaseOrderItem::where('purchase_order_id', $data->id)->get();

                foreach ($purchaseItems as $key => $value) {
                    $item_uom = Item::find($value->item_id)->unit_of_measure_id;

                    $transact = new Transact();
                    if ($value->quantity == '1') {
                        $uom_name = UnitOfMeasure::find($item_uom)->first()->name;
                        $transact->description = 'Se agregó <b>' . $value->quantity . ' ' . $uom_name . '</b> por orden de entrada ($' .$value->price. ')';
                    }else{
                        $uom_name = UnitOfMeasure::find($item_uom)->first()->plural;
                        $transact->description = 'Se agregaron <b>' . $value->quantity . ' ' . $uom_name . '</b> por orden de entrada ($' . $value->price. ')';
                    }
                    $transact->object_id = $value->item_id;
                    $transact->object_type = "items";
                    $transact->created_by = $request->user()->id;
                    $transact->save();

                    if($item_uom != 1){
                        $sItem = new StockItem();
                        $sItem->item_id     = $value->item_id;
                        $sItem->qty     = $value->quantity;
                        $sItem->price       = $value->price;
                        $sItem->location_id = $data->location_id;
                        $sItem->save();
                    }else{
                        for ($x = 0; $x < $value->quantity; $x++) {
                            $sItem = new StockItem();
                            $sItem->item_id     = $value->item_id;
                            $sItem->price       = $value->price;
                            $sItem->location_id = $data->location_id;
                            $sItem->save();
                        }
                    }
                }

                $historical = new MovementHistorical();
                $historical->order_id = $data->id;
                $historical->movement_status_id = 3;
                $historical->movement_type = "purchases";
                $historical->location_id = $data->location_id;
                $historical->created_by = $request->user()->id;
                $historical->updated_by = $request->user()->id;
                $historical->enterprise_id = $request->user()->enterprise->id;
                $historical->save();
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
            $data = PurchaseOrder::find($id);

            if($data === null) {
                return $this->json($request, $data, 404);
            }

            $items = PurchaseOrderItem::where('purchase_order_id', $id)->delete();
            
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
                    'date'            => 'required|date',
                    'code'            => 'required|string',
                    'supplier_id'     => 'required|integer',
                    'location_id'     => 'required|integer'
                    /*'x' => 'integer',
                    'y' => 'integer',
                    'z' => 'integer',
                    'latitude' => 'float',
                    'longitude' => 'float',*/
                ];
                break;

            case 'PUT':
                return [
                    'date'            => 'date',
                ];
                break;
        }
    }

    private function message() 
    {
        switch ($this->_method)
        {
            case 'POST':
                return [
                    'date.required'         => 'Se debe indicar una fecha',
                    'date.date'             => 'La fecha debe ser una fecha valida',
                    'code.required'         => 'Se debe indicar un codigo de orgen',
                    'code.string'           => 'El codigo de orden debe ser un valor alfanumerico valido',
                    'supplier_id.required'  => 'Se requiere indicar el proveedor',
                    'supplier_id.integer'   => 'El proveedor debe tener una ID valida',
                    'location_id.required'  => 'Se debe indicar un almacen',
                    'location_id.integer'   => 'El almacen debe tener una ID valida'
                ];
                break;

            case 'PUT':
                return [
                    'date.date'            => 'El cambo de fecha debe ser una fecha valida',
                ];
                break;
        }
    }
}
