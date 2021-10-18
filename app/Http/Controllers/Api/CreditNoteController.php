<?php

namespace App\Http\Controllers\Api;

use DB;
use Log;
use App\Sale;
use Validator;
use App\Client;
use App\Comune;
use App\SaleDetail;
use App\CreditNote;
use App\SaleBox;
use App\Location;
use App\Adjustment;
use App\AdjustmentItem;
use App\User;
use App\Transact;
use App\UnitOfMeasure;
use App\StockItem;
use App\Libraries\Afe;
use App\Libraries\Utils;
use App\CreditNoteDetail;
use App\Enum\CreditNoteTypes;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Enum\TransactDetails;
use App\Rules\Rut;
use Carbon\Carbon;


class CreditNoteController extends ApiController implements  CreditNoteTypes
{
    const CN_VALIDATION_ERROR           = 1;
    const CN_SALE_NOT_FOUND             = 2;
    const CN_HAS_TOTAL                  = 3;
    const AFE_PLATFORM_ERROR            = 4;
    const AFE_CLIENT_NOT_FOUND          = 5;
    const CN_CLIENT_NOT_FOUND           = 6;
    const CN_COMUNE_NOT_FOUND           = 7;
    const CN_INCONSISTENT_ITEM          = 8;
    const CN_INSUFFICIENT_FOLIO         = 9;
    const CN_NO_ENTERPRISE_DATA         = 10;
    const IVA                           = 0.19;
    const SALE_BOX_NO_EXISTS            = 20;
    const SALE_BOX_TRANSACT_NOT_FOUND   = 21;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $start   = is_null($request->get('start')) ? 0 : (int)$request->get('start');
        $length  = is_null($request->get('length')) ? 100 : (int)$request->get('length');
        $columns = $request->get('columns');
        $order   = $request->get('order');
        $orderDirection = isset($order[0]) ? $order[0]['dir'] :  'asc';
        $orderField     = isset($columns[$order[0]['column']]['data']) ? $columns[$order[0]['column']]['data'] : 'id';
        $filters = !$request->input('filters') ? [] : $request->input('filters');
        $result = (new CreditNote)->getPaginated($filters, $start, $length, $orderField, $orderDirection);
         
        foreach ($result['rows'] as $key => $res) {
            
            $client_id = Sale::find($res->sale_id)->client_id;
            $result['rows'][$key]->client = Client::find($client_id)->name;
        }

        return $this->json($request, $result, 200);
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

            $AFE = new Afe();
            DB::beginTransaction();
            $validator = Validator::make($request->all(), [
                'sale_id'   => 'required|integer',
                'type'      => 'required|integer|between:1,3',
                'reason'    => 'required|string'
            ]);
            if ($validator->fails()) {
                throw new \Exception(self::CN_VALIDATION_ERROR);
            }
            $saleData = Sale::find($request->input('sale_id'));
            $reason   = $request->input('reason');
            if ($saleData == null) {
                throw new \Exception(self::CN_SALE_NOT_FOUND);
            }
            $type = $request->input('type');
            if ($type == CreditNoteTypes::TYPE_TEXT) {
                $validator = Validator::make($request->all(), [
                    'where'   => 'required|string',
                    'should'  => 'required|string'
                ]);
                if ($validator->fails()) {
                    throw new \Exception(self::CN_VALIDATION_ERROR);
                }   
            }
            if ($type == CreditNoteTypes::TYPE_PARTIAL) {
                if (!$request->has('items')) {
                    throw new \Exception(self::CN_VALIDATION_ERROR);
                }
                $validator = Validator::make(json_decode($request->input('items')), [
                    '*.id'    => 'required|integer',
                    '*.qtyf'  => 'required|integer'
                ]);
                if ($validator->fails()) {
                    throw new \Exception(self::CN_VALIDATION_ERROR);
                } 
            }

           

