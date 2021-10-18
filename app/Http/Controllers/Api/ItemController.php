<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use Validator;
use Log;
use App\ItemCategory;
use App\StockItem;
use App\Category;
use App\Item; # tabla -> Items | campos -> ["name", "description", "is_bom", "unit_of_measure_id", "custom_sku", "manufacturer_sku", "ean", "upc", "category_id", "item_type_id", "item_id", "block_discount"]
use App\File;
use App\User;
use App\Enterprise;
use App\BomItem;
use App\LocationItem;
use App\Location;
use App\Brand;
use App\ItemAttributeValue;
use App\Attribute;
use App\UnitOfMeasure;
use App\Adjustment;
use App\AdjustmentItem;
use App\SaleOrder;
use App\PurchaseOrder;
use App\PurchaseOrderItem;
use App\SaleOrderItem;
use App\TransferItem;
use App\ItemType;
use App\UnitOfMeasureConversion;
use App\ItemPrice;
use App\PriceType;
use App\Config;
use App\Transact;
use Storage;
use Image;
use DB;
use Illuminate\Validation\Rule;

class ItemController extends ApiController
{   
    public function getLocationItem(Request $request, $id){
        Log::info(__FUNCTION__ . " ItemController" );
        try{
            $item = LocationItem::where('item_id', $id)->where('location_id', $request->get('location_id'))->first();
            if($request->get('location_id') == "DEFAULT"){
                $i = Item::find($id);
                $item = new \stdClass();
                $item->reorder_point = $i->reorder_point;
                $item->order_up_to_level = $i->order_up_to;

            }

            return $this->json($request, $item, 200);
        }
        catch(\Exception $e) {
            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
            return $this->json($request, null, 500);
        }


    }

    public function saveControlStockData(Request $request, $id)
    {
        Log::info(__FUNCTION__ . " ItemController" );
        try{
            $item = Item::find($id);
            if($request->get("location_id") == "DEFAULT"){
                $item->reorder_point = $request->get('reorder_point');
                $item->order_up_to = $request->get('order_up_to');
                $item->save();
            }else{
                $location = LocationItem::where('item_id', $id)->where('location_id', $request->get('location_id'))->first();
                if($location == null){
                    $location = new LocationItem();
                    $location->item_id = $id;
                    $location->location_id = $request->get('location_id');
                }
                $location->order_up_to_level = $request->get('order_up_to');
                $location->reorder_point = $request->get('reorder_point');
                $location->save();
                return $this->json($request, $location, 200);
            }
            return $this->json($request, $item, 200);
        }
        catch(\Exception $e) {
            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
            return $this->json($request, null, 500);
        }

    }

    public function axiosItems(Request $request)
    {
        Log::info(__FUNCTION__ . " ItemController" );
        $data = new \stdClass();

        $filters = $request->input('filters');
        $data->recordsFiltered = 0;

        $datas = new Item();
        $data->recordsTotal = $datas->count();
        
        if($filters !== null) {
                       
            if($filters['name'] !== null) {

                $name = $filters['name'];

                $datas = $datas->where('name','LIKE',"%{$name}%");
                $datas = $datas->orWhere('custom_sku','=',"{$name}");
            }

        }

            $datas = $datas->offset(0)->limit(10);

        $data->items = $datas->get();
        //$data->recordsFiltered = count($data->items);

        foreach ($data->items as $key => $value) 
        {

            $data->items[$key]->sku_name = $value->custom_sku.'-'.$value->name;

            $img = File::where('object_id', $value->id)
                ->where('object_type', 'items')
                ->where('type', "IMG")
                ->first();
            if($img !== null)
            {
                $data->items[$key]->image_route = $img->filename;
            }
            else{
                $data->items[$key]->image_route = "";
            }

            $cat = Category::where('id', $value->category_id)->first();
            $data->items[$key]->category = "";

            if($cat !== null){
                $data->items[$key]->category = $cat->fullRoute();
            }

            $brand = Brand::where('id', $value->brand_id)->first();
            $data->items[$key]->brand = "";

            if($brand !== null){
                $data->items[$key]->brand = $brand->name;
            }

            $uom = UnitOfMeasure::where('id', $value->unit_of_measure_id)->first();
            $data->items[$key]->uom = "";

            if($uom !== null){
                $data->items[$key]->uom = $uom->name;
                $data->items[$key]->uom_plural = $uom->plural;
            }

            $item_type = ItemType::where('id', $value->item_type_id)->first();
            $data->items[$key]->item_type = "";

            if($item_type !== null){
                $data->items[$key]->item_type = $item_type->name;
            }

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

            $stock = LocationItem::where('item_id', $value->id)->get();
            if($stock !== null){
                $locitems = [];
                $total = 0;
                foreach ($stock as $key2 => $value2) {
                    $loc = Location::where('id', $value2->location_id)->first();

                    $datass = new \stdClass();
                    $datass->id = $value2->id;
                    $datass->location_id = $value2->location_id;
                    $datass->location = $loc->name;
                    $datass->amount = $value2->quantity;
                    $total += $value2->quantity;
                    array_push($locitems, $datass);
                }

                $datass = new \stdClass();
                $datass->id = null;
                $datass->location = "TOTAL";
                $datass->amount = $total;
                array_push($locitems, $datass);

                
                $data->items[$key]->stock = $locitems;
            }
            else{
                $datass = new \stdClass();
                $datass->id = null;
                $datass->location = "TOTAL";
                $datass->amount = "0";
                $data->items[$key]->stock = [$datass];
            }

            if($request->get('type') == "stock"){
                $pendingSales = SaleOrder::where('movement_status_id', 2)->pluck('id');
                $pendingPurchases = PurchaseOrder::where('movement_status_id', 2)->pluck('id');
                $salesItems = SaleOrderItem::whereIn('sale_order_id', $pendingSales)->where('item_id', $value->id)->first();
                $purchasesItems =  PurchaseOrderItem::whereIn('purchase_order_id', $pendingPurchases)->where('item_id', $value->id)->first();


                if($salesItems){

                    $uom_from = $salesItems->unit_of_measure_id;
                    $uom_to = $value->unit_of_measure_id;
                    if($uom_from != $uom_to){
                        $con = UnitOfMeasureConversion::where('uom_from_id', $uom_from)->where('uom_to_id', $uom_to)->first();
                        $data->items[$key]->stock_pending_sales = $con->factor*$salesItems->quantity;
                    }
                    else{
                        $data->items[$key]->stock_pending_sales = $salesItems->quantity;
                    }
                    
                }
                else{
                    $data->items[$key]->stock_pending_sales = 0;
                }

                if($purchasesItems){

                    $uom_from = $purchasesItems->unit_of_measure_id;
                    $uom_to = $value->unit_of_measure_id;
                    if($uom_from != $uom_to){
                        $con = UnitOfMeasureConversion::where('uom_from_id', $uom_from)->where('uom_to_id', $uom_to)->first();
                        $data->items[$key]->stock_pending_purchases = $con->factor*$purchasesItems->quantity;
                    }
                    else{
                        $data->items[$key]->stock_pending_purchases = $purchasesItems->quantity;
                    }
                    
                }
                else{
                    $data->items[$key]->stock_pending_purchases = 0;
                }
                
               
            }
            $itemPrice = ItemPrice::where('item_id',$value->id)->where('item_active',1)->get();
            $data->items[$key]->item_prices = $itemPrice;
                    
        }

        return $this->json($request, $data, 200);
    }

