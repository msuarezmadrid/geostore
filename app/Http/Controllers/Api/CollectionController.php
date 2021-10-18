<?php

namespace App\Http\Controllers\Api;

use App\Sale;
use App\Client;
use Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Jobs\SendEmailByClient;
use SendGrid\Mail\Mail;
use Log;
use DB;
use Carbon\Carbon;
use App\Libraries\Utils;
use App\Libraries\Excel;
use Illuminate\Support\Facades\Storage;


class CollectionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try{
            $data = new \stdClass();

            $start = $request->input('start');
            $length = $request->input('length');
            $draw = $request->input('draw');

            $columns = $request->input('columns');
            $order = $request->input('order');

            $dir = $order[0]['dir'];
            $columns[$order[0]['column']]['data'] = 'client_id';
            $field = $columns[$order[0]['column']]['data'];

            $filters = $request->input('filters');
            $data->recordsFiltered = 0;
 

            $datas = new Sale();
            $data->recordsTotal = $datas->count();
            
            if($start !== null && $length !== null) {

                if($filters !== null) {

                    if($filters['type'] == 1 || $filters['type'] == 2) {
                        $datas = $datas->where('type','=',$filters['type']);
                    }

                    if($filters['name'] !== null) {
                        $client_name = Client::where('name','LIKE',"%{$filters['name']}%")->pluck('id');
                        $datas = $datas->whereIn('client_id',$client_name);
                    }
    
                    if($filters['rut'] !== null) {
                        
                        $client_rut = Client::where(DB::raw('concat(rut,"-",rut_dv)'),'LIKE',"%{$filters['rut']}%")->pluck('id');
                        $datas = $datas->whereIn('client_id',$client_rut);
                    }
                }

                if($order !== null) {
                    $datas = $datas->orderBy($field, $dir);
                }

                $data->draw = $draw;
                $data->recordsFiltered = $datas->count();
                $datas = $datas->offset($start)->limit($length);
            }
            $datas = $datas->whereIn('conciliation_status_id',['1','2']);

            
            $data->collections = $datas->select([
                'client_id as id',
                DB::raw('sum(total)-sum(conciliated) as amount'),
                DB::raw('max(sales.updated_at) as updated_at')
                ])
                ->groupBy('client_id')
                ->get();
            
            if($data->recordsFiltered == 0) $data->recordsFiltered = $datas->count();
            