            if ($request->input('with_client_data') == 1) {
                $validator = Validator::make($request->all(), [
                    'client_rut'            => ['required','string', new Rut],
                    'client_name'           => 'required|string',
                    'client_industries'     => 'required|string',
                    'client_address'        => 'required|string'
                ]);
                if ($validator->fails()) {
                    throw new \Exception(self::CN_VALIDATION_ERROR);
                }
                $params['rutRecep']     = $request->input('client_rut');
                $params['razon_social'] = $request->input('client_name');
                $params['industries']   = $request->input('client_industries');
                $params['address']      = $request->input('client_address');
                $params['comune']       = Comune::where('id', $request->input('client_comune'))->withTrashed()->first()->comune_detail;
            }else{
                if ($request->input('enterprise') == 1){
                    $rut = explode('-',config('afe.company_rut'));
                    $client = Client::where('rut','=',$rut[0])->first();
                    if($client != null) {
                        $saleData->client_id = $client->id; //RUT LARDANI SI SE USAN DATOS DE EMPRESA
                    } else {
                        throw new \Exception(self::CN_CLIENT_NOT_FOUND);
                    }
                }
                $client = Client::find($saleData->client_id);
                if ($client == null) {
                    throw new \Exception(self::CN_CLIENT_NOT_FOUND);
                }
                $rut = trim($client->rut).'-'.$client->rut_dv;
                $response = $AFE->checkClient($rut);
                if(!$response['success']) {
                        throw new \Exception(self::AFE_PLATFORM_ERROR);
                }

                $params             = [];
                
                $params['rutRecep']     = $rut;
                $params['razon_social'] = $client->name;
                $params['industries']   = $client->industries;
                $params['address']      = $client->address;
                $comune = Comune::where('id', $client->comune_id)->withTrashed()->first();
                if ($comune == null) {
                    throw new \Exception(self::CN_COMUNE_NOT_FOUND);
                }
                $params['comune']       = $comune->comune_detail;//;
            }
            //NO SE PUEDE CREAR UNA NOTA DE CREDITO SI ES QUE YA EXISTE UNA TOTAL
            //ASOCIADA A LA MISMA VENTA
            $totals = CreditNote::where('type', CreditNoteTypes::TYPE_TOTAL)
                                ->where('sale_id', $saleData->id)
                                ->count();
            if ($totals > 0) {
                throw new \Exception(self::CN_HAS_TOTAL);
            }