    public function index(Request $request)
    {
        Log::info(__FUNCTION__ . " ItemController" );

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


        $datas = new Item();
        $enterprise = $request->user()->enterprise;
        $datas = $datas->where('enterprise_id', $enterprise->id);
        $data->recordsTotal = $datas->count();
        
        if($start !== null && $length !== null) {

            if($filters !== null) {
                if($request->get("allsearch")==1){
                    if($filters['all'] !== null) {

                        $datas = $datas->where(function ($query) use ($filters){
                            $query->where('custom_sku','LIKE',"%{$filters['all']}%")
                                ->OrWhere('manufacturer_sku','LIKE',"%{$filters['all']}%")
                                ->OrWhere('ean','LIKE',"%{$filters['all']}%")
                                ->OrWhere('upc','LIKE',"%{$filters['all']}%")
                                ->OrWhere('name','LIKE',"%{$filters['all']}%")
                                ->OrWhere('id','LIKE',"%{$filters['all']}%");
                        });
                    }
                }
                elseif($request->get("allsearch")==2){

                    $locationItems = StockItem::distinct()->where('location_id', $request->get('location_id'))->pluck('item_id')->toArray();

                    $datas = $datas->whereIn('id', array_unique($locationItems));
                    $data->recordsTotal = $datas->count();
                    
                    if($filters['all'] !== null) {

                        $datas = $datas->where(function ($query) use ($filters){
                            $query->where('custom_sku','LIKE',"%{$filters['all']}%")
                            ->OrWhere('manufacturer_sku','LIKE',"%{$filters['all']}%")
                            ->OrWhere('ean','LIKE',"%{$filters['all']}%")
                            ->OrWhere('upc','LIKE',"%{$filters['all']}%")
                            ->OrWhere('name','LIKE',"%{$filters['all']}%")
                            ->OrWhere('id','LIKE',"%{$filters['all']}%");
                        });
                    }
                }
                else{
                    $stock_items = StockItem::distinct()
                        ->pluck('item_id')
                        ->toArray();

                    if($filters['name'] !== null) {
                        $datas = $datas->where('name','LIKE',"%{$filters['name']}%");
                    }

                    if($filters['code'] !== null) {
                        $datas = $datas->where(function ($query) use ($filters){
                            $query->where('custom_sku','LIKE',"%{$filters['code']}%")
                            ->OrWhere('manufacturer_sku','LIKE',"%{$filters['code']}%")
                            ->OrWhere('ean','LIKE',"%{$filters['code']}%")
                            ->OrWhere('upc','LIKE',"%{$filters['code']}%");
                        });
                    }
                    if($filters['category_id'] !== null) {
                        if($filters['category_id'] == -1){
                            $datas = $datas->where('category_id',null);
                        }else{
                            $datas = $datas->where('category_id',$filters['category_id']);
                        }    
                    }
                    if($filters['brand'] !== null) {
                        $brands = Brand::where('name', 'LIKE', "%{$filters['brand']}%")->pluck('id');
                        $datas = $datas->whereIn('brand_id',$brands);
                    }
                    if($filters['item_type'] !== null) {
                        $item_types = ItemType::where('id', $filters['item_type'])->pluck('id');
                        $datas = $datas->whereIn('item_type_id',$item_types);
                    }
                    if($filters['active_without_stock'] != 1) {
                        $datas->whereIn('id', $stock_items);
                    }
                }
                
            }

            if($order !== null) {
                $datas = $datas->orderBy($field, $dir);
            }

            $data->draw = $draw;
            $data->recordsFiltered = $datas->count();
            //return $this->json($request, $datas->get(), 200);
            $datas = $datas->offset($start)->limit($length);
        }


        if($request->get('with')== "availableBomItems"){
            $itemId = $request->get("item_id");
            $bomItems = BomItem::where('item_id', $itemId)->pluck('child_item_id');
            $datas = $datas->whereNotIn('id', $bomItems)->whereNotIn('id', [$itemId]);
        }
        if($data->recordsFiltered == 0) $data->recordsFiltered = $datas->count();
        
        $data->items = $datas->get();
        //$data->recordsFiltered = count($data->items);

        foreach ($data->items as $key => $value) 
        {
            $img = File::where('object_id', $value->id)
                ->where('object_type', 'items')
                ->where('type', "IMG")
                ->first();
            if($img!== null){
                $data->items[$key]->image_route = $img->filename;
            }
            else{
                $data->items[$key]->image_route = "";
            }

            $cat = Category::where('id', $value->category_id)->first();
            $data->items[$key]->category = "";
            if($cat!== null){
                $data->items[$key]->category = $cat->fullRoute();
            }

            $brand = Brand::where('id', $value->brand_id)->first();
            $data->items[$key]->brand = "";
            if($brand!== null){
                $data->items[$key]->brand = $brand->name;
            }

            $uom = UnitOfMeasure::where('id', $value->unit_of_measure_id)->first();
            $data->items[$key]->uom = "";
            if($uom!== null){
                $data->items[$key]->uom = $uom->name;
                $data->items[$key]->uom_plural = $uom->plural;
            }

            $item_type = ItemType::where('id', $value->item_type_id)->first();
            $data->items[$key]->item_type = "";
            if($item_type!== null){
                $data->items[$key]->item_type = $item_type->name;
            }

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
            $item_uom = Item::find($value->id)->unit_of_measure_id;
            if ($item_uom != 1){
                $stocks  = StockItem::where('item_id', $value->id)
                    ->join('locations','stock_items.location_id','=','locations.id')
                    ->select(DB::raw('locations.id, locations.name, sum(stock_items.qty) as stock'))
                    ->groupBy('locations.id')
                    ->get();
            }else{
                $stocks  = StockItem::where('item_id', $value->id)
                    ->join('locations','stock_items.location_id','=','locations.id')
                    ->select(DB::raw('locations.id, locations.name, count(*) as stock'))
                    ->groupBy('locations.id')
                    ->get();
            }

            if ($stocks) {
                $locitems = [];
                $total = 0;
                $fakeId = 1;
                foreach ($stocks as $stock) {
                    $datass = new \stdClass();
                    $datass->id = $fakeId;
                    $datass->location_id = $stock->id;
                    $datass->amount = round($stock->stock,2);
                    $datass->location = $stock->name;
                    $total += $stock->stock;
                    array_push($locitems, $datass);
                    $fakeId++;
                }

                $datass = new \stdClass();
                $datass->id = null;
                $datass->location = "TOTAL";
                $datass->amount = round($total,2);
                array_push($locitems, $datass);
                $data->items[$key]->stock = $locitems;
            }
            else {
                $datass = new \stdClass();
                $datass->id = null;
                $datass->location = "TOTAL";
                $datass->amount = "0";
                $data->items[$key]->stock = [$datass];
            }

            if($request->get('type') == "stock"){
                $pendingSales = SaleOrder::where('movement_status_id', 2)->pluck('id');
                $pendingPurchases = PurchaseOrder::where('movement_status_id', 2)->pluck('id');
                $salesItems = SaleOrderItem::whereIn('sale_order_id', $pendingSales)->where('item_id', $value->id)->first();
                $purchasesItems =  PurchaseOrderItem::whereIn('purchase_order_id', $pendingPurchases)->where('item_id', $value->id)->first();


                if($salesItems){

                    $uom_from = $salesItems->unit_of_measure_id;
                    $uom_to = $value->unit_of_measure_id;
                    if($uom_from != $uom_to){
                        $con = UnitOfMeasureConversion::where('uom_from_id', $uom_from)->where('uom_to_id', $uom_to)->first();
                        $data->items[$key]->stock_pending_sales = $con->factor*$salesItems->quantity;
                    }else{
                        $data->items[$key]->stock_pending_sales = $salesItems->quantity;
                    }
                    
                }
                else{
                    $data->items[$key]->stock_pending_sales = 0;
                }

                if($purchasesItems){

                    $uom_from = $purchasesItems->unit_of_measure_id;
                    $uom_to = $value->unit_of_measure_id;
                    if($uom_from != $uom_to){
                        $con = UnitOfMeasureConversion::where('uom_from_id', $uom_from)->where('uom_to_id', $uom_to)->first();
                        $data->items[$key]->stock_pending_purchases = $con->factor*$purchasesItems->quantity;
                    }else{
                        $data->items[$key]->stock_pending_purchases = $purchasesItems->quantity;
                    }
                    
                }
                else{
                    $data->items[$key]->stock_pending_purchases = 0;
                }
                
            }
                    
        }

        return $this->json($request, $data, 200);
    }

