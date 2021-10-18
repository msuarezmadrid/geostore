<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\ConcilliationDoc;
use App\SaleOrder;
use App\Location;
use App\Client;
use App\Libraries\Afe;
use App\Sale;
use Log;
use DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class SaleController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        Log::info(__FUNCTION__ . " SaleController ");

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


        $datas = new Sale();
        
        $data->recordsTotal = $datas->count();
        if($start !== null && $length !== null) {
            
            if ($filters !== null) 
            {
               
                $data->recordsTotal = $datas->count();
                if($filters['folio'] !== null) {
                    
                    $datas = $datas->where('folio','LIKE',"%{$filters['folio']}%");
                }

                if($filters['type'] == 1 || $filters['type'] == 2) {
                    $datas = $datas->where('type','=',$filters['type']);
                }
                
            }

            if ($order !== null) 
            {
                $datas = $datas->orderBy($field, $dir);
            }
           

            $data->draw = $draw;
            $data->recordsFiltered = $datas->count();
            $datas = $datas->offset($start)->limit($length);
        }

        
        $data->sales = $datas->select(
                [
                    '*',
                    DB::raw('IFNULL(sales.folio, \'NO EMITIDO EN SISTEMA\') as folio_fallback'),
                ]
            )
            ->get();

        if($data->recordsFiltered == 0) $data->recordsFiltered = $datas->count();
        
        foreach ($data->sales as $key => $value) {
        
           $data->sales[$key]->client = Client::find($value->client_id);
        }
        
        
        return $this->json($request, $data, 200);
    }

    public function forwardTable(Request $request) 
    {
        Log::info(__FUNCTION__ . " SaleController ");
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


        $datas = new Sale();
        $datas = $datas->select([
            'sales.id',
            'sales.folio',
            'sales.type',
            'sales.date',
            'sales.total',
            'clients.rut',
            'clients.rut_dv',
            'clients.name',
            'clients.email'
        ]);
        
        $datas = $datas->join('clients', 'sales.client_id', '=', 'clients.id');

        $datas = $datas->whereNotNull('folio');
        $datas->where('rut', '<>', '66666666');
        $data->recordsTotal = $datas->count();
        if($start !== null && $length !== null) {
            if ($filters !== null) 
            {
                $data->recordsTotal = $datas->count();
                if(isset($filters['folio'])) {
                    $datas = $datas->where('folio', '=', $filters['folio']);
                }
                if(isset($filters['start_date']) && isset($filters['end_date'])) {
                    $datas = $datas->where('date', '<=', $filters['end_date'])
                    ->where('date', '>=', $filters['start_date']);
                }
                if(isset($filters['tipo_doc'])) {
                    $datas = $datas->where('type', '=', $filters['tipo_doc']);
                }
            }

            if ($order !== null) 
            {
                $datas = $datas->orderBy($field, $dir);
            }
        

            $data->draw = $draw;
            $data->recordsFiltered = $datas->count();
            $datas = $datas->offset($start)->limit($length);
        }

        
        $data->sales = $datas->get();
        if($data->recordsFiltered == 0) $data->recordsFiltered = $datas->count();
        
        
        return $this->json($request, $data, 200);
    }

    public function forwardDocument(Request $request) 
    {
        Log::info(__FUNCTION__ . " SaleController ");
        $AFE = new Afe();
        $emails = $request->input('emails');
        $emailSplit = explode(',', $emails);
        $validator = new \stdClass();
        if(count($emailSplit) > 1) {
            $data = [
                'folio' => $request->input('folio'),
                'doctype' => $request->input('doctype'),
                'cliente' => $request->input('cliente'),
                'emails' => $emailSplit,
                'emailLength' => $emails
            ];
            $validator = Validator::make($data, [
                'folio'     =>  'required|integer',
                'cliente'   =>  'required|string',
                'emails.*'  =>  'required|email',
                'emailLength' => 'required|max:191',
                'doctype'   =>  ['required', Rule::in([33, 34, 39, 41, 46, 56, 61, 52, 43, 110, 111, 112])]
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'folio'     =>  'required|integer',
                'cliente'   =>  'required|string',
                'emails'     =>  'required|email|max:191',
                'doctype'   =>  ['required', Rule::in([33, 34, 39, 41, 46, 56, 61, 52, 43, 110, 111, 112])]
            ]);
        }
        if($validator->fails()) {
            $errors = ['errors' => $validator->errors()];
            return $this->json($request, $errors, 200);
        }
        $response = $AFE->forwardDocument($request->all());
        if(isset($response['data']) && isset($response['data']->success) && $response['data']->success == 'success') {
            $clienteRut = explode('-', $request->input('cliente'));
            $cliente = new Client();
            $cliente->where('rut', '=', $clienteRut[0])
            ->where('rut', '<>', '66666666')
            ->where('rut_dv', '=', $clienteRut[1])
            ->update(['email' => $emails]);

        }
        return $this->json($request, $response, 200);
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
        //
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

    public function searchconciliated(Request $request, $id, $client, $type, Sale $sale, ConcilliationDoc $condoc, $conciliated) {
        if ($conciliated == 0){
            $data = $sale->getNoConciliatedDocuments($client, $type);
            $con  = $condoc->getNoConciliatedDocuments($client);
        }else{
            $data = $sale->getConciliatedDocuments($client, $type, $id);
            $con  = $condoc->getConciliatedDocuments($client, $id);
        }
        

        return $this->json($request,[
            'docs' => $data,
            'con'  => $con
        ], 200);
    }

}