            $params['details']  = [];
            switch ($type) {
                case CreditNoteTypes::TYPE_TOTAL:
                    $params['observations'] = 'Anulación de documento';
                    $params['CodRef']    = [CreditNoteTypes::TYPE_TOTAL];
                    $params['TpoDocRef'] = [$saleData->type == 1 ? AFE::TICKET : AFE::INVOICE];
                    $params['FolioRef']  = [$saleData->folio];
                    $params['RazonRef']  = [$reason];
                    $params['FchRef']    = [$saleData->date];
                    $items = [];
                    $details = SaleDetail::where('sale_id', $saleData->id)->get();
                    foreach ($details as $detail) {
                        $qty    = CreditNoteDetail::where('sale_detail_id', $detail->id)->sum('qty');
                        $qty_cn = 0;
                        if ($qty != null) {
                            $qty_cn  = $detail->qty - $qty;
                            if ($qty_cn != 0) {
                                $items[] = [
                                    'id'  => $detail->id,
                                    'qty' => $qty_cn,
                                    'item_id' => $detail->item_id,
                                    'item_price' => $detail->price,
                                ];
                            }
                            
                        } else {
                            $qty_cn  = $detail->qty;
                            if ($qty_cn != 0) {
                                $items[] = [
                                    'id'  => $detail->id,
                                    'qty' => $qty_cn,
                                    'item_id' => $detail->item_id,
                                    'item_price' => $detail->price,
                                ];
                            }
                        }
                        if ($qty_cn != 0) {
                            $dt                 = [];
                            $dt['qty']          = $qty_cn;
                            $dt['price']        = $detail->price;
                            $dt['itemName'] = $detail->item_name;
                            $dt['discount'] = $detail->discount_percent;
                            $params['details'][] = $dt;
                        }
                    }
                    //TOTAL
                    $cnote               = new CreditNote();
                    $cnote->folio        = 0;
                    $cnote->sale_id      = $saleData->id;
                    $cnote->type         = $type;
                    $cnote->credit_type  = 1; //TEMPORAL 
                    $cnote->net          = 0;
                    $cnote->tax          = 0;
                    $cnote->total        = 0;
                    $cnote->observations = $reason;
                    $cnote->created_by   = $request->user()->id;
                    $cnote->updated_by   = $request->user()->id;
                    $cnote->save();

                    $location_id = Location::first()->id;
                    
                    $user = User::find($request->user()->id);


                    $adj = new Adjustment();
                    $adj->code = "ADJDNC".Carbon::now()->timestamp;
                    $adj->date = Carbon::now();
                    $adj->reason = "DEVOLUCIÓN POR NOTA DE CREDITO";
                    $adj->location_id = $location_id;
                    $adj->movement_status_id = 3;
                    $adj->enterprise_id = $user->enterprise->id;
                    $adj->created_by = $request->user()->id;
                    $adj->save();
                    
                    foreach($items as $item) {
                        
                        $cdn   = new CreditNoteDetail();
                        $cdn->credit_note_id = $cnote->id;
                        $cdn->sale_detail_id = $item['id'];
                        $cdn->qty = $item['qty'];
                        $cdn->save();

                        if ($item['item_id'] != null){

                            $item_uom = DB::table('items')->where('id', $item['item_id'])->first()->unit_of_measure_id;

                            $adjItem = new AdjustmentItem();
                            $adjItem->adjustment_id      = $adj->id;
                            $adjItem->item_id            = $item['item_id'];
                            $adjItem->quantity           = $item['qty'];
                            $adjItem->unitary_price      = $saleData->type == 1 ? $item['item_price'] : round($item['item_price']*1.19);
                            $adjItem->unit_of_measure_id = $item_uom;
                            $adjItem->created_by = $request->user()->id;
                            $adjItem->save();
                            

                            $transact = new Transact();
                            if ($item['qty'] == '1') {
                                $uom_name = UnitOfMeasure::find($item_uom)->first()->name;
                                $transact->description = 'Se agregó <b>' . $item['qty'] . ' ' . $uom_name . '</b> por devolución de nota de crédito';
                            }else{
                                $uom_name = UnitOfMeasure::find($item_uom)->first()->plural;
                                $transact->description = 'Se agregaron <b>' . $item['qty'] . ' ' . $uom_name . '</b> por devolución de nota de crédito';
                            }
                            $transact->object_id = $item['item_id'];
                            $transact->object_type = "items";
                            $transact->created_by = $request->user()->id;
                            $transact->save();


                            if($uom_name != 1){
                                $stockItem = new StockItem();
                                $stockItem->item_id     = $item['item_id'];
                                $stockItem->qty         = $item['qty'];
                                $stockItem->price       = $item['item_price'];
                                $stockItem->location_id = $location_id;
                                $stockItem->adjustment_item_id = $adjItem->id;
                                $stockItem->save();
                            }else{
                                for ($x = 0; $x < $item['qty']; $x++) {
                                    $stockItem = new StockItem();
                                    $stockItem->item_id     = $item['item_id'];
                                    $stockItem->price       = $item['item_price'];
                                    $stockItem->location_id = $location_id;
                                    $stockItem->adjustment_item_id = $adjItem->id;
                                    $stockItem->save();
                                }
                            }
                        }
                    }
                break;
                case CreditNoteTypes::TYPE_PARTIAL:
                    $params['observations'] = 'Ajuste Parcial';
                    $params['CodRef']    = [CreditNoteTypes::TYPE_PARTIAL];
                    $params['TpoDocRef'] = [$saleData->type == 1 ? AFE::TICKET : AFE::INVOICE];
                    $params['FolioRef']  = [$saleData->folio];
                    $params['RazonRef']  = [$reason];
                    $params['FchRef']    = [$saleData->date];

                    $fixeds = json_decode($request->input('items'));

                    $items  = [];
                    //VALIDAMOS QUE LAS CANTIDADES SEAN LAS SUFICIENTES    
                    foreach ($fixeds as $fixed) {
                        //DESCARTAMOS LOS ITEMS CON CANTIDAD 0
                        if($fixed->qtyf != 0) {
                            $dt   = SaleDetail::find($fixed->id);
                            $qty  = CreditNoteDetail::where('sale_detail_id', $fixed->id)->sum('qty');
                            if ($qty != null) {
                                if ( ($dt->qty - $fixed->qtyf - $qty) < 0) {
                                    throw new \Exception(self::CN_INCONSISTENT_ITEM);
                                }
                            } else {
                                if ( ($dt->qty - $fixed->qtyf) < 0) {
                                    throw new \Exception(self::CN_INCONSISTENT_ITEM);
                                }
                            }
                            $items[] = [
                                'id'  => $fixed->id,
                                'qty' => $fixed->qtyf,
                                'item_id' => $dt->item_id,
                                'item_price' => $dt->price,
                            ];
                            $it                  = [];
                            $it['qty']           = $fixed->qtyf;
                            $it['price']         = $dt->price;
                            $it['itemName']      = $dt->item_name;
                            $it['item_id']      = $dt->item_id;
                            $it['item_price']      = $dt->price;
                            $it['discount']      = $dt->discount_percent;
                            $params['details'][] = $it;
                        }
                    }
                    $cnote               = new CreditNote();
                    $cnote->folio        = 0;
                    $cnote->sale_id      = $saleData->id;
                    $cnote->type         = $type;
                    $cnote->credit_type  = 1; //TEMPORAL 
                    $cnote->net          = 0;
                    $cnote->tax          = 0;
                    $cnote->total        = 0;
                    $cnote->observations = 'Ajuste Parcial';
                    $cnote->created_by   = $request->user()->id;
                    $cnote->updated_by   = $request->user()->id;
                    $cnote->save();

                    $location_id = Location::first()->id;
                    
                    $user = User::find($request->user()->id);


                    $adj = new Adjustment();
                    $adj->code = "ADJDNC".Carbon::now()->timestamp;
                    $adj->date = Carbon::now();
                    $adj->reason = "DEVOLUCIÓN POR NOTA DE CREDITO";
                    $adj->location_id = $location_id;
                    $adj->movement_status_id = 3;
                    $adj->enterprise_id = $user->enterprise->id;
                    $adj->created_by = $request->user()->id;
                    $adj->save();


                    foreach($items as $item) {

                        $cdn   = new CreditNoteDetail();
                        $cdn->credit_note_id = $cnote->id;
                        $cdn->sale_detail_id = $item['id'];
                        $cdn->qty = $item['qty'];
                        $cdn->save();

                        if ($item['item_id'] != null){
                            
                            $item_uom = DB::table('items')->where('id', $item['item_id'])->first()->unit_of_measure_id;

                            $adjItem = new AdjustmentItem();
                            $adjItem->adjustment_id      = $adj->id;
                            $adjItem->item_id            = $item['item_id'];
                            $adjItem->quantity           = $item['qty'];
                            $adjItem->unitary_price      = $saleData->type == 1 ? $item['item_price'] : round($item['item_price']*1.19);
                            $adjItem->unit_of_measure_id = $item_uom;
                            $adjItem->created_by = $request->user()->id;
                            $adjItem->save();
                            

                            $transact = new Transact();
                            if ($item['qty'] == '1') {
                                $uom_name = UnitOfMeasure::find($item_uom)->first()->name;
                                $transact->description = 'Se agregó <b>' . $item['qty'] . ' ' . $uom_name . '</b> por devolución de nota de crédito';
                            }else{
                                $uom_name = UnitOfMeasure::find($item_uom)->first()->plural;
                                $transact->description = 'Se agregaron <b>' . $item['qty'] . ' ' . $uom_name . '</b> por devolución de nota de crédito';
                            }
                            $transact->object_id = $item['item_id'];
                            $transact->object_type = "items";
                            $transact->created_by = $request->user()->id;
                            $transact->save();


                            if($uom_name != 1){
                                $stockItem = new StockItem();
                                $stockItem->item_id     = $item['item_id'];
                                $stockItem->qty         = $item['qty'];
                                $stockItem->price       = $item['item_price'];
                                $stockItem->location_id = $location_id;
                                $stockItem->adjustment_item_id = $adjItem->id;
                                $stockItem->save();
                            }else{
                                for ($x = 0; $x < $item['qty']; $x++) {
                                    $stockItem = new StockItem();
                                    $stockItem->item_id     = $item['item_id'];
                                    $stockItem->price       = $item['item_price'];
                                    $stockItem->location_id = $location_id;
                                    $stockItem->adjustment_item_id = $adjItem->id;
                                    $stockItem->save();
                                }
                            }
                        }
                    }
                break;
                case CreditNoteTypes::TYPE_TEXT:
                    $params['observations'] = 'Cambio de texto';
                    $params['CodRef']    = [CreditNoteTypes::TYPE_TEXT];
                    $params['TpoDocRef'] = [$saleData->type == 1 ? AFE::TICKET : AFE::INVOICE];
                    $params['FolioRef']  = [$saleData->folio];
                    $params['RazonRef']  = [$reason];
                    $params['FchRef']    = [$saleData->date];
                    $params['NmbItem']   = [$request->input('where'), $request->input('should')];

                    $cnote               = new CreditNote();
                    $cnote->folio        = 0;
                    $cnote->sale_id      = $saleData->id;
                    $cnote->type         = $type;
                    $cnote->credit_type  = 1; //TEMPORAL 
                    $cnote->net          = 0;
                    $cnote->tax          = 0;
                    $cnote->total        = 0;
                    $cnote->observations = $reason.' DONDE DICE :'.$request->input('where').' DEBE DECIR:'.$request->input('should');
                    $cnote->created_by   = $request->user()->id;
                    $cnote->updated_by   = $request->user()->id;
                    $cnote->save();
                break;
            }