    public function store(Request $request)
    {
        Log::info(__FUNCTION__ . " ItemController" );
        Log::info(__FUNCTION__ . " request: " . json_encode($request) . "" );

        try {

            DB::beginTransaction();

            $this->_method = $request->method();

            $validator = Validator::make($request->all(),[
                'name' => 'required|max:150|string',
                'description' => 'max:250|string',
                'manufacturer_sku' => 'required|max:150|string'
            ]);
            if($validator->fails()){
                Log::info("message=[{$validator->errors()}]");
                $errors = ['errors' => $validator->errors()];
                return $this->json($request,$errors,200);
            }
            
            $sku = $request->get('manufacturer_sku');
            $checkSku = Item::where('manufacturer_sku', $sku)->orWhere('custom_sku', $sku)->first();
            if($checkSku !== null) {
                $errors = ['errors' => ['manufacturer_sku' => ['El Manufact. SKU ya es utilizado por otro item']]];
                return $this->json($request,$errors,200);
            }
            $sku = $request->get('custom_sku');
            $checkSku = Item::where('manufacturer_sku', $sku)->orWhere('custom_sku', $sku)->first();
            if(trim($sku) !== "" && $checkSku !== null) {
                $errors = ['errors' => ['manufacturer_sku' => ['El Custom SKU ya es utilizado por otro item']]];
                return $this->json($request,$errors,200);
            }

            if($request->has('stocks'))
            {
                $validator = Validator::make(json_decode($request->get('stocks'), true), [
                    '*.quantity'  => 'required|numeric|min:0',
                    '*.price'     => 'required|integer|between:1,999999',
                    '*.location'  => 'required|integer'
                ],
                [
                    '*.quantity.required'   => 'Es necesario especificar la candidad el item',
                    '*.quantity.numeric'    => 'La cantidad debe ser un valor numerico',
                    '*.quantity.min'        => 'La cantidad no puede ser menor a 0',
                    '*.price.required'   => 'Es necesario especificar el precio',
                    '*.price.integer'    => 'El precio debe ser un valor numerico',
                    '*.price.between'        => 'El precio no puede ser menor a 1 ni exceder 999.999',
                    '*.location.required'   => 'Es necesario especificar el almacen',
                    '*.location.integer'    => 'El id del almacen debe ser un valor numerico'
                ]);

                if($validator->fails()){
                    Log::info("message=[{$validator->errors()}]");
                    $errors = ['errors' => $validator->errors()];
                    return $this->json($request,$errors,200);
                }
            }

            if ($request->has('file') && $request->file('file') !== null) {
                
                $file = $request->file('file');
                $fileExtension = $file->getClientOriginalExtension();
                $validator = Validator::make(
                    [
                        'file' => $file,
                        'fileExtension' => $fileExtension
                    ], 
                    [
                        'file'  => 'required|image|mimes:jpg,jpeg,png',
                        'fileExtension' => ['required', Rule::NotIn(['jfif'])],
                    ],
                    [
                        'file.required' => 'Se debe indicar la imagen a subir',
                        'file.image'    => 'El archivo debe ser una imagen',
                        'file.mimes'    => 'El archivo debe estar en un formato valido. Formatos validos: [jpg, jpeg, png]',
                        'file.uploaded' => 'El archivo no se pudo subir, es posible que el archivo sea demasiado grande',
                        'fileExtension.required' => 'El archivo debe tener una extensión',
                        'fileExtension.not_in' => 'La extensión del archivo debe ser no puede ser jfif. Formatos validos: [jpg, jpeg, png]',
                    ]
                );

                if($validator->fails()){
                    Log::info("message=[{$validator->errors()}]");
                    $errors = ['errors' => $validator->errors()];
                    return $this->json($request,$errors,200);
                }
            }

            $user = User::find($request->user()->id);
            
            $data = new Item();
            $data->fill($request->all());
            $data->unit_of_measure_id = $request->get('unit_of_measure_id'); //UNIDAD

            Log::info(__FUNCTION__ . " data1: " . json_encode($data) . " " );
            
            if($request->get('brand_id')!=null){
                $brand = Brand::where('name', $request->get('brand_id'))->first();
                if($brand !=null){
                    $data->brand_id = $brand->id;
                }else{
                    $brand = new Brand();
                    $brand->name = strtoupper($request->get('brand_id'));
                    $brand->created_by = $user->id;
                    $brand->updated_by = $user->id;
                    $brand->enterprise_id = $user->enterprise->id;
                    $brand->save();
                    $data->brand_id = $brand->id;
                }
            }
            
            $data->block_discount = $request->get('block_discount');           
            $data->enterprise_id = $user->enterprise->id;
            $data->created_by = $user->id;
            $data->updated_by = $user->id;

            $data->save();

            $transact = new Transact();
            $transact->description = 'Se ha creado el producto';
            $transact->object_id = $data->id;
            $transact->object_type = "items";
            $transact->created_by = $request->user()->id;
            Log::info(__FUNCTION__ . " transact1: " . json_encode($transact) . " " );
            $transact->save();

            $cost_init = 0;
            if ($request->has('stocks')) {
                $stocksCost = json_decode($request->get('stocks'));
                $priceCost = $stocksCost[0]->price;
                $cost_init = $priceCost;
            }

            Log::info(__FUNCTION__ . " cost_init: " . json_encode($cost_init) . " " );

            $itemPrice = new ItemPrice();
            $itemPrice->item_id = $data->id;

            $price_type = PriceType::where('name','COMPRA')
                ->where('enterprise_id',$user->enterprise->id)->first()->id;

            Log::info(__FUNCTION__ . " price_type: " . json_encode($price_type) . " " );

            $itemPrice->price_type_id = $price_type;//COST
            $itemPrice->price = $cost_init;
            $itemPrice->created_by = $user->id;
            $itemPrice->updated_by = $user->id;
            $itemPrice->item_active = 1;

            Log::info(__FUNCTION__ . " itemPrice: " . json_encode($itemPrice) . " " );
            $itemPrice->save();

            if ($request->has('attributes')) {
                $attributes = explode(",", $request->get('attributes'));
                
                $count = 0;
                while ($count<count($attributes)) {
                    $id = $attributes[$count];
                    $val = $attributes[$count+1];
                    $itemAttrVal = new ItemAttributeValue();
                    $itemAttrVal->item_id = $data->id;
                    $itemAttrVal->attribute_id = $id;

                    $attr = Attribute::find($id);
                    if($attr->type == "float"){
                        $itemAttrVal->float_val = $val;
                    }else if($attr->type == "integer"){
                        $itemAttrVal->int_val = $val;
                    }else if($attr->type == "date"){
                        $itemAttrVal->date_val = $val;
                    }else{
                        $itemAttrVal->text_val = $val;
                    }
                    $itemAttrVal->created_by = $user->id;
                    $itemAttrVal->updated_by = $user->id;
                    $itemAttrVal->save();
                    $count = $count +2;
                }
                
            }

            if($request->get('prices')!=null){
                $prices = explode(",", $request->get('prices'));
                
                $count = 0;
                while ($count<count($prices)) {
                    $id = $prices[$count];
                    $val = $prices[$count+1];
                    $itemPrice = new ItemPrice();
                    $itemPrice->item_id = $data->id;
                    $itemPrice->price_type_id = $id;
                    $itemPrice->price = $val;

                 
                    $itemPrice->created_by = $user->id;
                    $itemPrice->updated_by = $user->id;
                    $itemPrice->save();
                    $count = $count +2;
                }
                
            }



            if ($request->has('stocks')) {
                $stocks = json_decode($request->get('stocks'));
                $adjustments = [];
                foreach ($stocks as $stock) {
                    // 1er foreach
                    $adjustments[$stock->location][] = [$stock->quantity, $stock->price];
                }
                foreach ($adjustments as $key=>$values) {
                    // 2do foreach
                    $adj = new Adjustment();
                    $adj->code = "ADJ". $key.$data->id.Carbon::now()->timestamp;
                    $adj->date = Carbon::now();
                    $adj->reason = "INVENTARIO INICIAL";
                    $adj->location_id = $key;
                    $adj->movement_status_id = 3;
                    $adj->enterprise_id = $user->enterprise->id;
                    $adj->created_by = $request->user()->id;
                    Log::info(__FUNCTION__ . " adj: " . json_encode($adj) . " " );
                    $adj->save();

                    foreach ($values as $value){
                        // 3er foreach 
                        $adjItem = new AdjustmentItem();
                        $adjItem->adjustment_id      = $adj->id;
                        $adjItem->item_id            = $data->id;
                        $adjItem->quantity           = $value[0];
                        $adjItem->unitary_price      = $value[1];
                        $adjItem->unit_of_measure_id = 1;
                        $adjItem->created_by = $request->user()->id;
                        Log::info(__FUNCTION__ . " adjItem: " . json_encode($adjItem) . " " );
                        $adjItem->save();

                        $transact = new Transact();
                        if ($value[0] == '1') {
                            $uom_name = UnitOfMeasure::find($request->get('unit_of_measure_id'))->first()->name;
                            $transact->description = 'Se agregó <b>' . $value[0] . ' ' . $uom_name . '</b> al momento de su creación';
                        }else{
                            $uom_name = UnitOfMeasure::find($request->get('unit_of_measure_id'))->first()->plural;
                            $transact->description = 'Se agregaron <b>' . $value[0] . ' ' . $uom_name . '</b> al momento de su creación';
                        }
                        $transact->object_id = $data->id;
                        $transact->object_type = "items";
                        $transact->created_by = $request->user()->id;
                        Log::info(__FUNCTION__ . " transact2: " . json_encode($transact) . " " );
                        $transact->save();

                        if($request->get('unit_of_measure_id') != 1){
                            $stockItem = new StockItem();
                            $stockItem->item_id     = $data->id;
                            $stockItem->qty         = $value[0];
                            $stockItem->price       = $value[1];
                            $stockItem->location_id = $key;
                            $stockItem->adjustment_item_id = $adjItem->id;
                            Log::info(__FUNCTION__ . " stockItem[IF]: " . json_encode($stockItem) . " " );
                            $stockItem->save();
                        }else{
                            for ($x = 0; $x < $value[0]; $x++) {
                                $stockItem = new StockItem();
                                $stockItem->item_id     = $data->id;
                                $stockItem->price       = $value[1];
                                $stockItem->location_id = $key;
                                $stockItem->adjustment_item_id = $adjItem->id;
                                Log::info(__FUNCTION__ . " stockItem[ELSE]: " . json_encode($stockItem) . " " );
                                $stockItem->save();
                            }
                        }

                    }// fin 3er foreach
                }// fin 2do foreach
            }//fin if ($request->has('stocks'))

            Log::info(__FUNCTION__ . " location_items " );
            if($request->get('location_items')!=null){
                $location_items = explode(",", $request->get('location_items'));
                $item_id = $data->id;

                $count = 0;
                while ($count<count($location_items)) {
                    $location_id = $location_items[$count];
                    $quantity = $location_items[$count+1];
                    $unit_of_measure_id = $location_items[$count+2];
                    

                    $adj = new Adjustment();
                    $adj->code = "ADJ". $location_id.$data->id.Carbon::now()->timestamp;
                    $adj->date = Carbon::now();
                    $adj->reason = "INVENTARIO INICIAL";
                    $adj->location_id = $location_id;
                    $adj->movement_status_id = 3;
                    $adj->enterprise_id = $user->enterprise->id;
                    $adj->created_by = $request->user()->id;
                    $adj->save();


                    $adjItem = new AdjustmentItem();
                    $adjItem->adjustment_id = $adj->id;
                    $adjItem->item_id = $item_id;
                    $adjItem->quantity = $quantity;
                    $adjItem->unit_of_measure_id = $unit_of_measure_id;
                    $adjItem->created_by = $request->user()->id;
                    $adjItem->save();

                    $locationItem = LocationItem::where('location_id', $location_id)->where('item_id', $item_id)->first();

                    $uom_from = $unit_of_measure_id;
                    $uom_to = $data->unit_of_measure_id;

                    if($locationItem !== null){
                        $locationItem = new LocationItem();
                        $locationItem->item_id = $item_id;
                        $locationItem->location_id = $location_id;
                        //convertir uom
                        if($uom_from != $uom_to){
                            $con = UnitOfMeasureConversion::where('uom_from_id', $uom_from)->where('uom_to_id', $uom_to)->first();
                            $locationItem->quantity = $con->factor*$quantity;
                        }else{
                            $locationItem->quantity = $quantity;
                        }
                        
                    }
                    $locationItem->created_by = $request->user()->id;
                    $locationItem->save();

                    $count = $count +3;

                }

            }

            ## IMAGENES 
            if ($request->file('file')!==null) {
                $fileImg = new File();
                $fileImg->description = "item_image";
                $fileImg->object_id = $data->id;
                $fileImg->object_type = "items";
                $fileImg->type = "IMG";

                $file = $request->file('file');

                $fileRoute = str_replace(' ','_', $file->getClientOriginalName());
                $filename = time(). "_" . $fileRoute;

                $fileImg->name = $filename;

                $folder = public_path('uploads/items/');
                if (!file_exists($folder)) {
                    mkdir($folder, 0777, true);
                }


                Image::make($file)->save( public_path('uploads/items/' . $filename ));
                
                $fileImg->route = 'uploads/items';

                $fileImg->filename = $filename;
                $fileImg->enterprise_id            = $user->enterprise->id;
                $fileImg->created_by               = $request->user()->id;
                $fileImg->updated_by               = $request->user()->id;
                $fileImg->save();
                
            }

            Log::info(__FUNCTION__ . " AHORA commit a BD y FIN" );

            DB::commit();
            return $this->json($request, $data, 201);

        } catch(\Exception $e) {
            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
            DB::rollback();
            return $this->json($request, null, 500);
        }
    }


