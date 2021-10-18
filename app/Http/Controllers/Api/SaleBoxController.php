<?php

namespace App\Http\Controllers\Api;

use App\Config;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\SaleBox;
use App\Location;
use App\SaleBoxDetail;
use App\Enum\TransactDetails;
use App\SaleBoxDocument;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade as PDFDOM;
use Validator;
use Uuid;
use DB;
use Illuminate\Support\Facades\Schema;
use Log;
use NumberFormatter;

class SaleBoxController extends ApiController
{
    const SALE_BOX_NO_EXISTS          = 1;
    const SALE_BOX_ALREADY_OPEN       = 2;
    const SALE_BOX_ALREADY_CLOSED     = 3;
    const SALE_BOX_TRANSACT_NOT_FOUND = 4;
    const SALE_BOX_ALREADY_OWNED      = 5;
    const SALE_BOX_USER_ALREADY_OPENED_OTHER_BOX = 6;



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


        $datas = new SaleBox();
        //$enterprise = $request->user()->enterprise;
        //$datas = $datas->where('enterprise_id', $enterprise->id);
        $data->recordsTotal = $datas->count();

        if($start !== null && $length !== null) {


            if($order !== null) {
                $datas = $datas->orderBy($field, $dir);
            }

            $data->draw = $draw;
            $data->recordsFiltered = $datas->count();
            $datas = $datas->offset($start)->limit($length);
        }

