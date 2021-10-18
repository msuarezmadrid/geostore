<?php

namespace App\Http\Controllers\Api;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Log;
use PDF;
use App\Location;
use App\OrderNote;
use App\SaleOrder;
use App\Comune;
use App\File;
use App\SaleBox;
use App\ConciliationStatus;
use App\Enum\TransactDetails;
use App\SaleOrderDetail;
use App\SaleOrderItem;
use App\MovementHistorical;
use App\ItemPrice;
use App\LocationItem;
use App\StockItem;
use App\CartOrder;
use App\CartItem;
use App\Transact;
use App\SaleBoxDetail;
use App\Discount;
use App\Client as Clnt;
use App\Libraries\Afe;
use Response;
use DB;
use App\Sale;
use App\Seller;
use App\SaleDetail;
use App\User;
use App\Item;
use App\InvoiceReference;
use App\Config;
use App\UnitOfMeasure;
use GuzzleHttp\Client;
use Validator;
use App\Libraries\Utils;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade as PDFDOM;
use NumberFormatter;

class PointOfSaleController extends ApiController
{
    public $controller = "PointOfSaleController";
    const NO_BOX_OPEN                = 1;
    const SELL_GENERAL_ERROR         = 2; 
    const SELL_VALIDATION_ERROR      = 3;
    const AFE_PLATFORM_ERROR         = 4;
    const SELL_RUT_ERROR             = 5;
    const SELL_BOX_NOT_FOUND         = 6;
    const SELL_TRANSACTION_NOT_FOUND = 7;
    const SELL_DOC_NULL              = 8;
    const AFE_CLIENT_NOT_FOUND       = 9;
    const AFE_FOLIO_ERROR            = 10;
    const SELL_CART_NOT_FOUND        = 11;
    const SELL_INVALID_SELLER        = 12;
    const SELL_CLIENT_NOT_FOUND      = 13;
    const SELL_NO_STOCK              = 14;
    const CLIENT_COMUNE_NOT_FOUND    = 15;
    const NOT_ALLOWED_PAYMENT_METHOD = 16;
    const IVA                        = 0.19;

    public function salePoint(Request $request)
    {
        Log::info(__FUNCTION__ . " PointOfSaleController ");
        try {
        	$data = new \stdClass();
        	$sale_items = json_decode($request->get("items_sale"));
        	$sale_detail = json_decode($request->get("sale_detail"));

        	$sale = new SaleOrder();
        	$sale->code = 1233;
        	$sale->date = $sale_detail->date;
        	$sale->client_id = 1;
        	$sale->location_id = 2;
        	$sale->movement_status_id = 3;
        	$sale->enterprise_id = 4;
        	$sale->created_by= 9;
        	$sale->save();

        	$saleDetail = new SaleOrderDetail();
        	$saleDetail->total_discount = 0;
        	$saleDetail->total_net = $sale_detail->net;
        	$saleDetail->total_tax = $sale_detail->tax;
        	$saleDetail->total =  $sale_detail->total;
        	$saleDetail->sale_order_id = $sale->id ;
        	$saleDetail->save();

        	$historical = new MovementHistorical();
            $historical->order_id = $sale->id;
            $historical->movement_status_id = 3;
            $historical->movement_type = "sales";
            $historical->location_id = $sale->location_id;
            $historical->enterprise_id = 4;
            $historical->created_by = 9;
            $historical->save();

          	foreach ($sale_items as $key => $value) {

        		$saleItem = new SaleOrderItem();
		        $saleItem->item_id = $value->item->id ;
		        $saleItem->sale_order_id = $sale->id ;
		        $saleItem->quantity = $value->numberOfItems ;
		        $saleItem->unit_of_measure_id = $value->item->unit_of_measure_id ;
		        $saleItem->price = $value->item->item_prices[0]->price ;

		        $saleItem->created_by = $request->user()->id;
		        $saleItem->updated_by = $request->user()->id;
		        $saleItem->save();

		        $locationItem = LocationItem::where('location_id', $sale->location_id)->where('item_id', $value->item->id)->first();
		        $locationItem->quantity -= $value->numberOfItems;
		        $locationItem->save();
        	}

        	return $this->json($request, $data, 200);

        } catch(\Exception $e) {
            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
            return $this->json($request, null, 500);
        }
    }
    /**
     ** find all items in stock
     *
     * @param Request $request
     * @return*/

