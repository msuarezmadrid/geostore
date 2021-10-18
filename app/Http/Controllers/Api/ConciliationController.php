<?php

namespace App\Http\Controllers\Api;

use DB;
use Log;
use App\Sale;
use Validator;
use App\Client;
use App\Conciliation;
use App\ConcilliationDoc;
use App\ConciliationDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Carbon\Carbon;


class ConciliationController extends ApiController
{
    private const SALE_TYPE = 1;
    private const CDOC_TYPE = 2;

    private const SALE_NO_CONCILIATED      = 1;
    private const SALE_PARTIAL_CONCILIATED = 2;
    private const SALE_CONCILIATED         = 3;

    private const CONCILIATION_VALIDATION_ERROR         = 1;
    private const CONCILIATION_CLIENT_NOT_FOUND         = 2;
    private const CONCILIATION_DOC_ALREADY_CONCILIATED  = 3;
    private const CONCILIATION_DIFF_AMOUNTS             = 4;
    private const CONCILIATION_SALE_ALREADY_CONCILIATED = 5;
    private const CONCILIATION_SALE_DIFFERENCE          = 6;
    private const CONCILIATION_NOT_FOUND                = 7;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Conciliation $con)
    {
        $start   = is_null($request->get('start')) ? 0 : (int)$request->get('start');
        $length  = is_null($request->get('length')) ? 100 : (int)$request->get('length');
        $columns = $request->get('columns');
        $order   = $request->get('order');
        $orderDirection = isset($order[0]) ? $order[0]['dir'] :  'asc';
        $orderField     = isset($columns[$order[0]['column']]['data']) ? $columns[$order[0]['column']]['data'] : 'id';
        $filters = !$request->input('filters') ? [] : $request->input('filters');
        $result = $con->getPaginated($filters, $start, $length, $orderField, $orderDirection);
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
                DB::beginTransaction();
                $validator = Validator::make($request->all(),[
                    'client_id'         => 'required|integer',
                    'conciliation_docs' => 'required|json',
                    'documents'         => 'required|json'
                ]);
                if ($validator->fails()) { 
                    Log::info("message=[{$validator->errors()}]");
                    $errors = ['errors' => $validator->errors()];
                    throw new \Exception(self::CONCILIATION_VALIDATION_ERROR);
                }
                $client = Client::find($request->input('client_id'));
                if (!$client) {
                    throw new \Exception(self::CONCILIATION_CLIENT_NOT_FOUND);
                }
                $documents     = json_decode($request->input('documents'));
                $con_documents = json_decode($request->input('conciliation_docs'));

                if (!is_array($con_documents)) {
                    throw new \Exception(self::CONCILIATION_VALIDATION_ERROR);    
                }

                $doc_amount      = 0;
                $conc_doc_amount = 0;

                foreach ($con_documents as $doc) {
                    $conc_doc = ConcilliationDoc::find($doc);
                    if (!$conc_doc) {
                        throw new \Exception(self::CONCILIATION_VALIDATION_ERROR); 
                    }
                    $conc_doc_amount += $conc_doc->amount;
                }

                foreach ($documents as $document) {
                    $sale = Sale::find($document->id);
                    
                    if (!$sale) {
                        throw new \Exception(self::CONCILIATION_VALIDATION_ERROR);
                    }
                    $doc_amount += $document->amount;
                }

                if ($doc_amount != $conc_doc_amount) {
                    throw new \Exception(self::CONCILIATION_DIFF_AMOUNTS);
                }

                $conciliation              = new Conciliation();
                $conciliation->client_id   = $client->id;
                $conciliation->created_by  = $request->user()->id;
                $conciliation->updated_by  = $request->user()->id;
                $conciliation->save();

                //DETAILS
                //FOR CONCILIATION DOCS
                foreach ($con_documents as $doc) {
                    $conc_doc = ConcilliationDoc::find($doc);
                    if ($conc_doc->status == 'Conciliado') {
                        throw new \Exception(self::CONCILIATION_DOC_ALREADY_CONCILIATED);
                    }
                    $conc_doc->status = 'Conciliado';
                    $conc_doc->save();
                    $conciliation_detail = new ConciliationDetail();
                    $conciliation_detail->conciliation_id = $conciliation->id;
                    $conciliation_detail->doc_id          = $conc_doc->id;
                    $conciliation_detail->type            = self::CDOC_TYPE;
                    $conciliation_detail->amount          = 0;
                    $conciliation_detail->save();
                }
                //FOR SALES
                foreach ($documents as $doc) {
                    $sale = Sale::find($doc->id);
                    if ($sale->conciliation_status_id == self::SALE_CONCILIATED) {
                        throw new \Exception(self::CONCILIATION_SALE_ALREADY_CONCILIATED);
                    }
                    //REVISAR MONTOS
                    if ( ($doc->amount + $sale->conciliated) > $sale->total ) {
                        throw new \Exception(self::CONCILIATION_SALE_DIFFERENCE);
                    }
                    $sale->conciliated += $doc->amount;
                    $sale->updated_at = Carbon::now();
                    if ($sale->conciliated == $sale->total) {
                        $sale->conciliation_status_id = self::SALE_CONCILIATED;
                    } else {
                        $sale->conciliation_status_id = self::SALE_PARTIAL_CONCILIATED;
                    }
                    $sale->save();
                    $conciliation_detail = new ConciliationDetail();
                    $conciliation_detail->conciliation_id = $conciliation->id;
                    $conciliation_detail->doc_id          = $sale->id;
                    $conciliation_detail->type            = self::SALE_TYPE;
                    $conciliation_detail->amount          = $doc->amount;
                    $conciliation_detail->save();
                }
                DB::commit();
                return $this->json($request, null, 200);

        } catch (\Exception $e) {
            DB::rollback();
            switch($e->getMessage()) {
                case self::CONCILIATION_VALIDATION_ERROR:
                    return $this->json($request, [
                        'msg' => 'Error de validación'
                    ], 401);
                break;
                case self::CONCILIATION_CLIENT_NOT_FOUND:
                    return $this->json($request, [
                        'msg' => 'Cliente no existe'
                    ], 401);
                break;
                case self::CONCILIATION_DOC_ALREADY_CONCILIATED:
                    return $this->json($request, [
                        'msg' => 'Uno de los documentos a conciliar ya se encuentra conciliado'
                    ], 401);
                break;
                case self::CONCILIATION_DIFF_AMOUNTS:
                    return $this->json($request, [
                        'msg' => 'Existe una diferencia entre en total de documentos a conciliar y el total de Facturas/Boletas a conciliar'
                    ], 401);
                break;
                case self::CONCILIATION_SALE_ALREADY_CONCILIATED:
                    return $this->json($request, [
                        'msg' => 'Una de las facturas/boletas ya se encuentra conciliada'
                    ], 401);
                break;
                case self::CONCILIATION_SALE_DIFFERENCE:
                    return $this->json($request, [
                        'msg' => 'Monto de una de las facturas/boletas excede monto total'
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
    public function destroy(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $conciliation = Conciliation::find($id);
            if (!$conciliation) {
                throw new \Exception(self::CONCILIATION_NOT_FOUND);
            }
            $conDetails = ConciliationDetail::where('conciliation_id', $conciliation->id)->get();
            foreach ($conDetails as $detail) {
                switch($detail->type) {
                    case self::CDOC_TYPE:
                        $cdoc = ConcilliationDoc::find($detail->doc_id);
                        $cdoc->status = 'No Conciliado';
                        $cdoc->save();
                    break;
                    case self::SALE_TYPE:
                        $sale = Sale::find($detail->doc_id);
                        if ( ($sale->conciliated - $detail->amount) < 0) {
                            throw new \Exception(self::CONCILIATION_SALE_DIFFERENCE);
                        }
                        if ( ($sale->conciliated - $detail->amount) == 0) {
                            $sale->conciliated -= $detail->amount;
                            $sale->conciliation_status_id = self::SALE_NO_CONCILIATED;
                            $sale->save();
                        }
                        if ( ($sale->conciliated - $detail->amount) > 0) {
                            $sale->conciliated -= $detail->amount;
                            $sale->conciliation_status_id = self::SALE_PARTIAL_CONCILIATED;
                            $sale->save();
                        }
                    break;
                }
                $detail->delete();
            }
            $conciliation->delete();
            DB::commit();
            return $this->json($request, null, 200);
        } catch (\Exception $e) {
            DB::rollback();
            switch($e->getMessage()) {
                case self::CONCILIATION_NOT_FOUND:
                    return $this->json($request, [
                        'msg' => 'Conciliación no existe'
                    ], 401);
                break;
                case self::CONCILIATION_SALE_DIFFERENCE:
                    return $this->json($request, [
                        'msg' => 'Monto de una de las facturas/boletas excede monto total'
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