            $response = $AFE->makeCreditNote($params);
            if (!$response['success']) {
                if ($response['msg'] == '1') {
                    throw new \Exception(self::AFE_CLIENT_NOT_FOUND);
                } else if ($response['msg'] == '2') {
                    throw new \Exception(self::CN_INSUFFICIENT_FOLIO);
                } else if ($response['msg'] == '3') {
                    throw new \Exception(self::CN_NO_ENTERPRISE_DATA);
                } 
                else throw new \Exception(self::AFE_PLATFORM_ERROR);
            }
            $cnote->folio = $response['folio'];
            $cnote->net   = $response['net'];
            $cnote->tax   = $response['tax'];
            $cnote->total = $response['total'];

            if ($request->input('sale_box_movement') != 0){
                $sale_box = SaleBox::where('status', 1)
                          ->first();

                    if(!$sale_box) {
                        throw new \Exception(self::SALE_BOX_NO_EXISTS);
                    }
                    $transactionid = $sale_box->searchTransaction([
                        'seller'   => $sale_box->seller,
                        'sale_box' => $sale_box->id
                    ]); 
                    if(!$transactionid) {
                        throw new \Exception(self::SALE_BOX_TRANSACT_NOT_FOUND);
                    }
                    $sale_box->addTransaction([
                        'seller'       => $sale_box->seller,
                        'sale_box_id'  => $sale_box->id,
                        'transact_id'  => $transactionid,
                        'type'         => TransactDetails::SALE_BOX_CREDIT_NOTE,
                        'amount'       => $response['total']*-1,
                        'doc_id'       => $response['folio'],
                        'observations' => 'Devolución por nota de crédito'
                    ]);
            }