    public function items(Request $request)
    {
        Log::info(__FUNCTION__ . " PointOfSaleController ");
        DB::enableQueryLog();

        try {
            $itemQty = Config::where('param','PRESALE_POS_ITEM_QUANTITY')->first()->value;
            Log::info($itemQty);
            $name  = $request->input('name') == '' ? null : Utils::cleanString($request->input('name'));
            $listItems = [];

            $stock_items = StockItem::distinct()->pluck('item_id');

            if($name != null){
                $iisList = Item::leftJoin('item_prices', function($join) {
                    $join->on('item_prices.item_id', '=', 'items.id');
                    $join->where('item_prices.item_active', 1);
              })
              ->when($name, function($query) use($name) {
                    $query = $query->where('items.name', 'like', "%$name%");
              })
              ->where(function($query) use ($stock_items) {
                $query->whereIn('items.id',$stock_items)
                ->orWhere('items.active_without_stock', 1);
              })
              ->whereNotNull('item_prices.price')
              ->select([
                  'items.id',
                  'items.name',
                  'item_prices.price as actual_price'
              ])
                      ->limit($itemQty);
            } else {

                $priority = SaleDetail::whereNotNull('item_id')
                ->select([
                    DB::raw('count(*) as qty'),
                    'item_id'
                ])
                
                ->distinct()
                ->groupBy('item_id')
                ->orderBy('qty','desc')
                ->pluck('item_id')->toArray();
                $priority = array_reverse($priority);
                $priority = implode(",",$priority);

                Log::info($priority);
                $priority ? Log::info('con priority') : Log::info('sin priority');

                // DB::connection()->enableQueryLog();
                $iisList = Item::leftJoin('item_prices', function($join) {
                    $join->on('item_prices.item_id', '=', 'items.id');
                    $join->where('item_prices.item_active', 1);
                })
                ->leftjoin('brands','brands.id','=','items.brand_id')
                ->whereNotNull('item_prices.price')
                ->whereNull('items.deleted_at')
                ->where(function($query) use ($stock_items) {
                    $query->whereIn('items.id',$stock_items)
                    ->OrWhere('items.active_without_stock','=','1');
                })
                ->select([
                    'items.id',
                    'items.name',
                    'item_prices.price as actual_price'
                ])
                ->orderByRaw($priority  ? 'FIELD(items.id,'.$priority.') desc' : 'items.id desc')
                ->limit($itemQty);

            }
            
            $qrys = DB::getQueryLog();
            ## ULTIMA QUERY EJECUTADA 
            Log::info(__FUNCTION__ . " query: " . json_encode(end($qrys)) );

            if ($request->get('brands') != 0){
                $iisList = $iisList->where("brand_id", $request->get('brands'));
            }
            if ($request->get('categories') != 0){
                $iisList = $iisList->where("category_id", $request->get('categories'));
            }
            $iisList = $iisList->get();
            
            $qrys = DB::getQueryLog();
            ## ULTIMA QUERY EJECUTADA 
            Log::info(__FUNCTION__ . " query: " . json_encode(end($qrys)) );

            foreach($iisList as $iis) {
                $item_uom = DB::table('items')->where('id', $iis->id)->first()->unit_of_measure_id;
                if ($item_uom != 1){
                    $iis->real_stock = round(StockItem::where('item_id', $iis->id)->whereNull('order_cart_user_id')->sum('qty'),2);
                }
                else{
                    $iis->real_stock = StockItem::where('item_id', $iis->id)->whereNull('order_cart_user_id')->count();
                }
                $img = File::where('object_id', $iis->id)
                    ->where('object_type', 'items')
                    ->first();
                if ($img) {
                    $iis->imgUrl = $img->filename;
                }
            }
            
            $qrys = DB::getQueryLog();
            ## ULTIMA QUERY EJECUTADA 
            Log::info(__FUNCTION__ . " query: " . json_encode(end($qrys)) );

        return Response::json($iisList);

        }catch(\Exception $e) {
            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
            return $this->json($request, null, 500);
        }

    }
    /**
     ** find  item with barcode in stock
     *
     * @param Request $request
     * @return*/
    public function itemBarcode(Request $request)
    {
        Log::info(__FUNCTION__ . " PointOfSaleController ");
        try {
            $filters = $request->input('filters');
            $barcode = $filters['barcode'];
            $barcode = trim($barcode);
            $mode = 'scan';
            if(strlen($barcode) > 2) {
                if(substr($barcode, 0, 2) == 'ON') {
                    $mode = 'onote';
                }
                if(substr($barcode, 0, 2) == 'PQ') {
                    $mode = 'onote';
                }
            }
            if($mode == 'scan') {
                $items_in_stock = DB::select( DB::raw("SELECT items.id as id, items.name,
                (select item_prices.price from item_prices where item_prices.id = itemP.id) as actual_price
                from items 
                join item_prices as itemP on itemP.item_id = items.id
                where items.deleted_at is null
                and
                itemP.item_active = true
                and
                ( items.manufacturer_sku  = '".$barcode."' 
                or items.custom_sku = '".$barcode."'  ) 
                GROUP BY items.id, itemP.id"));
                if($items_in_stock) {
                    $items_in_stock[0]->stock_item = StockItem::where('item_id', $items_in_stock[0]->id)
                                                    ->whereNull('order_cart_user_id')
                                                    ->count();
                    return $this->json($request, $items_in_stock, 200);
                } else {
                    return $this->json($request, null, 404);
                }
            }
            if($mode == 'onote') {
                $box = SaleBox::where('seller', $request->user()->id)
                    ->where('status', 1)
                    ->first();
                if(!$box) {
                    return $this->json($request, [
                        'msg' => 'Caja debe encontrarse abierta'
                    ], 403);
                }
                $id        = substr($barcode, 2 , strlen($barcode));
                $ordernote = OrderNote::find($id);
                

                if(!$ordernote) {
                    return $this->json($request, [
                        'msg' => 'Orden no existe'
                    ], 403);
                }
                $cartOrder = CartOrder::where('created_by',$request->user()->id)->first();
                if (!$cartOrder) {
                    $cartOrder = new CartOrder();
                    $cartOrder->created_by = $request->user()->id;
                    $cartOrder->save();
                }
                
                $cartOrder->clean($request->user()->id);
                $observations = $ordernote->makeOrder($cartOrder->id, $request->user()->id);
                if(isset($observations['INVALID_NAME']) && $observations['INVALID_NAME'] === true) {
                    return $this->json($request, [
                        'msg' => 'Orden sin productos validos'
                    ], 403);
                }
                return $this->json($request, [
                    'seller_id'    => $ordernote->seller_id,
                    'type'         => $ordernote->type,
                    'client_id'    => $ordernote->client_id,
                    'onote_id'     => $ordernote->id,
                    'observations' => $observations
                ], 201);
            }
        }catch(\Exception $e) {
            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
            return $this->json($request, null, 500);
        }

    }

    /**
     * check available stock for item 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function stock(Request $request) 
    {
        Log::info(__FUNCTION__ . " PointOfSaleController ");
        DB::enableQueryLog();

        $item    = $request->input('item_id');
        $qty     = $request->input('stock');
        $success = true;
        $stock = StockItem::where('item_id', $item)
            ->where(function($query) use ($request) {
                $query->whereNull('order_cart_user_id')
            ->orWhere('order_cart_user_id', $request->user()->id);
        })
            ->count();

        $qrys = DB::getQueryLog();
        ## ULTIMA QUERY EJECUTADA 
        Log::info(__FUNCTION__ . " query: " . json_encode(end($qrys)) );

        if($qty > $stock) {
            $success = false;
        }
        $response = [
            'success' => $success
        ];
        return $this->json($request, $response, 200);
    }

    /**
     * Add discount to item
     */
    public function updateDiscount(Request $request)
    {
        Log::info(__FUNCTION__ . " PointOfSaleController ");
        try{

            $item_id = $request["batch_id"];
            $discount_id = $request["discount_id"];

            DB::table('cart_items')
                ->where('item_id', $item_id)
                ->update([
                    'discount_id' => $discount_id
                ]);
            return Response::json([
                'update_discount' => true
            ]);
        }
        catch(\Exception $e) {
            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
            $response = new \stdClass();
            $response->errors = ["message" => "La operación no pudo ser realizada. Intente nuevamente . Si el problema persiste, contacte al administrador."];
            return $this->json($request, $response, 500);
        }
    }

    /**
     ** add a new item to cart
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function add(Request $request) {
        Log::info(__FUNCTION__ . " PointOfSaleController ");
        try {
            $data = new \stdClass();
            $item_id = $request["batch_id"];
            $name = $request["name"];
            $price = $request["price"];
            $qty = $request["quantity"];
            $cart_item_id = 0;
            if ($request["cart_item_id"]){
                $cart_item_id = $request["cart_item_id"];
            }

            DB::beginTransaction();

            //CHECK IF BOX IS OPEN
            $box = SaleBox::where('status', 1)
                ->where('seller', $request->user()->id)
                ->first();
            if(!$box) {
                throw new \Exception(self::NO_BOX_OPEN);
            }

            //CART USER EXIST?
            $cartOrder = CartOrder::where('created_by',$request->user()->id)->first();
            $cartItem = null;
            if (!$cartOrder) {
                $cartOrder = new CartOrder();
                $cartOrder->created_by = $request->user()->id;
                $cartOrder->save();
            }

            //PRODUCT EXIST
            if ($item_id == 'null'){
                $item_id = null;
                $item_uom = 1;
                if ($cart_item_id != 0){
                    $cartItem = CartItem::where('id',$cart_item_id)->where("cart_order_id", $cartOrder->id)->first();
                }
            }
            else{
                $cartItem = CartItem::where('item_id',$item_id)->where("cart_order_id", $cartOrder->id)->first();
                $item_uom   = DB::table('items')->where('id', $item_id)->first()->unit_of_measure_id;
            }
            $new_batch_exists = "regular";
            $new_batch = null;

            if($item_uom != 1){
                $stock = StockItem::where('item_id', $item_id)
                    ->whereNull('order_cart_user_id')
                    ->sum('qty');
            }
            else{
                $stock = StockItem::where('item_id', $item_id)
                    ->whereNull('order_cart_user_id')
                    ->count();
            }

            if ($cartItem) {
                if ($item_uom == 1){
                    $qty = round($qty,0);
                }
                if ($qty <= 0) {
                    $cartItem->delete();
                    DB::commit();
                    return Response::json([
                        "new_batch" => null,
                        "stock" => $stock,
                        "discount"  => null,
                        "new_batch_exists" => 'regular',
                        "ADDCART" => "agregando al carrito..."
                    ]);

                }
                else {
                    $cartItem->quantity = $qty;
                    $cartItem->save();
                    DB::commit();
                    return Response::json([
                        "new_batch" => null,
                        "stock" => $stock,
                        "discount"  => $cartItem->discount_id,
                        "new_batch_exists" => 'regular',
                        "ADDCART" => "agregando al carrito..."
                    ]);
                }
            }
            else {
                $cartItem = new CartItem();
                $cartItem->price         = $price;
                $cartItem->item_id       = $item_id;
                $cartItem->quantity      = $qty;
                $cartItem->name          = $name;
                $cartItem->cart_order_id = $cartOrder->id;
                $cartItem->location_id   = $box->location_id != null ? $box->location_id : Location::first()->id;
                $cartItem->discount_id   = Discount::where('name','Sin Descuento')->first()->id;
                $cartItem->save();
                DB::commit();
                
                return Response::json([
                    "new_batch" => null,
                    "stock"     => $stock, 
                    "discount"  => $cartItem->discount_id,
                    "new_batch_exists" => 'regular'
                ]);

            }
        }
        catch(\Exception $e) {
            DB::rollback();
            switch($e->getMessage()) {
                case self::NO_BOX_OPEN:
                    return $this->json($request, [
                        'msg' => 'Debe abrir caja'
                    ], 403);
                break;
                default:
                    Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
                    $response = new \stdClass();
                    $response->errors = ["message" => "La operación no pudo ser realizada. Intente nuevamente . Si el problema persiste, contacte al administrador."];
                    return $this->json($request, $response, 500);
                break;
            }
            
       }

    }

    /**
     ** return all items in cart
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function all(Request $request, StockItem $si) 
    {
        Log::info(__FUNCTION__ . " PointOfSaleController ");
        try{
            $cartOrder = CartOrder::where('created_by',$request->user()->id)->first();
            if($cartOrder){
                $cartItem  = CartItem::where('cart_order_id',$cartOrder->id)
                    ->where('quantity', '>', 0)
                    ->get();

                //8080
                $cartItem->filter(function($item) {
                    return trim($item->name)  !== "";
                });

                $locations = Location::where('enterprise_id', $request->user()->enterprise_id)->get();

                foreach($cartItem as $item) {
                    //price 
                    if ($item->item_id != null) {
                        $net = ItemPrice::where('item_id', $item->item_id)
                            ->where('item_active', 1)
                            ->select('price')->first();

                        $item->items = DB::table('items')->where('id', $item->item_id)->first();

                        if ($net) {
                            $item->net_price = round($net->price/1.19, 2);
                        }
                        else {
                            $item->net_price = 0;  
                        }

                        $item->stocks = $si->getStocks($item->item_id, $locations);
                    }
                    else {
                        $item->net_price = round($item->price/1.19, 2);
                        $item->stocks    = null;
                        $item->items = new \StdClass();
                        $item->items->block_discount = 0;
                    }
                }  
                return Response::json($cartItem);
            }

        }
        catch(\Exception $e) {
            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
            return $this->json($request, null, 500);
        }
    }

    public function deleteAll(Request $request) 
    {
        Log::info(__FUNCTION__ . " PointOfSaleController ");
        try {
            $cartOrder = CartOrder::where('created_by',$request->user()->id)->first();
            if ($cartOrder) {
                $cartOrder->clean($request->user()->id);
            }
            $response = [
                'code' => 200
            ];
            return $this->json($request, $response, 200);

        } catch (\Exception $e) {
            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
            return $this->json($request, null, 500);
        }
    }


    /*
     ** validate all items in cart
     ** ensure requested quantity is not more than available stock
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function process(Request $request) 
    {
        Log::info(__FUNCTION__ . " PointOfSaleController ");
        $cart_item = DB::select( 
            DB::raw("SELECT count(*) as count from cart_items join cart_orders on cart_orders.id = cart_items.cart_order_id where cart_orders.created_by =".$request->user()->id)
        );

        if($cart_item){
            return Response::json([
                "success" => $cart_item,
            ]);
        }
    }

    public function sell(Request $request) 
    {
        Log::info(__FUNCTION__ . " PointOfSaleController ");
        try {
            $msg = '';
            $AFE = new Afe();
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'client_id'      => 'required',
                'document_type'  => 'string|required',
                'seller'         => 'integer|required',
                'payment_method' => 'string|required'
            ]);
            if($validator->fails()){
                Log::info("message=[{$validator->errors()}]");
                throw new \Exception(self::SELL_VALIDATION_ERROR);
            }

            $paymentMethod = Config::where('param',$request->get('payment_method'))
                ->where('value','1')
                ->first();
            if ($paymentMethod == null) {
                throw new \Exception(self::NOT_ALLOWED_PAYMENT_METHOD);
            }
                                    

            $cartOrder = CartOrder::where('created_by',$request->user()->id)->first();
            $cartItem = CartItem::where('cart_order_id',$cartOrder->id)->where('quantity', '>', 0)->get();

            if ($cartOrder == null) {
                throw new \Exception('Cart Order not found');
            }
            if ($cartItem == null) {
                throw new \Exception('Cart Item not found');
            }
            $user = User::find($request->user()->id);
            $type = $request->input('document_type');

            $ignore_rut = 'false';
            $client_id  = $request->input('client_id');
            $clientData = Clnt::find($client_id);

            $seller     = Seller::find($request->get('seller'));
            if ($seller == null) {
                throw new \Exception(self::SELL_INVALID_SELLER);
            }

            $conStatusNO    = ConciliationStatus::where('name', 'NO_CONCILIATE')->first();
            $conStatusPART  = ConciliationStatus::where('name', 'PART_CONCILIATE')->first();
            $conStatus      = ConciliationStatus::where('name', 'CONCILIATE')->first();

            if ($conStatus == null || $conStatusPART == null || $conStatusNO == null) {
                throw new \Exception(self::SELL_VALIDATION_ERROR);
            }

            if ($type == 'ticket') {
                if ($request->has('ignore_rut')) {
                    $ignore_rut = $request->input('ignore_rut');
                } else {
                    throw new \Exception(self::SELL_VALIDATION_ERROR);
                }
            }

            if ($type == 'invoice' && $clientData == null) {
                throw new \Exception(self::SELL_CLIENT_NOT_FOUND);
            }

            if ($clientData != null && $ignore_rut == 'false') {
                $rut = trim($clientData->rut).'-'.$clientData->rut_dv;
                //CONSULTA EN AFE
                $response = $AFE->checkClient($rut);
                if(!$response['success']) {
                    throw new \Exception(self::AFE_PLATFORM_ERROR);
                }
            }

            if ($ignore_rut == 'false' && $clientData == null) {
                throw new \Exception(self::SELL_CLIENT_NOT_FOUND);
            }

            $clientGeneric = Clnt::where('name', 'GENERIC')->first(); //PUBLICO GENERAL

            $sale = new Sale();
            $sale->code           = $request->get('order_note');
            $sale->date           = $request->get("date");
            $sale->client_id      = $clientData != null ? $clientData->id : $clientGeneric->id;
            $sale->seller_id      = $seller->id;
            $sale->type           = $type == 'ticket' ? 1 : 2;
            $sale->payment_method = $request->get('payment_method');
            $sale->conciliation_status_id = $conStatus->id;
            $sale->created_by = $request->user()->id;
            $sale->updated_by = $request->user()->id;
            $sale->save();

            $params = [];
            if ($ignore_rut == 'true') {
                $params['rutRecep'] = config('afe.default_rut');
            } else {
                $params['rutRecep'] = $clientData->rut.'-'.$clientData->rut_dv;
            }

            $params['observations'] = ($request->has('observations') ? $request->get('observations') : '');
            $params['payMode']  = $request->get('credit_sale') == 'true' ? 2 : 1;
            $params['FhcVenc']  = $request->get('credit_sale') == true ? $request->get('FhcVenc') : "false";
            $params['details']  = [];
            $params['FolioRef'] = [];
            $params['RazonRef'] = [];
            $params['TpoDocRef'] = [];
            $params['FchRef'] = [];
            $params['letter'] = $request->get('letter') == 'true' ? 1 : 0;
            // $params['letter'] = 0;

            if($request->has('invoice_references')) {
                $references = json_decode($request->get('invoice_references'));
                foreach($references as $ref){
                    
                    $invoiceReference = new InvoiceReference();
                    
                    $invoiceReference->folio            =   $ref->folio;
                    $invoiceReference->reason_reference =   $ref->reason_reference;
                    $invoiceReference->doc_type         =   strstr($ref->doc_type, ':', true);
                    $invoiceReference->date             =   $ref->date;
                    $invoiceReference->sale_id          =   $sale->id;

                    $params['FolioRef'][] = $ref->folio;
                    $params['RazonRef'][] = $ref->reason_reference;
                    $params['TpoDocRef'][] = strstr($ref->doc_type, ':', true);
                    $params['FchRef'][] = $ref->date;

                    $invoiceReference->save();
                }
            }

            $total = 0;
            $locations = [];

            foreach ($cartItem as $item) {
                $sdetail            = new SaleDetail();
                $sdetail->sale_id   = $sale->id;
                $sdetail->item_id   = $item->item_id;
                $sdetail->item_name = $item->name;
                if($item->item_id){
                    $itemRef = DB::table('items')->where('id', $item->item_id)->first();
                    $it                 = $itemRef->block_discount;
                    $item_uom           = $itemRef->unit_of_measure_id;
                    $item_sell_without_stock = $itemRef->active_without_stock;

                }
                else{
                    $it = 0;
                    $item_uom = 1;
                }
                if ($it==0){
                    $sdetail->discount_percent = $item->discount_percent;
                }
                else{
                    $sdetail->discount_percent = 0;
                }
                
                if ($type == 'ticket') {
                    $sdetail->price = $item->price;
                    $total  += round($item->price*$item->quantity);
                    $total  -= round(round($item->price*$item->quantity)*($item->discount_percent/100));
                }
                else {
                    $sdetail->price = round($item->price/(1+self::IVA), 2);
                    $total += round( round(round($item->price/(1+self::IVA), 2) * $item->quantity));                    
                    $total -= round( round(round($item->price/(1+self::IVA), 2) * $item->quantity)*($item->discount_percent/100));
                }
                $sdetail->qty              = $item->quantity;
                
                $sdetail->conciliated      = 0;
                $sdetail->save();

                $detail = [];
                $detail['qty']       = $sdetail->qty;
                $detail['price']     = $item->price;
                $detail['pricei']    = round($item->price/(1+self::IVA), 2);
                $detail['itemName']  = $item->name;
                if ($it==1){
                    $detail['discount']  = 0;
                }
                else{
                    $detail['discount']  = $item->discount_percent;
                }
                $params['details'][] = $detail;


                //ASEGURAMOS STOCK
                if ($item->item_id != null) {
                    if ($item_uom != 1){
                        $stock = StockItem::where('item_id', $item->item_id)
                        ->whereNull('order_cart_user_id')
                        ->where('location_id', $item->location_id)
                        ->sum('qty');
                    }else{
                        $stock = StockItem::where('item_id', $item->item_id)
                                      ->whereNull('order_cart_user_id')
                                      ->where('location_id', $item->location_id)
                                      ->count();
                    }
                    if ($item->quantity <= $stock || $item_sell_without_stock == 1) {
                        $transact = new Transact();
                        if ($sdetail->qty == '1') {
                            $uom_name = UnitOfMeasure::find($item_uom)->first()->name;
                            $transact->description = 'Se vendió <b>' . $sdetail->qty . ' ' . $uom_name.'</b>';
                        }else{
                            $uom_name = UnitOfMeasure::find($item_uom)->first()->plural;
                            $transact->description = 'Se vendieron <b>' . $sdetail->qty . ' ' . $uom_name.'</b>';
                        }
                        $transact->object_id = $item->item_id;
                        $transact->object_type = "items";
                        $transact->created_by = $request->user()->id;
                        $transact->save();
                        if($item_uom != 1){
                            $sitem = StockItem::where('location_id', $item->location_id)
                                ->where('item_id', $item->item_id)
                                ->first();
                            if ($sitem != null) {
                                $validateValue = $sitem->qty-$item->quantity;
                                while ($validateValue <= 0 && $sitem != null){
                                    $sitem = $sitem->delete();
                                    $sitem = StockItem::where('location_id', $item->location_id)
                                        ->where('item_id', $item->item_id)
                                        ->first();

                                    if($sitem != null) {
                                        $validateValue = $sitem->qty+$validateValue;
                                    }
                                }

                                if($sitem != null) {

                                    $newItem = new StockItem();
                                    $newItem->item_id = $sitem->item_id;
                                    $newItem->qty =  $item->quantity;
                                    $newItem->price = $sitem->price;
                                    $newItem->location_id =  $item->location_id;
                                    $newItem->qty =  $item->quantity;
                                    $newItem->order_cart_user_id = $request->user()->id;

                                    $sitem->qty = $validateValue;
                                    $sitem->save();
                                }

                            } 
                            else {
                                if($item_sell_without_stock != 1) {
                                    $msg = $item->name.' no posee stocks';
                                    throw new \Exception(self::SELL_NO_STOCK);    
                                } 
                                else {
                                    Log::info($this->controller . "->" . __FUNCTION__ . ": " . $item->name . ", Vendido sin Stock, stock utilizado: " . ($validateValue-$cartItem->quantity));
                                }
                            }
                        }else{
                            for ($x=0; $x<$item->quantity; $x++) {
                                $sitem = StockItem::where('item_id', $item->item_id)
                                    ->whereNull('order_cart_user_id')->first();
                                if ($sitem != null) {
                                    $sitem->order_cart_user_id = $request->user()->id;
                                    $sitem->save();
                                } else {
                                    if($item_sell_without_stock != 1) {
                                        $msg = $item->name.' no posee stocks';
                                        throw new \Exception(self::SELL_NO_STOCK);
                                    } else {
                                        Log::info($this->controller . "->" . __FUNCTION__ . ": " . $item->name . ", Vendido sin Stock, stock utilizado: " . ($x+1));
                                    }
                                }
                            }
                        }
                    } 
                    else {
                        $msg = $item->name.' no posee stock';
                        throw new \Exception(self::SELL_NO_STOCK);
                    }
                    if (!in_array($item->location_id, $locations)) {
                        $locations[] = $item->location_id;
                    }
                }


            }
            if ($type == 'ticket') {
                $sale->total = $total;
            } else {
                $sale->net   = $total;
                $sale->tax   = round($sale->net*self::IVA);
                $sale->total = $sale->net + $sale->tax;
            }
            switch ($sale->payment_method) { 
                case 'intern' :
                    $sale->conciliation_status_id = $conStatusNO->id;
                break;
                case 'mix' :
                    $tmix = 0;
                    $docs = json_decode($request->get('docs'));
                    foreach ($docs as $doc) {
                        //4 - interno
                        if($doc->type != 4) {
                            $tmix = $doc->amount;
                        }
                    }
                    if ($tmix == 0) $sale->conciliation_status_id = $conStatusNO->id;
                    if ($tmix > 0 && $tmix < $sale->total) {
                        $sale->conciliated = $tmix;
                        $sale->conciliation_status_id = $conStatusPART->id;
                    }
                    if ($tmix == $sale->total) {
                        $sale->conciliated = $sale->total;
                    }
                break;
                default:
                    $sale->conciliated = $sale->total;
                break;
            }
            $sale->save();

            //FLUJO DE CAJA
            //VERIFICAMOS SI EL USUARIO TIENE CAJA ABIERTA
            $box = SaleBox::where('seller', $request->user()->id)
                ->where('status', 1)
                ->first();
            if(!$box) {
                throw new \Exception(self::SELL_BOX_NOT_FOUND);
            }

            //BUSCAMOS CODIGO TRANSACCION 
            $transactionid = $box->searchTransaction([
                'seller'   => $request->user()->id,
                'sale_box' => $box->id
            ]); 
            if(!$transactionid) {
                throw new \Exception(self::SELL_TRANSACTION_NOT_FOUND);
            }

            $printTicket = $request->get('submit_ticket');

            $printTicket = $printTicket === 'false' ? false : true;

            if ($type == 'ticket') {
                if(($request->get('payment_method') == 'card' || $request->get('payment_method') == 'intern') && !$printTicket) {
                    $response = ['folio' => 'NO EMITIDO EN SISTEMA'];   
                }
                else {
                    $response = $AFE->makeTicket($params);
                    if (!$response['success']) {
                        if($response['msg'] == '1') {
                            throw new \Exception(self::AFE_CLIENT_NOT_FOUND);
                        }
                        else if ($response['msg'] == '2') {
                            throw new \Exception(self::AFE_FOLIO_ERROR);
                        }
                        else throw new \Exception(self::AFE_PLATFORM_ERROR);
                    }
                }
            }
            else {
                $comune = Comune::where('id', $clientData->comune_id)->withTrashed()->first();

                if($comune == null) {
                    throw new \Exception(self::CLIENT_COMUNE_NOT_FOUND);
                }

                $params['razon_social'] = $clientData->name;
                $params['address'] = $clientData->address;
                $params['industries'] = $clientData->industries;
                $params['comune'] = $comune->comune_detail;

                $response = $AFE->makeInvoice($params);
                if (!$response['success']) {
                    if($response['msg'] == '1') {
                        throw new \Exception(self::AFE_CLIENT_NOT_FOUND);
                    }
                    else if ($response['msg'] == '2') {
                        throw new \Exception(self::AFE_FOLIO_ERROR);
                    }
                    else throw new \Exception(self::AFE_PLATFORM_ERROR);
                }
            }

            $sale->folio = $response['folio'];
            $sale->save();

            foreach ($locations as $location) {
                //ORDENES DE SALIDA SEGUN DESTINO
                $saleorder                      = new SaleOrder();
                $saleorder->sale_id             = $sale->id;
                $saleorder->code                = $response['folio'];
                $saleorder->date                = Date('Y-m-d');
                $saleorder->client_id           = $clientData == null ? $clientGeneric->id : $clientData->id;
                $saleorder->location_id         = $location;
                $saleorder->movement_status_id  = 3;
                $saleorder->enterprise_id       = $request->user()->enterprise_id;
                $saleorder->created_by          = $request->user()->id;
                $saleorder->updated_by          = $request->user()->id;
                $saleorder->save();
                //DETALLE
                foreach ($cartItem as $citem) {
                    if($citem->item_id != null && $citem->location_id == $location) {
                        $sorderitem = new SaleOrderItem();
                        $sorderitem->item_id            = $citem->item_id;
                        $sorderitem->sale_order_id      = $saleorder->id;
                        $sorderitem->quantity           = $citem->quantity;
                        $sorderitem->unit_of_measure_id = $item_uom;
                        $sorderitem->price_type_id      = 2; //VENTA
                        $sorderitem->price              = $item->price;
                        $sorderitem->created_by         = $request->user()->id;
                        $sorderitem->updated_by         = $request->user()->id;
                        $sorderitem->save();
                    }
                }
            }

            $allItems    = $cartItem;
            $removeStock = StockItem::where('order_cart_user_id',$request->user()->id)->delete();
            $cartOrder = CartOrder::where('created_by',$request->user()->id)->first();
            $cartItem = CartItem::where('cart_order_id',$cartOrder->id)->delete();
            $cartOrder->delete();

            $payment_method = $request->get('payment_method');

            if ($payment_method == 'cash') {
                $box->addTransaction([
                    'seller'       => $request->user()->id,
                    'sale_box_id'  => $box->id,
                    'transact_id'  => $transactionid,
                    'type'         => $type == 'ticket' ? TransactDetails::SALE_BOX_TICKET_CASH : TransactDetails::SALE_BOX_INVOICE_CASH,
                    'amount'       => $request->get('amount_tendered'),
                    'doc_id'       => $response['folio'],
                    'observations' => 'Pago efectivo'
                ]);
    
                $box->addTransaction([
                    'seller'       => $request->user()->id,
                    'sale_box_id'  => $box->id,
                    'transact_id'  => $transactionid,
                    'type'         => $type == 'ticket' ? TransactDetails::SALE_BOX_TICKET_CASH : TransactDetails::SALE_BOX_INVOICE_CASH,
                    'amount'       => (-1)*$request->get('change'),
                    'doc_id'       => $response['folio'],
                    'observations' => 'Cambio'
                ]);

                if($request->has('diff')) {
                    $diff = $request->input('diff');
                    if ($diff != 0) {
                        $box->addTransaction([
                            'seller'       => $request->user()->id,
                            'sale_box_id'  => $box->id,
                            'transact_id'  => $transactionid,
                            'type'         => $type == 'ticket' ? TransactDetails::SALE_BOX_DIFF_TICKET : TransactDetails::SALE_BOX_DIFF_INVOICE,
                            'amount'       => $diff,
                            'doc_id'       => $response['folio'],
                            'observations' => 'Diferencia ley redondeo'
                        ]);
                    }
                }

            }
            if ($payment_method == 'card') {
                $box->addTransaction([
                    'seller'       => $request->user()->id,
                    'sale_box_id'  => $box->id,
                    'transact_id'  => $transactionid,
                    'type'         => $type == 'ticket' ? TransactDetails::SALE_BOX_TICKET_CARD : TransactDetails::SALE_BOX_INVOICE_CARD,
                    'amount'       => $sale->total,
                    'doc_id'       => $response['folio'],
                    'observations' => 'Cod Operacion :'.$request->get('operation').' | Observaciones : '.$request->get('observations')
                ]);
            }
            if ($payment_method == 'cheque') {
                if ($request->has('docs')) {
                    $docs = json_decode($request->get('docs'));
                    foreach ($docs as $doc) {
                        $observations  = 'Número documento : '.$doc->docnumber;
                        $observations .= '| Fecha : '.date('d-m-Y', strtotime($doc->date));
                        $observations .= '| Banco : '.$doc->bank;
                        $observations .= '| Observaciones : '.$request->get('observations');
                        
                        $box->addTransaction([
                            'seller'       => $request->user()->id,
                            'sale_box_id'  => $box->id,
                            'transact_id'  => $transactionid,
                            'type'         => $type == 'ticket' ? TransactDetails::SALE_BOX_TICKET_CHEQUE : TransactDetails::SALE_BOX_INVOICE_CHEQUE,
                            'amount'       => $doc->amount,
                            'doc_id'       => $response['folio'],
                            'observations' => $observations
                        ]);
                    }
                }
                else {
                    throw new \Exception(self::SELL_DOC_NULL);
                }
            }
            if ($payment_method == 'intern') {
                $observations = '| Observaciones : '.$request->get('observations');
                $box->addTransaction([
                    'seller'       => $request->user()->id,
                    'sale_box_id'  => $box->id,
                    'transact_id'  => $transactionid,
                    'type'         => $type == 'ticket' ? TransactDetails::SALE_BOX_TICKET_INTERN : TransactDetails::SALE_BOX_INVOICE_INTERN,
                    'amount'       => $sale->total,
                    'doc_id'       => $response['folio'],
                    'observations' => $observations
                ]);
            }
            if($payment_method == 'app') {
                $app = $request->get('deliveryapp');
                if($app == -1) {
                    $app = "APP INVALIDO, NO INGRESADO, O DESCONOCIDO";
                }
                $observations = 'APP: ' . $app . ". VOUCHER: " . $request->get('deliveryappvoucher');
                $observations = 'APP: ' . $request->get('deliveryapp') . ". VOUCHER: " . $request->get('deliveryappvoucher');
                $box->addTransaction([
                    'seller'       => $request->user()->id,
                    'sale_box_id'  => $box->id,
                    'transact_id'  => $transactionid,
                    'type'         => $type == 'ticket' ? TransactDetails::SALE_BOX_TICKET_APP : TransactDetails::SALE_BOX_INVOICE_APP,
                    'amount'       => $sale->total,
                    'doc_id'       => $response['folio'],
                    'observations' => $observations
                ]);
            }
            if($payment_method == 'transfer') {
                $observations = 'CLIENTE: ' . $request->get('transferclient');
                $box->addTransaction([
                    'seller'       => $request->user()->id,
                    'sale_box_id'  => $box->id,
                    'transact_id'  => $transactionid,
                    'type'         => $type == 'ticket' ? TransactDetails::SALE_BOX_TICKET_TRANSFER : TransactDetails::SALE_BOX_INVOICE_TRANSFER,
                    'amount'       => $sale->total,
                    'doc_id'       => $response['folio'],
                    'observations' => $observations
                ]);
            }
            if ($payment_method == 'mix') {
                if ($request->has('docs')) {
                    $docs = json_decode($request->get('docs'));
                    foreach ($docs as $doc) {
                        switch($doc->type) {
                            case 1:
                                $box->addTransaction([
                                    'seller'       => $request->user()->id,
                                    'sale_box_id'  => $box->id,
                                    'transact_id'  => $transactionid,
                                    'type'         => $type == 'ticket' ? TransactDetails::SALE_BOX_TICKET_CASH : TransactDetails::SALE_BOX_INVOICE_CASH,
                                    'amount'       => $doc->amount,
                                    'doc_id'       => $response['folio'],
                                    'observations' => 'Pago efectivo parcial'
                                ]);
                                if($doc->change != 0) {
                                    $box->addTransaction([
                                        'seller'       => $request->user()->id,
                                        'sale_box_id'  => $box->id,
                                        'transact_id'  => $transactionid,
                                        'type'         => $type == 'ticket' ? TransactDetails::SALE_BOX_TICKET_CASH : TransactDetails::SALE_BOX_INVOICE_CASH,
                                        'amount'       => (-1)*$doc->change,
                                        'doc_id'       => $response['folio'],
                                        'observations' => 'Cambio'
                                    ]);    
                                }
                            break;
                            case 2:
                                $observations = 'Observaciones : '.$request->get('observations');
                                $box->addTransaction([
                                    'seller'       => $request->user()->id,
                                    'sale_box_id'  => $box->id,
                                    'transact_id'  => $transactionid,
                                    'type'         => $type == 'ticket' ? TransactDetails::SALE_BOX_TICKET_CHEQUE : TransactDetails::SALE_BOX_INVOICE_CHEQUE,
                                    'amount'       => $doc->amount,
                                    'doc_id'       => $response['folio'],
                                    'observations' => $observations
                                ]);
                            break;
                            case 3:
                                $box->addTransaction([
                                    'seller'       => $request->user()->id,
                                    'sale_box_id'  => $box->id,
                                    'transact_id'  => $transactionid,
                                    'type'         => $type == 'ticket' ? TransactDetails::SALE_BOX_TICKET_CARD : TransactDetails::SALE_BOX_INVOICE_CARD,
                                    'amount'       => $doc->amount,
                                    'doc_id'       => $response['folio'],
                                    'observations' => 'Observaciones : '.$request->get('observations')
                                ]);
                            break;
                            case 4:
                                $box->addTransaction([
                                    'seller'       => $request->user()->id,
                                    'sale_box_id'  => $box->id,
                                    'transact_id'  => $transactionid,
                                    'type'         => $type == 'ticket' ? TransactDetails::SALE_BOX_TICKET_INTERN : TransactDetails::SALE_BOX_INVOICE_INTERN,
                                    'amount'       => $doc->amount,
                                    'doc_id'       => $response['folio'],
                                    'observations' => 'Observaciones : '.$request->get('observations')
                                ]);
                            break;
                        }
                    }
                } else {
                    throw new \Exception(self::SELL_DOC_NULL);
                }
            }
            

            DB::commit();
            
            foreach($allItems as $ai) {
                $disc = $ai->discount_percent;
                $ai->net_price       = round($ai->price/1.19, 2);
                $ai->discount_no_tax = round(($ai->net_price*$ai->quantity)*( ($disc)/100));
                $ai->discount_tax    = round(($ai->price*$ai->quantity)*( ($disc)/100));
                $ai->total_no_tax    = ($ai->net_price*$ai->quantity) -  $ai->discount_no_tax;
                $ai->total_tax       = ($ai->price*$ai->quantity)   -  $ai->discount_tax;
                //(100-$disc)/100
            }
            
            return Response::json([
                "success" => "Compra exitosa ",
                "id"      => $sale->id,
                "items"   => $allItems,
                "change_amount"   => ($request->has("change") ? $request->get("change") : 0),
                "total_amount"    => $total,
                "amount_tendered" => ($request->has("amount_tendered") ? $request->get("amount_tendered") : $total),
                'payment_method'  => ($request->has("payment_method") ? $request->get("payment_method") : ''),
                "afe_response"    => $response
            ]);
        } catch(\Exception $e) {
            DB::rollback();
            switch($e->getMessage()) {
                case self::SELL_GENERAL_ERROR :
                    return $this->json($request, [
                        'msg' => 'Error general'
                    ], 401);
                break;
                case self::SELL_VALIDATION_ERROR :
                    return $this->json($request, [
                        'msg' => 'Campos inválidos'
                    ], 401);
                break;
                case self::AFE_PLATFORM_ERROR :
                    return $this->json($request, [
                        'msg' => 'Error en plataforma AFE, contacte al administrador del sistema'
                    ], 401);
                break;
                case self::SELL_RUT_ERROR :
                    return $this->json($request, [
                        'msg' => 'Debe ingresar un rut válido'
                    ], 401);
                break;
                case self::SELL_BOX_NOT_FOUND :
                    return $this->json($request, [
                        'msg' => 'No existe caja abierta'
                    ], 401);
                break;
                case self::SELL_TRANSACTION_NOT_FOUND:
                    return $this->json($request, [
                        'msg' => 'Transacción no encontrada'
                    ], 401);
                break;
                case self::SELL_DOC_NULL:
                    return $this->json($request, [
                        'msg' => 'No se ingresaron cheques'
                    ], 401);
                break;
                case self::AFE_CLIENT_NOT_FOUND:
                    return $this->json($request, [
                        'msg' => 'Error en plataforma AFE : Cliente no se encuentra registrado'
                    ], 401);
                break;
                case self::AFE_FOLIO_ERROR:
                    return $this->json($request, [
                        'msg' => 'No hay folios disponibles para este tipo de documento'
                    ], 401);
                case self::SELL_INVALID_SELLER:
                    return $this->json($request, [
                        'msg' => 'Debe seleccionar un vendedor'
                    ], 401);
                break;
                case self::SELL_CLIENT_NOT_FOUND:
                    return $this->json($request, [
                        'msg' => 'Debe seleccionar un cliente'
                    ], 401);
                break;
                case self::SELL_NO_STOCK:
                    return $this->json($request, [
                        'msg' => $msg
                    ], 401);
                break;
                case self::CLIENT_COMUNE_NOT_FOUND:
                    return $this->json($request, [
                        'msg' => 'Cliente no tiene comuna'
                    ], 401);
                break;
                case self::NOT_ALLOWED_PAYMENT_METHOD:
                    return $this->json($request, [
                        'msg' => 'Método de pago no aceptado'
                    ], 401);
                break;
                default:
                    Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
                    return $this->json($request, null, 500);
                break;
            }
        }
    }

    public function printReplacementDocument(Request $request, int $id) 
    {
        Log::info(__FUNCTION__ . " PointOfSaleController ");
        $sale = Sale::find($id);
        $info  = $sale->getPdfInfo();
        if($info != null) {
            $info = $info[0];
        }
        $info['rznSoc'] = config('afe.company_name');

        //TO LOCAL DATE (CHILE)
        $info['date'] = Carbon::createFromFormat('Y-m-d H:i:s', $sale->created_at, 'UTC')
            ->setTimezone('America/Santiago')
            ->format('d-m-Y');
        $info['hour'] = Carbon::createFromFormat('Y-m-d H:i:s', $sale->created_at, 'UTC')
            ->setTimezone('America/Santiago')
            ->format('H:i:s');
        
        $formatter = new NumberFormatter( 'es_CL', NumberFormatter::CURRENCY);
        $info['total'] = $formatter->formatCurrency($sale->total, 'CLP');
        $data  = [
            'sale' => $info,
            'id'    => 'replace_'.$sale->id
        ];

        $addressOverride = config('pdf.pdf_print_folder') == '' ? 'geostore' : config('pdf.pdf_print_folder');
        
        $path = ".pos.".strtolower($addressOverride);
        
        $filePath = storage_path('app/public').'/ticket_replacement/';
        if(!file_exists($filePath)) {
            mkdir($filePath, 0755, true);
        }


        //~161px es el minimo para tener un item + el detalle inicial
        //15px por item parece tener un desfase positivo muy leve
        //156px y 15px tomado como los valores iniciales
        //Añadidos 30px de holgura
        $length = 176 + count($info['details'])*15;
        //226
        $pdf = PDFDOM::loadView('pdf'.$path.'.ticket-replacement', $data)
            ->setPaper(array(10,0,226.77 , $length));
                
        $pdf->save(storage_path('app/public').'/ticket_replacement/'.$sale->id.'.pdf');
        return $this->json($request, [
            'success' => true,
            'url' => asset('storage/ticket_replacement').'/'.$sale->id.'.pdf'
        ], 200);
    }

    public function clients(Request $request) 
    {
        Log::info(__FUNCTION__ . " PointOfSaleController ");
        try {
            $AFE      = new Afe();
            $response = $AFE->getClients();
            if(!$response['success']) {
                throw new \Exception(self::AFE_PLATFORM_ERROR);
            }
            return $this->json($request, $response, 200);
        } catch (\Exception $e) {
            switch($e->getMessage()) {
                case self::AFE_PLATFORM_ERROR :
                    return $this->json($request, [
                        'msg' => 'Error en plataforma AFE, contacte al administrador del sistema'
                    ], 401);
                break;
                default:
                    Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
                    return $this->json($request, null, 500);
                break;
            }
        }        
    }

    public function getMovements(Request $request) 
    {
        Log::info(__FUNCTION__ . " PointOfSaleController ");
        $box = SaleBox::where('seller', $request->user()->id)
            ->where('status', 1)
            ->first();
        if(!$box) {
            return $this->json($request, [
                'movements' => null
            ], 200);
        }
        //BUSCAMOS CODIGO TRANSACCION 
        //2020
        $transactionid = $box->searchTransaction([
           'seller'   => $request->user()->id,
           'sale_box' => $box->id
        ]);
        if(!$transactionid) {
            return $this->json($request, [
                'movements' => null
            ], 200);
        }
        $start = is_null($request->get('start')) ? 0 : (int)$request->get('start');
        $length = is_null($request->get('length')) ? 100 : (int)$request->get('length');
        $columns = $request->get('columns');
        $order = $request->get('order');
        $orderDirection = isset($order[0]) ? $order[0]['dir'] :  'asc';
        $orderField = isset($columns[$order[0]['column']]['data']) ? $columns[$order[0]['column']]['data'] : 'id';
        $filters   = !$request->input('filters') ? [] : $request->input('filters');
        $filters['transact_id'] = $transactionid;
        $result = (new SaleBoxDetail)->getPaginated($filters, $start, 10, $orderField, $orderDirection);
        
        return $this->json($request, [
            'movements' => $result
        ], 200);
    }

    public function  getMovementResume(Request $request) 
    {
        Log::info(__FUNCTION__ . " PointOfSaleController ");
        $box = SaleBox::where('seller', $request->user()->id)
            ->where('status', 1)
            ->first();
        if(!$box) {
            return $this->json($request, [
                'success' => false
            ], 404);
        }
        $transactionid = $box->searchTransaction([
            'seller'   => $request->user()->id,
            'sale_box' => $box->id
         ]);
        if(!$transactionid) {
            return $this->json($request, [
                'success' => false
            ], 404);
        }
        $resume = (new SaleBoxDetail)->getResume($transactionid);
        return $this->json($request, [
            'resume' => $resume,
            'box'    => $box->name
        ], 200);
    }
    
    public function discount(Request $request) 
    {
        Log::info(__FUNCTION__ . " PointOfSaleController ");
        try {
            $validator = Validator::make($request->all(), [
                'discount_number' => 'required|numeric|min:0|max:99'
            ]);
            if($validator->fails()){
                Log::info("message=[{$validator->errors()}]");
                throw new \Exception(self::SELL_VALIDATION_ERROR);
            }
            $cartOrder = CartOrder::where('created_by',$request->user()->id)->first();
            $box = SaleBox::where('seller', $request->user()->id)
                ->where('status', 1)
                ->first();
            if(!$cartOrder) {
                throw new \Exception(self::SELL_CART_NOT_FOUND);
            }
            if(!$box) {
                throw new \Exception(self::SELL_BOX_NOT_FOUND);
            }
            $details = CartItem::where('cart_order_id', $cartOrder->id)
            ->select('cart_items.*')
            ->leftJoin('items', 'cart_items.item_id', 'items.id')
            ->where(function($query) {
                $query->orWhere('items.block_discount', '<>', 1)
                ->orWhereNull('items.block_discount');
            })->get();
            foreach ($details as $detail) {
                //revisar items a los cuales no se le puede aplicar descuento
                $detail->discount_percent = $request->input('discount_number');
                $detail->save();
            }
            return $this->json($request, null, 200);
        } catch (\Exception $e) {
            switch($e->getMessage()) {
                case self::SELL_VALIDATION_ERROR :
                    return $this->json($request, [
                        'msg' => 'Campos inválidos'
                    ], 401);
                break;
                case self::SELL_BOX_NOT_FOUND :
                    return $this->json($request, [
                        'msg' => 'No existe caja abierta'
                    ], 401);
                break;
                case self::SELL_CART_NOT_FOUND :
                    return $this->json($request, [
                        'msg' => 'No existe caja'
                    ], 401);
                break;
                default:
                    Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
                    return $this->json($request, null, 500);
                break;
            }
            
        }
    }

    public function updateitemdiscount(Request $request, $id) 
    {
        Log::info(__FUNCTION__ . " PointOfSaleController ");
        $validator = Validator::make($request->all(), [
            'value' => 'required|numeric|min:0|max:99'
        ]);
        if($validator->fails()){
            return $this->json($request, ['msg'=>'Error de Validación'], 401);
        }
        $cartItem = CartItem::find($id);
        if(!$cartItem) {
            return $this->json($request, ['msg'=>'Item no encontrado'], 404);
        }
        $cartItem->discount_percent = $request->input('value');
        $cartItem->save();
        return $this->json($request, null, 200);
    }

    public function updatelocation(Request $request, $id) 
    {
        Log::info(__FUNCTION__ . " PointOfSaleController ");
        $validator = Validator::make($request->all(), [
            'location_id' => 'required|integer'
        ]);
        if($validator->fails()){
            return $this->json($request, ['msg'=>'Error de Validación'], 401);
        }
        $cartItem = CartItem::find($id);
        if(!$cartItem) {
            return $this->json($request, ['msg'=>'Item no encontrado'], 404);
        }
        $cartItem->location_id = $request->input('location_id');
        $cartItem->save();
        return $this->json($request, null, 200);
    }

    public function print(Request $request, $id) 
    {
        Log::info(__FUNCTION__ . " PointOfSaleController ");
        $saleData = Sale::find($id);
        if ($saleData == null) {
            return $this->json($request, null, 404);
        }
        $info = [
           'details' => $saleData->getPdfInfo()
        ];
        if (count($info['details']) == 0) {
            //NO POSEE ORDENES DE SALIDA (ITEMS SIN CODIGO)
            return $this->json($request, null, 403);
        }
        $maxlength = 0;
        foreach($info['details'] as $det ) {
            $length  = 0;
            $length += 400; //BASE
            $length += count($det['details'])*20;
            if($length > $maxlength ) $maxlength = $length;
        }

        $filePath = storage_path('app/public').'/withdraw/';
        if(!file_exists($filePath)) {
            mkdir($filePath, 0755, true);
        }

        $path = ".pos.".config('app.name');
        $pdf = PDF::loadView('pdf'.$path.'.pos', $info)
                ->setPaper(array(10,0,311.80 ,  $maxlength));
        $pdf->save(storage_path('app/public').'/withdraw/'.$saleData->id.'.pdf');
        return $this->json($request, [
            'success' => true,
            'url' => asset('storage/withdraw').'/'.$saleData->id.'.pdf'
        ], 200);
    }

    public function vouchers(Request $request) 
    {
        Log::info(__FUNCTION__ . " PointOfSaleController ");
        $params = [];
        $params['rut_user'] = $request->rut_user;
        $params['type'] = $request->type;
        $params['folio'] = $request->folio;

        try {
            $AFE      = new Afe();
            $response = $AFE->getVoucher($params);
            if(!$response['success']) {
                throw new \Exception(self::AFE_PLATFORM_ERROR);
            }
            return $this->json($request, $response, 200);
        } catch (\Exception $e) {
            switch($e->getMessage()) {
                case self::AFE_PLATFORM_ERROR :
                    return $this->json($request, [
                        'msg' => 'Error en plataforma AFE, contacte al administrador del sistema'
                    ], 401);
                break;
                default:
                    Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
                    return $this->json($request, null, 500);
                break;
            }
        }        
    }

}
