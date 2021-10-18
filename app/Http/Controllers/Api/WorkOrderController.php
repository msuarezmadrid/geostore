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
use App\WorkOrder;
use App\WorkStation;
use App\WorkOrderItem;
use App\Item;
use App\File;
use App\Category;
use App\LocationItem;
use App\ItemAttributeValue;
use App\UnitOfMeasureConversion;
use App\Attribute;
use App\MovementHistorical;

class WorkOrderController extends ApiController
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


        $datas = new WorkOrder();
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

                if($filters['work_station_id'] !== null) {
                    $datas = $datas->where('work_station_id',$filters['work_station_id']);
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

        
        $data->work_orders = $datas->get();
        if($data->recordsFiltered == 0) $data->recordsFiltered = $datas->count();
        
        foreach ($data->work_orders as $key => $value) {
           $data->work_orders[$key]->location = Location::find($value->location_id);
           $data->work_orders[$key]->work_station = WorkStation::find($value->work_station_id);
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
        $workOrderItems = WorkOrderItem::where('work_order_id', $id)->get();
        if($workOrderItems !== null){
            foreach ($workOrderItems as $key => $value) {
                $i = DB::table('items')->where('id',$value->item_id)->first();
                array_push($items, $i);
            }
        }
        $data->items = $items;
        foreach ($data->items as $key => $value) 
        {
            $data->items[$key]->work_order_item_id = $workOrderItems[$key]->id;
            $data->items[$key]->quantity = $workOrderItems[$key]->quantity;
            $data->items[$key]->unit_of_measure_id = $workOrderItems[$key]->unit_of_measure_id;

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
            DB::beginTransaction();
            $data = new WorkOrder();
            $data->fill($request->all());
            $user = User::find($request->user()->id);
            $data->enterprise_id = $user->enterprise->id;
            $data->created_by = $request->user()->id;
            $data->updated_by = $request->user()->id;
            $data->save();

            $historical = new MovementHistorical();
            $historical->order_id = $data->id;
            $historical->movement_status_id = 1;
            $historical->movement_type = "work_orders";
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
            $data = WorkOrder::find($id);

            if($data === null) {
                return $this->json($request, $data, 404);
            }

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
            $data = WorkOrder::find($id);

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
                $historical->movement_type = "work_orders";
                $historical->location_id = $data->location_id;
                $historical->created_by = $request->user()->id;
                $historical->updated_by = $request->user()->id;
                $historical->enterprise_id = $request->user()->enterprise->id;
                $historical->save();

            }

            if($data->movement_status_id == 2 && $request->get('movement_status_id')==3){
                $saleItems = WorkOrderItem::where('work_order_id', $data->id)->get();

                DB::beginTransaction();
                foreach ($saleItems as $key => $value) {

                    $locationItem = LocationItem::where('location_id', $data->location_id)->where('item_id', $value->item_id)->first();

                    $uom_from = $value->unit_of_measure_id;
                    $item = Item::find($value->item_id);
                    $uom_to = $item->unit_of_measure_id;

                    
                    if($uom_from != $uom_to){
                        $con = UnitOfMeasureConversion::where('uom_from_id', $uom_from)->where('uom_to_id', $uom_to)->first();
                        $locationItem->quantity -= $con->factor*$value->quantity;
                        if($locationItem->quantity<0){
                            DB::rollback();
                            $data = new \stdClass();
                            $errors =  new \stdClass();
                            $errors->message = 'El producto "'.$item->name. '" no cuenta con stock suficiente.';
                            $data->errors = $errors;
                            return $this->json($request, $data, 200);

                        }
                    }else{
                        $locationItem->quantity -= $value->quantity;
                        if($locationItem->quantity<0){
                            DB::rollback();
                            $data = new \stdClass();
                            $errors =  new \stdClass();
                            //$errors->message = "Existen productos que no cuentan con stock suficiente.";
                            $errors->message = 'El producto "'.$item->name. '" no cuenta con stock suficiente.';
                            $data->errors = $errors;
                            return $this->json($request, $data, 200);
                        }
                    }
                    $locationItem->updated_by = $request->user()->id;
                
                    
                    $locationItem->save();
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

                DB::commit();
            }

            $data->fill($request->all());
            $data->save();
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
                    'code' => 'required|max:20',
                    'date' => 'required|date',
                    'location_id' => 'required|integer',
                    'work_station_id' => 'required|integer'
                    /*'x' => 'integer',
                    'y' => 'integer',
                    'z' => 'integer',
                    'latitude' => 'float',
                    'longitude' => 'float',*/
                ];
                break;

            case 'PUT':
                return [
                    'name' => 'required|max:150',
                    'code' => 'required|max:20',
                    'date' => 'required|date',
                    'location_id' => 'required|integer',
                    'work_station_id' => 'required|integer'
                ];
                break;
        }

    }
}