            $cnote->save();
            DB::commit();
            return $this->json($request, $cnote, 200);
        } catch (\Exception $e) {
            DB::rollback();
            switch($e->getMessage()) {
                case self::CN_VALIDATION_ERROR :
                    return $this->json($request,[
                        'msg' => 'Error de validación, verifique los campos a ingresar'
                    ], 401);
                break;
                case self::CN_HAS_TOTAL :
                    return $this->json($request,[
                        'msg' => 'Ya existe una nota de crédito TOTAL asociado a este documento'
                    ], 401);
                break;
                case self::CN_SALE_NOT_FOUND:
                    return $this->json($request,[
                        'msg' => 'Documento no existe'
                    ], 404);
                break;
                case self::AFE_PLATFORM_ERROR :
                    return $this->json($request, [
                        'msg' => 'Error en plataforma AFE, contacte al administrador del sistema'
                    ], 401);
                break;
                case self::AFE_CLIENT_NOT_FOUND:
                    return $this->json($request, [
                        'msg' => 'Error en plataforma AFE : Cliente no se encuentra registrado'
                    ], 401);
                break;
                case self::CN_CLIENT_NOT_FOUND:
                    return $this->json($request, [
                        'msg' => 'Cliente no se encuentra registrado'
                    ], 401);
                break;
                case self::CN_COMUNE_NOT_FOUND:
                    return $this->json($request, [
                        'msg' => 'Cliente no posee comuna registrada'
                    ], 401);
                break;
                case self::CN_INCONSISTENT_ITEM:
                    return $this->json($request, [
                        'msg' => 'Uno de los items excede la cantidad disponible para reintegrar del documento'
                    ], 401);
                break;
                case self::CN_INSUFFICIENT_FOLIO:
                    return $this->json($request, [
                        'msg' => 'No hay folios disponibles para este tipo de documento'
                    ], 401);
                break;
                case self::CN_NO_ENTERPRISE_DATA:
                    return $this->json($request, [
                        'msg' => 'Nota de crédito no puede ser generada sin datos de cliente. Utilice los datos de la empresa'
                    ], 401);
                break;
                case self::SALE_BOX_NO_EXISTS:
                    return $this->json($request, [
                        'msg' => 'No existe Caja'
                    ], 401);
                break;
                case self::SALE_BOX_TRANSACT_NOT_FOUND:
                    return $this->json($request, [
                        'msg' => 'Error en la transacción, contacte al administrador del sistema'
                    ], 401);
                break;
                default:
                    Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
                    return $this->json($request, null, 500);
                break;
            }
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

    public function search(Request $request, $type, $folio) {
        $saleData = Sale::where('type', $type)
                        ->where('folio', $folio)
                        ->first();
        if($saleData == null) {
            return $this->json($request, [
                'error' => 'No existe Boleta / Factura'
            ], 404);
        }
        //SEARCH FOR CREDIT NOTES WITH TOTAL type
        $creditData = CreditNote::where('sale_id', $saleData->id)
                                ->where('type', CreditNoteTypes::TYPE_TOTAL )
                                ->count();
        if ($creditData > 0) {
            return $this->json($request, [
                'error' => 'Ya existe una nota de crédito TOTAL asociada a la Boleta/Factura'
            ], 401);
        }

        $details = $saleData->getCreditDetails();

        $info = [
            'client_name' => Client::find($saleData->client_id)->name,
            'details'     => $details,
            'type'        => $saleData->type,
            'folio'       => $saleData->folio,
            'id'          => $saleData->id
        ];
        return $this->json($request, $info, 200);
    }

    public function busca(Request $request) {

        
                $params = [];
                $params['folio'] = $request->folio;
        
                try {
                    $AFE      = new Afe();
                    $response = $AFE->getCreditNotes($params);
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