    public function show(Request $request, $id)
    {
        Log::info(__FUNCTION__ . " ItemController" );
        try {
            $datas = new \stdClass();
            $data = Item::find($id);
            if($data === null) {
                return $this->json($request, $data, 404);
            }
            $datas->item = $data;

            $brand = Brand::where('id', $data->brand_id)->first();
            $datas->item->brand = "";
            if($brand !== null){
                 $datas->item->brand = $brand->name;
            }

            // Log::info(__FUNCTION__ . " datas1: " . json_encode($datas) );

            $datas->prices = ItemPrice::where('item_id', $id)->get();

            $imgs = File::where('object_id', $id)
                ->where('object_type', 'items')
                ->where('type', "IMG")
                ->get();

            $attrValues = ItemAttributeValue::where('item_id', $id)->get();
            $attributes = [];
            foreach ($attrValues as $key2 => $value2) {
                $att = new \stdClass();
                $att->id = $value2->id;

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
            $datas->item->attributes = $attributes;

            // Log::info(__FUNCTION__ . " datas2: " . json_encode($datas) );

            $bom = BomItem::where('item_id', $id)->get();

            $bomItems = [];
            foreach ($bom as $key => $value) {
                $i = Item::find($bom[$key]->child_item_id);
                array_push($bomItems, $i);
            }

            $bom_items = new \stdClass();
            $bom_items->items = $bomItems;
            $bom_items->bom = $bom;

            if($request->get('with')=="availableBomItems"){
                $itemsIds = [];
                $bItemsIds = BomItem::where('item_id', $id)->pluck('child_item_id');
                foreach ($bItemsIds as $key => $value) {
                    array_push($itemsIds, $value);
                }
                
                array_push($itemsIds, $id);

                $user = User::find($request->user()->id);

                $items = Item::where('enterprise_id', $user->enterprise->id)->whereNotIn('id', $itemsIds)->get(); 
                $bom_items->available_items = $items;
                $bom_items->availableTotal = count($items); 
                $bom_items->availableFiltered = count($items); 
            }

            $bom_items->recordsTotal = count($bom);
            $bom_items->recordsFiltered = count($bom);
            $datas->images = $imgs;
            $datas->bom_items = $bom_items;

            // Log::info(__FUNCTION__ . " datas3: " . json_encode($datas) );

            return $this->json($request, $datas, 200);

        } catch(\Exception $e) {
            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
            return $this->json($request, null, 500);
        }
    }

    public function stock(Request $request, $id)
    {
        Log::info(__FUNCTION__ . " ItemController" );
        DB::enableQueryLog();
        try {

            $datas = new \stdClass();
            $data = Item::find($id);
            
            $item_uom = Item::find($id)->unit_of_measure_id;
            if($data === null) {
                return $this->json($request, $data, 404);
            }

            $uom = UnitOfMeasure::find($item_uom);
            $u   = $uom->plural;
            $fakeId = 1;

            if ($item_uom != 1){
                $locations  = StockItem::where('item_id', $id)
                    ->join('locations','stock_items.location_id','=','locations.id')
                    ->select(DB::raw('locations.id, locations.name, sum(stock_items.qty) as stock'))
                    ->groupBy('locations.id')
                    ->get();
            }
            else{
                $locations  = StockItem::where('item_id', $id)
                    ->join('locations','stock_items.location_id','=','locations.id')
                    ->select(DB::raw('locations.id, locations.name, count(*) as stock'))
                    ->groupBy('locations.id')
                    ->get();
            }

            $qrys = DB::getQueryLog();
            ## ULTIMA QUERY EJECUTADA 
            Log::info(__FUNCTION__ . " query: " . json_encode(end($qrys)) );

            if ($locations->isNotEmpty()) {
                $total = 0;
                $locitems = [];
                foreach ($locations as $location) {
                    $datass = new \stdClass();
                    $datass->id = $fakeId;
                    $datass->location = $location->name;
                    $datass->location_id = $location->id;
                    $datass->amount = round($location->stock,2);
                    $datass->uom_plural = $u;
                    $total += $location->stock;
                    array_push($locitems, $datass);
                    $fakeId++;
                }

                $datass = new \stdClass();
                $datass->id = null;
                $datass->location = "TOTAL";
                $datass->amount = $total;
                $datass->uom_plural = $u;
                array_push($locitems, $datass);

                
                $datas->stock = $locitems;
                $datas->recordsFiltered = count($locitems);
                $datas->recordsTotal = count($locitems);

            }
            else
            {
                $datass = new \stdClass();
                $datass->id = null;
                $datass->location = "TOTAL";
                $datass->amount = "0";
                $datass->uom_plural = $u;
                $datas->stock = [$datass];
                $datas->recordsFiltered = count($datass);
                $datas->recordsTotal = count($datass);
            }

            Log::info(__FUNCTION__ . " datass: " . json_encode($datass) );
            
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
    public function bomitems(Request $request, $id)
    {
        Log::info(__FUNCTION__ . " ItemController" );
        try {
            $datas = new \stdClass();
            $data = Item::find($id);
            if($data === null) {
                return $this->json($request, $data, 404);
            }

            $itemsIds = BomItem::where('item_id', $id)->pluck('child_item_id');
            $items = Item::whereIn('id',$itemsIds)->get();
            $bomItems = BomItem::where('item_id', $id)->get();
            $datas->bom_items = $items;
            
            foreach ($datas->bom_items as $key => $value){
                $datas->bom_items[$key]->bom_item_id = $bomItems[$key]->id;
                $datas->bom_items[$key]->quantity = $bomItems[$key]->amount;
                $datas->bom_items[$key]->unit_of_measure_id = $bomItems[$key]->unit_of_measure_id;

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

                
                $datas->bom_items[$key]->attributes = $attributes;

                $img = File::where('object_id', $value->id)
                            ->where('object_type', 'items')
                            ->where('type', "IMG")
                            ->first();
                if($img !== null)
                {
                    $datas->bom_items[$key]->image_route = $img->filename;
                }
                else{
                    $datas->bom_items[$key]->image_route = "";
                }

                $cat = Category::where('id', $value->category_id)->first();
                $datas->bom_items[$key]->category = "";
                if($cat !== null){
                    $datas->bom_items[$key]->category = $cat->fullRoute();
                }
                
            }

            $datas->recordsTotal = count($datas->bom_items);
            $datas->recordsFiltered = count($datas->bom_items);
        
            
            return $this->json($request, $datas, 200);

        }
        catch(\Exception $e) {
            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
            return $this->json($request, null, 500);
        }
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
        Log::info(__FUNCTION__ . " ItemController" );
        try{


            $this->_method = $request->method();
            $this->_id     = $id;
            
            $data = Item::find($id);
            
            if ($data === null)
            {
                return $this->json($request, $data, 404);
            }
            
            $validator = Validator::make($request->all(), $this->rules()); 
            
            if ($validator->fails())
            {
                Log::info("message=[{$validator->errors()}]");
                $errors = ['errors' => $validator->errors()];
                return $this->json($request,$errors,200);
            }
            $user = $request->user();
            
            $sku = $request->get('manufacturer_sku');
            $checkSku = Item::where('manufacturer_sku', $sku)->orWhere('custom_sku', $sku)->first();
            if($checkSku !== null && $data->id !== $checkSku->id) {
                $errors = ['errors' => ['manufacturer_sku' => ['El Manufact. SKU ya es utilizado por otro item']]];
                return $this->json($request,$errors,200);
            }
            $sku = $request->get('custom_sku');
            $checkSku = Item::where('manufacturer_sku', $sku)->orWhere('custom_sku', $sku)->first();
            if(trim($sku) !== "" && $checkSku !== null && $data->id !== $checkSku->id) {
                $errors = ['errors' => ['manufacturer_sku' => ['El Custom SKU ya es utilizado por otro item']]];
                return $this->json($request,$errors,200);
            }

            $data->fill($request->all());

            if($request->get('brand')!=null){
                $brand = Brand::where('name', $request->get('brand'))->first();
                if($brand !=null){
                    $data->brand_id = $brand->id;
                }else{
                    $brand = new Brand();
                    $brand->name = strtoupper($request->get('brand'));
                    $brand->created_by = $user->id;
                    $brand->updated_by = $user->id;
                    $brand->enterprise_id = $user->enterprise->id;
                    $brand->save();
                    $data->brand_id = $brand->id;
                }
            }
            Log::info($request->all());

            if($request->get('attributes')!=null){

                $attrValues = ItemAttributeValue::where('item_id', $id)->get();
                $attributes = [];

                $postAttributes = explode(",", $request->get('attributes'));
                Log::info($postAttributes);

                for ($i=0; $i < count($postAttributes)/2; $i++) { 
                    $attrValue = ItemAttributeValue::where('item_id', $id)->where('attribute_id', $postAttributes[$i*2])->first();

                    if($attrValue!=null){
                        $attribute = Attribute::where('id', $attrValue->attribute_id)->first();
                        $type = $attribute->type;
                        
                        if($type == "text"){
                            $attrValue->text_val = $postAttributes[$i*2+1];
                        }elseif ($type == "date") {
                            $attrValue->date_val = $postAttributes[$i*2+1];
                        }elseif ($type == "int") {
                            $attrValue->int_val = $postAttributes[$i*2+1];
                        }elseif ($type == "float") {
                           $attrValue->float_val = $postAttributes[$i*2+1];
                        }
                        $attrValue->updated_by = $user->id;
                        $attrValue->save();
                        
                    }else{
                        $attrValue = new ItemAttributeValue();
                        $attrValue->item_id = $id;
                        $attrValue->attribute_id =  $postAttributes[$i*2];

                        $attribute = Attribute::where('id', $attrValue->attribute_id)->first();
                        $type = $attribute->type;
                        if($type == "text"){
                            $attrValue->text_val = $postAttributes[$i*2+1];
                        }elseif ($type == "date") {
                            $attrValue->date_val = $postAttributes[$i*2+1];
                        }elseif ($type == "int") {
                            $attrValue->int_val = $postAttributes[$i*2+1];
                        }elseif ($type == "float") {
                           $attrValue->float_val = $postAttributes[$i*2+1];
                        }
                        $attrValue->created_by = $user->id;
                        $attrValue->updated_by = $user->id;
                        $attrValue->save();
                    }
                    
                }
                return $this->json($request, $postAttributes, 200);

                $brand = Brand::where('name', $request->get('brand'))->first();
                if($brand !=null){
                    $data->brand_id = $brand->id;
                }else{
                    $brand = new Brand();
                    $brand->name = strtoupper($request->get('brand'));
                    $brand->created_by = $user->id;
                    $brand->updated_by = $user->id;
                    $brand->enterprise_id = $user->enterprise->id;
                    $brand->save();
                    $data->brand_id = $brand->id;
                }
            }


            if($request->get('prices')!=null){
                $prices = explode(",", $request->get('prices'));
                
                $count = 0;
                while ($count<count($prices)) {
                    $idItemPrice = $prices[$count];
                    $val = $prices[$count+1];

                    $itemPrice = ItemPrice::where('item_id', $id)->where('price_type_id', $idItemPrice)->first();

                    if($itemPrice != []){
                        $itemPrice->price = $val;
                        if($itemPrice->id == $request->get('item_active')){
                            $itemPrice->item_active = 1;
                            ItemPrice::where('item_id', $id)->where('price_type_id', 4)->update(['item_active' => 0]);
                        }else{
                            $itemPrice->item_active = 0;
                            }
                    }else{
                        $itemPrice = new ItemPrice();
                        $itemPrice->item_id = $id;
                        $itemPrice->price_type_id = $idItemPrice;
                        $itemPrice->price = $val;
                    }

                    

                    $itemPrice->created_by = $user->id;
                    $itemPrice->updated_by = $user->id;
                    $itemPrice->save();
                    $count = $count +2;
                }
                
            }

            $isMod = Item::find($id)->manufacturer_sku != $data->manufacturer_sku;
            
            if ($isMod){
                $transact = new Transact();
                $transact->description = 'Se ha modificado el manufacturer sku de <b>"' . Item::find($id)->manufacturer_sku . '"</b> a <b>"' .$data->manufacturer_sku .'"</b>';
                $transact->object_id = $data->id;
                $transact->object_type = "items";
                $transact->created_by = $request->user()->id;
                $transact->save();
            }

            $isMod = Item::find($id)->name != $data->name;

            if ($isMod){
                $transact = new Transact();
                $transact->description = 'Se ha modificado el nombre de <b>"' . Item::find($id)->name . "'</b> a <b>'" .$data->name .'"</b>';
                $transact->object_id = $data->id;
                $transact->object_type = "items";
                $transact->created_by = $request->user()->id;
                $transact->save();
            }

            $isMod = Item::find($id)->custom_sku != $data->custom_sku;

            if ($isMod){
                $transact = new Transact();
                $transact->description = 'Se ha modificado el custom sku de <b>"' . Item::find($id)->custom_sku . '"</b> a <b>"' .$data->custom_sku .'"</b>';
                $transact->object_id = $data->id;
                $transact->object_type = "items";
                $transact->created_by = $request->user()->id;
                $transact->save();
            }

            $isMod = Item::find($id)->description != $data->description;

            if ($isMod){
                $transact = new Transact();
                $transact->description = 'Se ha modificado la descriptción de <b>"' . Item::find($id)->description . '"</b> a </b>"' .$data->description .'"</b>';
                $transact->object_id = $data->id;
                $transact->object_type = "items";
                $transact->created_by = $request->user()->id;
                $transact->save();
            }

            $data->updated_at       = Carbon::now();
            $data->updated_by       = $request->user()->id;
            $data->block_discount   = $request->block_discount;
            $data->active_without_stock   = $request->active_without_stock;
            $data->save();

            
            


            return $this->json($request, $data, 200);
        }
        catch(\Exception $e) 
        {
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
        Log::info(__FUNCTION__ . " ItemController" );
        try {
            $data = Item::find($id);
            if($data === null){
                return $this->json($request, null, 404);
            }



            $purchasesItems = PurchaseOrderItem::where('item_id', $id)->get();
            if(count($purchasesItems) > 0){
                $data = new \stdClass();
                $errors = "El Producto forma parte de al menos una Orden de Entrada. No puede ser eliminado.";
                $data->errors = $errors;
                return $this->json($request, $data, 200);
            }

            $saleItems = SaleOrderItem::where('item_id', $id)->get();
            if(count($saleItems) > 0){
                $data = new \stdClass();
                $errors = "El Producto forma parte de al menos una Orden de Salida. No puede ser eliminado.";
                $data->errors = $errors;
                return $this->json($request, $data, 200);
            }

            $adjustmentItems = AdjustmentItem::where('item_id', $id)->get();
            if(count($adjustmentItems) > 0){
                $data = new \stdClass();
                $errors = "El Producto forma parte de al menos un Ajuste de Inventario. No puede ser eliminado.";
                $data->errors = $errors;
                return $this->json($request, $data, 200);
            }

            $transferItems = TransferItem::where('item_id', $id)->get();
            if(count($transferItems) > 0){
                $data = new \stdClass();
                $errors = "El Producto forma parte de al menos una Transferencia. No puede ser eliminado.";
                $data->errors = $errors;
                return $this->json($request, $data, 200);
            }

            $data->deleted_at = Carbon::now();
            $data->save();
            return $this->json($request, $data, 200);

        } catch(\Exception $e) {
            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
            return $this->json($request, null, 500);
        }
    }


    /* Import products of with Digikey Format */
    public function postMassive(Request $request)
    {
        Log::info(__FUNCTION__ . " ItemController" );
        try
        {
            $file = $request->file('file');
            if ($file===null){
                $errors = ['errors' => ['file' => ['El campo archivo es obligatorio']]];
                return $this->json($request,$errors,400);
            }
            

            DB::beginTransaction();
            $datas = "";
            $row = 1;
            $items = [];
            if (($handle = fopen($file, "r")) !== FALSE) {
              while (($data = fgetcsv($handle, 1000, ",", '"')) !== FALSE) {
                $num = count($data);
                $row++;
                $item = [];
                for ($c=0; $c < $num; $c++) {
                    $item[] = $data[$c];
                }
                $items[] = $item;
              }

              fclose($handle);
            }
            $user = $request->user();

            foreach ($items as $key => $csvItem) {
                if($key != 0){

                    $brand = Brand::where('enterprise_id', $user->enterprise->id)->where('name', $csvItem[4])->first();
                    if($brand == null){
                        $brand = new Brand();
                        $brand->name = $csvItem[4];
                        $brand->enterprise_id = $user->enterprise->id;
                        $brand->created_by = $user->id;
                        $brand->save();
                    }



                    $i = new Item();
                    $i->custom_sku = $csvItem[2];
                    $i->manufacturer_sku = $csvItem[3];
                    $i->description = $csvItem[4];
                    $i->name = $csvItem[5];
                    $i->reorder_point = 0;
                    $i->order_up_to = 0;
                    $i->brand_id = $brand->id;
                    $i->unit_of_measure_id = 1;
                    $i->item_type_id = 1;
                    $i->category_id = $request->get('category_id');
                    $i->enterprise_id = $user->enterprise->id;
                    $i->created_by = $user->id;
                    $i->updated_by = $user->id;
                    $i->save();

                    /*SAVE CUSTOM ATRIBUTES*/

                    $customAttributesIds = [0];

                    for ($j=11; $j < count($items[0]) ; $j++) { 
                        $customAttributesIds[] = $j;
                    }
                    

                    foreach ($customAttributesIds as $cAttrId) {
                        if(isset($items[0][$cAttrId]) && isset($csvItem[$cAttrId]) ){
                            $itemAttr = Attribute::where('enterprise_id', $user->enterprise->id)->where('name', $items[0][$cAttrId])->first();
                            if($itemAttr == null){
                                $itemAttr = new Attribute();
                                $itemAttr->name = $items[0][$cAttrId];
                                $itemAttr->type = "text";
                                if($cAttrId == 0 ){
                                    $itemAttr->type = "url";
                                }
                                
                                $itemAttr->enterprise_id = $user->enterprise->id;
                                $itemAttr->created_by = $user->id;
                                $itemAttr->save();
                            }

                            if(trim($csvItem[$cAttrId]) != "-" ){
                                $itemAttrVal = new ItemAttributeValue();
                                $itemAttrVal->item_id = $i->id;
                                $itemAttrVal->attribute_id = $itemAttr->id;
                                $itemAttrVal->text_val = $csvItem[$cAttrId];   
                                $itemAttrVal->created_by = $user->id;
                                $itemAttrVal->updated_by = $user->id;
                                $itemAttrVal->save();
                            }

                            
                        }
                        
                    }
                    if(is_numeric($csvItem[8]) ){
                        $price = new ItemPrice();
                        $price->item_id = $i->id;
                        $price->price_type_id = 1;
                        $price->price = $csvItem[8];
                        $price->created_by = $user->id;
                        $price->updated_by = $user->id;
                        $price->save();
                    }
                    


                    $fileImg = new File();
                    $fileImg->description = "item_image";
                    $fileImg->object_id = $i->id;
                    $fileImg->object_type = "items";
                    $fileImg->type = "IMG";

                    $file = file_get_contents("http:".$csvItem[1]);
                    $ext = pathinfo("http:".$csvItem[1], PATHINFO_EXTENSION);

                    $fileRoute = random_int(1000000, 9999999).".".$ext;
                    $filename = time(). "_" . $fileRoute;

                    $fileImg->name = $filename;

                    Image::make($file)->save( public_path('uploads/items/' . $filename ));
                    
                    $fileImg->route = 'uploads/items';

                    $fileImg->filename = $filename;
                    $fileImg->enterprise_id            = $user->enterprise->id;
                    $fileImg->created_by               = $request->user()->id;
                    $fileImg->updated_by               = $request->user()->id;
                    $fileImg->save();
                    
                
                }
            }
                        
     

            DB::commit();
            
            return $this->json($request, $items, 200);

        }
        catch(\Exception $e)
        {
            DB::rollback();
            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");

            $error = new \stdClass();
            $error->code = $e->getCode();
            $error->message = $e->getMessage();
            $error->file = $e->getFile();
            $error->line = $e->getLine();

            $response = new \stdClass();
            $response->errors = ["message" => "La operación no pudo ser realizada. Intente nuevamente más tarde. Si el problema persiste, contacte al administrador."];
            
            $response->error = $error;

            return $this->json($request, $response, 500);
        }
    }

    /* Import products of with Shoes Example Format */
    public function postShoes(Request $request)
    {
        Log::info(__FUNCTION__ . " ItemController" );
        try
        {
            $file = $request->file('file');
            if ($file===null){
                $errors = ['errors' => ['file' => ['El campo archivo es obligatorio']]];
                return $this->json($request,$errors,400);
            }
            

            
            $datas = "";
            $row = 1;
            $items = [];
            /* Read CSV */
            if (($handle = fopen($file, "r")) !== FALSE) {
              while (($data = fgetcsv($handle, 1000, ",", '"')) !== FALSE) {
                $num = count($data);
                $row++;
                $item = [];
                for ($c=0; $c < $num; $c++) {
                    $item[] = $data[$c];
                }
                $items[] = $item;
              }

              fclose($handle);
            }
            $user = $request->user();
            //return $this->json($request, $items, 200);
            /* Loop throught items in csv */
            foreach ($items as $key => $csvItem) {
                if($key != 0 && $key != 1 && $key != 23 && $key != 24 && $key != 26 && $key != 28 && $key != 29 &&   $key != 33 && $key != 34){
                    DB::beginTransaction();
                    $brand = Brand::where('enterprise_id', $user->enterprise->id)->where('name', $csvItem[4])->first();
                    if($brand == null){
                        $brand = new Brand();
                        $brand->name = $csvItem[4];
                        $brand->enterprise_id = $user->enterprise->id;
                        $brand->created_by = $user->id;
                        $brand->save();
                    }

                    Log::info("....");
                    Log::info($key ." <--- KEY");

                    $i = new Item();
                    $i->custom_sku = $csvItem[0];//ok
                    $i->manufacturer_sku = $csvItem[13];//ok
                    $i->ean = $csvItem[9];//ok
                    if(isset($csvItem[32])){
                        $i->upc = $csvItem[32];//ok
                    }
                    
                    $i->description = "";
                    $i->name = $csvItem[14];//ok
                    $i->reorder_point = 0;
                    $i->order_up_to = 0;
                    $i->brand_id = $brand->id;
                    $i->unit_of_measure_id = 1;
                    $i->item_type_id = 1;
                    $i->enterprise_id = $user->enterprise->id;
                    $i->created_by = $user->id;
                    $i->updated_by = $user->id;


                    
                    $cat = Category::where('enterprise_id', $user->enterprise_id)->where('name', $csvItem[6])->first();
                    if($cat == null){
                        $cat = new Category();
                        $cat->name = $csvItem[6];
                        $cat->enterprise_id = $user->enterprise_id;
                        $cat->created_by = $user->id;
                        $cat->save();
                    }
                    
                    $i->category_id = $cat->id;
                    $i->save();
                    
                    

                    /*SAVE CUSTOM ATRIBUTES*/

                    $customAttributesIds = [5, 7, 8, 30];
                    foreach ($customAttributesIds as $cAttrId) {
                        if(isset($items[0][$cAttrId]) && isset($csvItem[$cAttrId]) ){
                            $itemAttr = Attribute::where('enterprise_id', $user->enterprise->id)->where('name', $items[0][$cAttrId])->first();
                            if($itemAttr == null){
                                $itemAttr = new Attribute();
                                $itemAttr->name = $items[0][$cAttrId];
                                $itemAttr->type = "text";
                                
                                $itemAttr->enterprise_id = $user->enterprise->id;
                                $itemAttr->created_by = $user->id;
                                $itemAttr->save();
                            }

                            if(trim($csvItem[$cAttrId]) != "-" && trim($csvItem[$cAttrId]) != ""){
                                $itemAttrVal = new ItemAttributeValue();
                                $itemAttrVal->item_id = $i->id;
                                $itemAttrVal->attribute_id = $itemAttr->id;
                                $itemAttrVal->text_val = $csvItem[$cAttrId];   
                                $itemAttrVal->created_by = $user->id;
                                $itemAttrVal->updated_by = $user->id;
                                $itemAttrVal->save();
                            }

                            
                        }
                        
                    }

                    /* Set prices */

                    $prices = [15, 16];

                    foreach ($prices as $key3 => $price) {
                        if ($price == 15){
                            $priceType = PriceType::where('enterprise_id', $user->enterprise_id)
                                        ->where('name', "MAX")->first();
                        }else{
                            $priceType = PriceType::where('enterprise_id', $user->enterprise_id)
                                        ->where('name', "MIN")->first();
                        }
                    

                        if($priceType == null && is_numeric($csvItem[$price]) ){
                            $priceType = new PriceType();
                            if ($price == 15){
                                $priceType->name = "MAX";
                            }else{
                                $priceType->name = "MIN";
                            }
                            
                            $priceType->enterprise_id = $user->enterprise_id;
                            $priceType->created_by = $user->id;
                            $priceType->save();
                        }

                        if( is_numeric($csvItem[$price]) ){
                            $itemPrice = new ItemPrice();
                            $itemPrice->item_id = $i->id;
                            $itemPrice->price_type_id = $priceType->id;
                            $itemPrice->price = $csvItem[$price];
                            $itemPrice->created_by = $user->id;
                            $itemPrice->updated_by = $user->id;
                            $itemPrice->save();
                        }
                    
                    
                    }




                    /* Get array of pictures from csv */
                    $images = explode(",", $csvItem[10], 8);
                    //return $this->json($request, $images, 200);
                    foreach ($images as $key2 => $image) {

                        //return $this->json($request, $image, 200);
                        Log::info($key2." -- img");
                        Log::info("cutting image link: ");
                        Log::info($image);
                         /* fix ext dumb data */
                        $arr = explode("?", $image, 2);
                        $image = $arr[0];
                        $arr = explode("%", $image, 2);
                        $image = $arr[0];
                        Log::info($image);

                        /* Ignore file_get_contents errors */
                        $context = stream_context_create(array(
                            'http' => array('ignore_errors' => true),
                        ));

                        if($file = file_get_contents($image, false, $context)){
                            $ext = pathinfo($image, PATHINFO_EXTENSION);

                            /* fix ext dumb data */
                            $arr = explode("?", $ext, 2);
                            $ext = $arr[0];
                            $arr = explode("%", $ext, 2);
                            $ext = $arr[0];

                            /* Save image on server */
                            $fileRoute = random_int(1000000, 9999999).".".$ext;
                            $filename = time(). "_" . $fileRoute;
                            Image::make($file)->save( public_path('uploads/items/' . $filename ));

                            /* Save on DB */
                            $fileImg = new File();
                            $fileImg->description = "item_image";
                            $fileImg->object_id = $i->id;
                            $fileImg->object_type = "items";
                            $fileImg->type = "IMG";
                            $fileImg->name = $filename;
                            $fileImg->route = 'uploads/items';
                            $fileImg->filename = $filename;
                            $fileImg->enterprise_id            = $user->enterprise->id;
                            $fileImg->created_by               = $request->user()->id;
                            $fileImg->updated_by               = $request->user()->id;
                            $fileImg->save();
                        }  
                    }             
                }
                DB::commit();
            }
            

            return $this->json($request, $items, 200);
        }
        catch(\Exception $e)
        {
            DB::rollback();
            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");

            $error = new \stdClass();
            $error->code = $e->getCode();
            $error->message = $e->getMessage();
            $error->file = $e->getFile();
            $error->line = $e->getLine();

            $response = new \stdClass();
            $response->errors = ["message" => "La operación no pudo ser realizada. Intente nuevamente más tarde. Si el problema persiste, contacte al administrador."];
            
            $response->error = $error;

            return $this->json($request, $response, 500);
        }
    }
    private function rules()
    {
        Log::info(__FUNCTION__ . " ItemController" );
        switch ($this->_method)
        {
            case 'POST':
                return [
                    'name' => 'required|max:150|string',
                    'description' => 'max:250|string',
                    'manufacturer_sku' => 'required|max:150|string'
                ];
                break;

            case 'PUT':
                return [
                    
                ];
                break;
        }
    }
    public function search(Request $request) {
        Log::info(__FUNCTION__ . " ItemController" );
        $search = $request->input('value');
        $type   = $request->input('type');
        $itemQty = Config::where('param','PRESALE_POS_ITEM_QUANTITY')->first()->value;

        $name  = $request->input('value') == '' ? null : $request->input('value');


        if ($type == 'desc') {


            $itemName = "@var1 := 0,";
            $searchFilters = explode(" ",$name);
            if ($request->input('value') !== null) {
                $itemName .= "@var1 := (case when locate('".$searchFilters[0]."',concat(items.name,(case when brands.name is null then '' else brands.name end),(case when items.custom_sku is null then '' else items.custom_sku end),(case when item_prices.price is null then '' else item_prices.price end))) != 0 then 1 else 0 end),";
                foreach ($searchFilters as $key => $str){
                    if ($key != 0) {
                        $itemName .= "@var1 := @var1+(case when locate('".$str."',concat(items.name,(case when brands.name is null then '' else brands.name end),(case when items.custom_sku is null then '' else items.custom_sku end),(case when item_prices.price is null then '' else item_prices.price end))) != 0 then 1 else 0 end),";
                    }
                }
            }
            $itemName .= "@var1 as relevancia";
            $stock_items = StockItem::distinct()->pluck('item_id')->toArray();

            $items = DB::table('items')
                      ->leftJoin('item_prices', function($join) {
                            $join->on('item_prices.item_id', '=', 'items.id');
                            $join->where('item_prices.item_active', 1);
                      })
                      ->leftjoin('brands','brands.id','=','items.brand_id')
                      ->whereNotNull('item_prices.price')
                      ->whereNull('items.deleted_at')
                      ->whereIn('items.id',array_unique($stock_items))
                      ->OrWhere('items.active_without_stock','=','1')
                      ->select([
                          DB::raw($itemName),
                          'items.id',
                          'items.name',
                          'item_prices.price'
                      ])
                      ->when($name != null, function ($query, $name){
                          return $query->having('relevancia','!=','0')->orderBy('relevancia','desc');
                      })
                      ->limit($itemQty)
                      ->get();


            // $items = Item::where('items.name', 'like', '%'.$search.'%')
            //         ->select([
            //             'items.id',
            //             'items.name',
            //             'item_prices.price'
            //         ])
            //         ->leftjoin('item_prices', function($join) {
            //             $join->on('item_prices.item_id', '=', 'items.id');
            //             $join->on('item_prices.item_active', '=', DB::raw('1'));
            //         })
            //         ->limit(10)
            //         ->get();
            foreach ($items as $item) {
                $item->name = $item->name.'  $'.number_format($item->price, 0,'','.');
            }
            if(trim($search) !== "") {
                $row = new \stdClass;
                $row->id   = 0;
                $row->name = $search.' NO STOCK $0';    
                $items[] = $row;
            }

        } else {
            $items = Item::where('items.manufacturer_sku',$search)
                    ->select([
                        'items.id',
                        'items.manufacturer_sku'
                    ])
                    ->first();
        }
        return $this->json($request, $items, 200);
    }
}