            foreach ($data->collections as $key => $value) {
                $data->collections[$key]->client = Client::find($value->id);
            }

            
            return $this->json($request, $data, 200);
        }catch(\Exception $e) {
            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
            return $this->json($request, null, 500);
            
        }

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
    public function show(Request $request, $id)
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

    public function autoSendMail(Request $request, $id, $doc_type)
    {
        //
        try{
            $client = Client::find($id);
            $mails = $client->mail;
            $emailSplit = explode(',', $mails);
            $download = 0;
            $excel = $this->autoCollectionExcel($request, $id, $download, $doc_type);
            $email = new Mail;
            $email->setFrom(config('mail.username'), config('app.name'));
            $email->setSubject("ESTADO DE CUENTA ".config('app.name'));
            foreach($emailSplit as $mail) {
                $email->addTo($mail, $client->name);
            }
            $email->addContent(
                "text/html", "Estimado,
                <br>
                <br> Adjunto estado de cuenta correspondiente al documento adjunto.
                <br>
                <br> Por favor depositar en alguna de las siguientes cuentas::
                <br>
                <br><strong>CTA CTE Nª XXXXX DEL BANCO XXXXX</strong>
                <br><strong>CTA VISTA Nª XXXXXX DEL BANCO XXXXX</strong>
                <br>
                <br> Ambas cuentas corrientes a nombre de XXXXX XXXXX XXXXX, RUT: X.XXX.XXX-X</strong>"
            );
            

            $attachment = new \SendGrid\Mail\Attachment();
            $attachment->setContent(file_get_contents($excel));
            $attachment->setType("application/octet-stream");
            $attachment->setFilename(basename($excel));
            $attachment->setDisposition("attachment");
            $email->addAttachment($attachment);

            SendEmailByClient::dispatch($email);

            return $this->json($request, $email, 200);
        }
        catch(\Exception $e) {
            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
            return $this->json($request, null, 500);
            
        }
    }

    public function manualSendMail(Request $request, $id, $doc_type)
    {
        //
        try{
            
            $this->_method = $request->method();
            $validator = Validator::make($request->all(), $this->rules());
            Log::info($request->get('email'));
            if($validator->fails()){
                Log::info("message=[{$validator->errors()}]");
                $errors = ['errors' => $validator->errors()];
                return $this->json($request,$errors,404);
            }
            $client = Client::find($id);
            $email = new Mail;
            $email->setFrom(config('mail.username'), config('app.name'));
            $email->setSubject("ESTADO DE CUENTA ".config('app.name'));
            $email->addTo( $request->email,  $request->name);
            $email->addContent(
                "text/plain", $request->msg
            );
            if ($request->file('file')!==null) {

                $file = $request->file('file');

                if ($file->getClientSize() >= '5500000'){
                    $errors = ['errors' => 'Tamaño de archivo no debe superar los 5 MB'];
                    return $this->json($request,$errors,404);
                }

                $filename = 'cobranza.xls';

                $folder = public_path('storage/collection/xls/');
                if (!file_exists($folder)) {
                    mkdir($folder, 0777, true);
                }
                $doc = 'collection/xls/'.$filename;
                
                    Storage::disk('public')->put($doc, file_get_contents($file) );
            $doc = "storage/".'collection/xls/'.$filename; 
            $attachment = new \SendGrid\Mail\Attachment();
            $attachment->setContent(file_get_contents($doc));
            $attachment->setType("application/octet-stream");
            $attachment->setFilename(basename($doc));
            $attachment->setDisposition("attachment");
            $email->addAttachment($attachment);

            }else{
                return $this->json($request,['errors' => [
                    'file' => [
                        'Debe adjuntar un archivo'
                    ] 
                 ]],404);
            }

            

            SendEmailByClient::dispatch($email);

            return $this->json($request, $email, 200);
        }
        catch(\Exception $e) {
            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
            return $this->json($request, null, 500);
            
        }
    }


    public function getExcel(Request $request, $id, $doc_type){
        $client = Client::find($id);
        $download = 1;
        $excel = $this->autoCollectionExcel($request, $id, $download, $doc_type);
        return $this->json($request, $excel, 200);
    }

    function autoCollectionExcel(Request $request, $id, $download, $doc_type) {
        try{

            // $clientD = Client::find($id)
            //           ->join('sales', 'sales.client_id', '=', 'clients.id')
            //           ->whereIn('sales.conciliation_status_id', ['1','2'])
            //           ->where('clients.id', '=', $id)
            //           ->select([
            //               DB::raw('"Impago" as movement'),
            //               'sales.type',
            //               'sales.folio',
            //               'clients.name',
            //               DB::raw('concat(clients.rut,"-",clients.rut_dv) as rut'),
            //               'sales.created_at',
            //               DB::raw('"NO APLICA"'),
            //               DB::raw('"NO APLICA"'),
            //               DB::raw('"NO APLICA"'),
            //               'sales.conciliated',
            //               DB::raw('total')
            //           ])
            //           ->orderBy(DB::raw('folio,movement'),'desc');

            $name   = $client = Client::find($id)->name;
            $client = Client::find($id)
                      ->join('sales', 'sales.client_id', '=', 'clients.id')
                      ->leftjoin('sale_details','sales.id','=','sale_details.sale_id')
                      ->whereIn('sales.conciliation_status_id', ['1','2'])
                      ->where('clients.id', '=', $id)
                      ->whereIn('sales.type', $doc_type == 0 ? ['1','2'] : [$doc_type])
                      ->select([
                          DB::raw('sales.id as sale_id'),
                          DB::raw('"Detalle" as movement'),
                          'sales.type',
                          'sales.folio',
                          'clients.name',
                          DB::raw('concat(clients.rut,"-",clients.rut_dv) as rut'),
                          'sale_details.created_at',
                          'sale_details.item_name',
                          'sale_details.qty',
                          'sale_details.price',
                          DB::raw('sale_details.qty*sale_details.price as total')
                      ])
                    //   ->union($clientD)
                      ->orderBy(DB::raw('folio,movement'),'desc')->get();

            if ($download == '1'){
                $path = null;
            }else{
                $path = 'storage/collection/xls';
            }
            $filename = 'cobranza.xlsx';

            $total = [];
            $total['total'] = 'Total Deuda';
            $total['amount'] = 0;
            $total['withtax'] = 0;
            $total['conciliated'] = 0;
            $sale_id = [];

            foreach ($client as $cli){
                if($cli->movement == 'Detalle'){
                    if($cli->type == '1'){
                        $cli->price = round($cli->price);
                        $cli->total = round($cli->total);
                    }else{
                        // $cli->price = round($cli->price*1.19);
                        // $cli->total = round($cli->total*1.19);
                        $cli->price = round($cli->price);
                        $cli->total = round($cli->total);
                        $total['withtax'] += $cli->total;
                    }
                    $total['conciliated'] += $cli->conciliated;
                    // $cli->price = '$'.$cli->price;
                }

                switch ($cli->type) {
                    case '1': 
                        $cli->type = 'Boleta'; 
                    break;
                    case '2': 
                        $cli->type = 'Factura';
                    break;
                }

                if (!in_array($cli->sale_id, $sale_id))
                {
                    $sale_id[] = $cli->sale_id; 
                }
                // $cli->total = '$'.$cli->total;
            }
            foreach ($sale_id as $sale){
                Log::info($sale);
                $sales = Sale::where('id',$sale)->first();
                $total['conciliated'] += $sales->conciliated;
                $total['amount'] += $sales->total;
                Log::info($total['amount']);
            }
           
            $total['amount'] = $total['amount']-$total['conciliated'];
            // $total['amount'] = '$'.$total['amount'];


            $excel     = new Excel([
                'pathfile' => $path,
                // 'pathfile' => null,
                'filename' => $filename,
                'title' => 'Cobranza',
                'columns'  => [
                    'Fecha', 'Tipo Doc.','N° Doc', 'Detalle', 'Cantidad', 'Valor', 'Total'
                ]
            ]);

            $excel->setValuesCollection($client, [
                    'created_at', 'type', 'folio', 'item_name', 'qty', 'price', 'total',
            ]);
            
            $excel->addColumn('TOTAL DEUDA:',$total['amount']);
            $excel->addTittle('RESUMEN CTA:',$name);

            // if ($total['conciliated'] != '0'){
            //     $excel->addColumn('TOTAL CONCILIADO','$'.$total['conciliated']);
            //     $excel->addColumn('TOTAL DEUDA:',$total['amount']);
            // }else{
            //     $excel->addColumn('TOTAL DEUDA:',$total['amount']);
            // }

            $excel->save();
            return $path."/".$filename;
        }
        catch(\Exception $e) {
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
                    'name'     => 'required',
                    'email'     => 'required',
                    'msg'     => 'required',
                    'file'  => 'required'

                ];
                break;

            case 'PUT':
                return [
                    'date'            => 'date',
                ];
                break;
        }

    }
}