        $data->sale_boxes = $datas->get();
        $data->user_id    = $request->user()->id;
        if($data->recordsFiltered == 0) $data->recordsFiltered = $datas->count();

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
            $data = new SaleBox();
            $data->fill($request->all());
            $data->location_id = Location::first()->id;
            $data->save();
            DB::commit();
            return $this->json($request, $data, 201);

        } catch(\Exception $e) {
            DB::rollback();
            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
            return $this->json($request, null, 500);
        }
    }

    public function activeBoxes(Request $request){
        $activeBoxes = SaleBox::where('status', '1')
                                 ->get([
                                     'id',
                                     'name'
                                 ]);
        return $this->json($request, $activeBoxes, 200);
    }

     /**
     * UPDATE sale box status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateSaleBoxStatus(Request $request)
    {
        try {
            DB::beginTransaction();
            switch($request->get('box_status')) {
                case 'open':
                    $sale_box = SaleBox::find($request->get('box_sales_id'));
                    if (!$sale_box) {
                        throw new \Exception(self::SALE_BOX_NO_EXISTS);
                    }
                    $sbd = new SaleBox();
                    if ($sale_box->status == 1) {
                        throw new \Exception(self::SALE_BOX_ALREADY_OPEN);
                    }
                    if(!$sbd->checkOtherOrders($sale_box, $request->user()->id)) {
                        throw new \Exception(self::SALE_BOX_USER_ALREADY_OPENED_OTHER_BOX);
                    }
                    $sale_box->seller = $request->user()->id;
                    $sale_box->status = 1;
                    $sale_box->save();
                    //CREAMOS REGISTROS 
                    //APERTURA DE CAJA 
                    $transactid = Uuid::generate()->string;
                    $sbd->addTransaction([
                        'seller'       => $sale_box->seller,
                        'sale_box_id'  => $sale_box->id,
                        'transact_id'  => $transactid,
                        'type'         => TransactDetails::SALE_BOX_OPEN,
                        'amount'       => 0,
                        'doc_id'       => null,
                        'observations' => ''
                    ]);
                    if ($request->input('balance') > 0) {
                        $sbd->addTransaction([
                            'seller'       => $sale_box->seller,
                            'sale_box_id'  => $sale_box->id,
                            'transact_id'  => $transactid,
                            'type'         => TransactDetails::BALANCE_ADD,
                            'amount'       => $request->input('balance'),
                            'doc_id'       => null,
                            'observations' => ''
                        ]);
                    }
                break;
                case 'close':
                    $sale_box = SaleBox::where('seller', $request->user()->id)->first();
                    if (!$sale_box) {
                        throw new \Exception(self::SALE_BOX_NO_EXISTS);
                    }
                    $sbd = new SaleBox();
                    if ($sale_box->status == 0) {
                        throw new \Exception(self::SALE_BOX_ALREADY_CLOSED);
                    }
                    if ($sale_box->seller != $request->user()->id) {
                        throw new \Exception(self::SALE_BOX_ALREADY_OWNED);
                    }
                    $sale_box->seller = null;
                    $sale_box->status = 0;
                    $sale_box->save();
                    //CIERRA CAJA
                    $transactionid = $sbd->searchTransaction([
                        'seller'   => $request->user()->id,
                        'sale_box' => $sale_box->id
                    ]);
                    if(!$transactionid) {
                        throw new \Exception(self::SALE_BOX_TRANSACT_NOT_FOUND);
                    }

                    $diffBox = $request->get("real_cash") - $request->get("total_box");

                    if ($diffBox == 0) {
                        $diffBox = "";
                    }
                    $sbd->addTransaction([
                        'seller'       => $request->user()->id,
                        'sale_box_id'  => $sale_box->id,
                        'transact_id'  => $transactionid,
                        'type'         => TransactDetails::SALE_BOX_CLOSE,
                        'amount'       => $request->get('real_cash'),
                        'doc_id'       => null,
                        'observations' => $diffBox
                    ]);

                    if(Schema::hasTable('sale_box_documents')) {
                        /* Generar PDf */
                        try {
                            $filePath = storage_path('app/public').'/caja/';
                            if(!file_exists($filePath)) {
                                mkdir($filePath, 0755, true);
                            }
    
                            $resume = (new SaleBoxDetail)->getResume($transactionid);
                            $fechaInicio = SaleBoxDetail::where('transact_id', $transactionid)
                            ->where('type', TransactDetails::SALE_BOX_OPEN)->first()->created_at;
                            $fechaTermino = SaleBoxDetail::where('transact_id', $transactionid)
                            ->where('type', TransactDetails::SALE_BOX_CLOSE)->first()->created_at;
    
                            $info = new \stdClass;
                            
                            $info->boxCoins = json_decode($request->input('box_coins'));
                            $info->realCash = $request->get('real_cash');
                            $info->boxName = $sale_box->name;
                            $info->box = $resume;
                            $info->tipoCaja = 0;
                            $tipoCaja = Config::where('param', 'TIPO_CAJA')->firsT();
                            if($tipoCaja != null) {
                                $info->tipoCaja = $tipoCaja->value;
                            }
                            $info->fechaInicio = Carbon::createFromFormat('Y-m-d H:i:s', $fechaInicio, 'UTC')
                                ->setTimezone('America/Santiago')
                                ->format('d-m-Y');
                            $info->horaInicio = Carbon::createFromFormat('Y-m-d H:i:s', $fechaInicio, 'UTC')
                                ->setTimezone('America/Santiago')
                                ->format('H:i:s');
                            $info->fechaTermino = Carbon::createFromFormat('Y-m-d H:i:s', $fechaTermino, 'UTC')
                                ->setTimezone('America/Santiago')
                                ->format('d-m-Y');
                            $info->horaTermino = Carbon::createFromFormat('Y-m-d H:i:s', $fechaTermino, 'UTC')
                                ->setTimezone('America/Santiago')
                                ->format('H:i:s');
    
    
                            $formatter = new NumberFormatter( 'es_CL', NumberFormatter::CURRENCY);
                            $info->box['total_box'] = $formatter->formatCurrency($info->box['total_box'], 'CLP');
                            $info->box['total_cash'] = $formatter->formatCurrency($info->box['total_cash'], 'CLP');
                            $info->box['total_card'] = $formatter->formatCurrency($info->box['total_card'], 'CLP');
                            $info->box['total_cheque'] = $formatter->formatCurrency($info->box['total_cheque'], 'CLP');
                            $info->box['total_rounding'] = $formatter->formatCurrency($info->box['total_rounding'], 'CLP');
                            $info->box['total_app'] = $formatter->formatCurrency($info->box['total_app'], 'CLP');
                            $info->box['total_transfer'] = $formatter->formatCurrency($info->box['total_transfer'], 'CLP');
                            $info->box['total_credit_note'] = $formatter->formatCurrency($info->box['total_credit_note'], 'CLP');
                            $info->box['total_income'] = $formatter->formatCurrency($info->box['total_income'], 'CLP');
                            $info->box['total_expenses'] = $formatter->formatCurrency($info->box['total_expenses'], 'CLP');
                            $info->box['total_intern'] = $formatter->formatCurrency($info->box['total_intern'], 'CLP');
                            $info->box['total_ticket'] = $formatter->formatCurrency($info->box['total_ticket'], 'CLP');
                            $info->box['total_invoice'] = $formatter->formatCurrency($info->box['total_invoice'], 'CLP');
                            $info->box['total_calculated'] = $formatter->formatCurrency($info->box['total_calculated'], 'CLP');
                            $info->box['smallbox'] = $formatter->formatCurrency($info->box['smallbox'], 'CLP');
                            $info->box['sales_total'] = $formatter->formatCurrency($info->box['sales_total'], 'CLP');
                            $info->realCash = $formatter->formatCurrency($info->realCash, 'CLP');
                            if($info->tipoCaja == 1) {
                                $info->boxCoins->val20k = $formatter->formatCurrency($info->boxCoins->val20k, 'CLP');
                                $info->boxCoins->val10k = $formatter->formatCurrency($info->boxCoins->val10k, 'CLP');
                                $info->boxCoins->val5k = $formatter->formatCurrency($info->boxCoins->val5k, 'CLP');
                                $info->boxCoins->val2k = $formatter->formatCurrency($info->boxCoins->val2k, 'CLP');
                                $info->boxCoins->val1k = $formatter->formatCurrency($info->boxCoins->val1k, 'CLP');
                                $info->boxCoins->val500 = $formatter->formatCurrency($info->boxCoins->val500, 'CLP');
                                $info->boxCoins->val100 = $formatter->formatCurrency($info->boxCoins->val100, 'CLP');
                                $info->boxCoins->val50 = $formatter->formatCurrency($info->boxCoins->val50, 'CLP');
                                $info->boxCoins->val10 = $formatter->formatCurrency($info->boxCoins->val10, 'CLP');
                                $info->boxCoins->val5 = $formatter->formatCurrency($info->boxCoins->val5, 'CLP');
                                $info->boxCoins->val1 = $formatter->formatCurrency($info->boxCoins->val1, 'CLP');    
                            }
    
                            /* 
                                val = val.toString().replace('.', ',');
                                return val.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");        
                            */
                            $data  = [
                                'info' => $info,
                                'id'    => 'CLOSE'.$transactionid
                            ];
    
                            $path = ".pos.geostore";
    
                            $pdf = PDFDOM::loadView('pdf'.$path.'.sale-box', $data);
                            $curDate = Carbon::createFromFormat('Y-m-d H:i:s', $fechaInicio, 'UTC')
                            ->setTimezone('America/Santiago')
                            ->format('Y-m-d_H-i-s');
    
                            $halfPath = '/caja/'.$transactionid.'_'.$curDate.'.pdf';
                            $fullPath = storage_path('app/public').$halfPath;
    
                            $pdf->save($fullPath);
    
                            $saleBoxDocument = new SaleBoxDocument();
                            $saleBoxDocument->sale_box_transact_id = $transactionid;
                            $saleBoxDocument->filename = $halfPath;
                            $saleBoxDocument->save();
                        } catch (\Exception $e) {
                            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
                        }
                    }
                break;
                case 'balance':
                    $sale_box = SaleBox::where('seller', $request->user()->id)
                          ->where('status', 1)
                          ->first();

                    if(!$sale_box) {
                        throw new \Exception(self::SALE_BOX_NO_EXISTS);
                    }
                    $transactionid = $sale_box->searchTransaction([
                        'seller'   => $request->user()->id,
                        'sale_box' => $sale_box->id
                    ]); 
                    if(!$transactionid) {
                        throw new \Exception(self::SALE_BOX_TRANSACT_NOT_FOUND);
                    }
                    $sale_box->addTransaction([
                        'seller'       => $request->user()->id,
                        'sale_box_id'  => $sale_box->id,
                        'transact_id'  => $transactionid,
                        'type'         => TransactDetails::BALANCE_ADD,
                        'amount'       => $request->get('cash_balance'),
                        'doc_id'       => null,
                        'observations' => $request->get('balance_observation').'| Retira/Agrega Efectivo'
                    ]);

                break;
            }
            DB::commit();

            return $this->json($request, $sale_box, 201);

        } catch(\Exception $e) {
            DB::rollback();
            switch($e->getMessage()) {
                case self::SALE_BOX_NO_EXISTS:
                    return $this->json($request, [
                        'msg' => 'No existe Caja'
                    ], 401);
                break;
                case self::SALE_BOX_ALREADY_OPEN:
                    return $this->json($request, [
                        'msg' => 'Caja ya se encuentra abierta'
                    ], 401);
                break;
                case self::SALE_BOX_ALREADY_CLOSED:
                    return $this->json($request, [
                        'msg' => 'Caja ya se encuentra cerrada'
                    ], 401);
                break;
                case self::SALE_BOX_TRANSACT_NOT_FOUND:
                    return $this->json($request, [
                        'msg' => 'Error en la transacción, contacte al administrador del sistema'
                    ], 401);
                break;
                case self::SALE_BOX_ALREADY_OWNED:
                    return $this->json($request, [
                        'msg' => 'Caja se encuentra asociada a otro usuario'
                    ], 401);
                break;
                case self::SALE_BOX_USER_ALREADY_OPENED_OTHER_BOX:
                    return $this->json($request, [
                        'msg' => 'Cajero ya abrió otra caja'
                    ], 401);
                break;
                default:
                    Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
                    return $this->json($request, null, 500);
                break;
            }
            
        }
    }


     private function rules()
    {
        switch ($this->_method)
        {
            case 'POST':
                return [
                    'name' => 'required|max:150|string'
                ];
                break;

            case 'PUT':
                return [
                    'name' => 'required|max:150|string'
                ];
                break;
        }

    }
}