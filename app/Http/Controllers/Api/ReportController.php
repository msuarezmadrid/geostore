<?php

namespace App\Http\Controllers\Api;

use Barryvdh\DomPDF\Facade as PDFDOM;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Sale;
use App\Client;
use App\InvoiceReference;
use App\Report;
use Carbon\Carbon;
use Log;
use Schema;
use App\SaleBox;
use App\SaleOrder;
use App\SaleDetail;
use App\Libraries\Utils;
use App\Libraries\Excel;
use App\Enum\TransactDetails;
use App\SaleBoxDocument;

class ReportController extends ApiController
{



    public function printSales(Request $request) {


        $report = new Report();

        if ( $request->start_date == null ||  $request->end_date == null ){
            $request->start_date = "";
            $request->end_date = "";
        }

        $type = "";
        if ($request->has('type')){
            $type = $request->get('type');
        }
        Log::info('Iniciando carga de data');
        $info = [
            'report' => $report->getPdfInfo(json_decode($request->ids), $request->start_date, $request->end_date, $request->timezone, $type)
        ];
        Log::info('Terminando carga de data');



        //TO LOCAL DATE (CHILE)

        
        $filePath = storage_path('app/public').'/sales_receipt/';
        if(!file_exists($filePath)) {
            mkdir($filePath, 0755, true);
        }
        
		$filename = '/sales_receipt/comprobante_de_ventas'.Carbon::now()->format('dmY_His').'.pdf';
        $pdf = PDFDOM::loadView('pdf.sales_receipt', $info)
                ->setPaper('A4' )
                ->save(storage_path('app/public').$filename);


        return $this->json($request, [
            'success' => true,
            'url' => asset('storage').$filename
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexSales(Request $request)
    {
        //
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

                
                $datas = $datas->join('clients','clients.id','=','sales.client_id');
                $datas = $datas->select([
                    'sales.id',
                    'clients.name',
                    DB::raw('IFNULL(sales.folio, \'NO EMITIDO EN SISTEMA\') as folio'),
                    'sales.client_id',
                    'sales.type',
                    'sales.net',
                    'sales.tax',
                    'sales.total',
                    'sales.date',
                    'clients.rut',
                    'clients.rut_dv'
                ]);
                
                

                
                $data->recordsTotal = $datas->count();
                if($start !== null && $length !== null) {
                    
                    if ($filters !== null) 
                    {
        
                        if($filters['type'] == 1 || $filters['type'] == 2) {
                            $datas = $datas->where('type','=',$filters['type']);
                        }
                        
                        $data->recordsTotal = $datas->count();
                        if($filters['name'] !== null) {
                            
                            $datas = $datas->where('clients.name', 'LIKE', "%{$filters['name']}%");

                        }

                        if($filters['rut'] !== null) {
                            $datas = $datas->where('clients.rut', 'LIKE', "%{$filters['rut']}%");
                        }

                        if($filters['start_date'] !== null && $filters['end_date'] !== null) {

                            $datas = $datas->whereBetween('sales.created_at', array($filters['start_date'],$filters['end_date']) );
                        }
                        
                    }

                    $data->salesReport = $datas->get();


                    if ($order !== null) 
                    {
                        if ($field == 'id'){
                            $field = 'sales.id';
                        }
                        if ($field == 'folio'){
                            $field = "ABS(sales.folio) $dir";
                            $datas = $datas->orderByRaw($field);
                        }else{
                            $datas = $datas->orderBy($field, $dir);
                        }
                    }
                   
        
                    $data->draw = $draw;
                    $data->recordsFiltered = $datas->count();
                    $datas = $datas->offset($start)->limit($length);
                }        
                
                $data->sales = $datas->get();

                if($data->recordsFiltered == 0) $data->recordsFiltered = $datas->count();

                foreach ($data->sales as $key => $value) {   
                   $data->sales[$key]->client = Client::find($value->client_id);
                   $data->sales[$key]->reference    = InvoiceReference::where('invoice_references.sale_id', '=', $value->id)->get();
                }                
                
                return $this->json($request, $data, 200);
    }

    public function sales(Request $request, Sale $so) {
        $start_date = $request->get('start_date');
        $end_date   = $request->get('end_date');
        $results    = $so->loadResume([
            'start_date' => $start_date,
            'end_date'   => $end_date 
        ]);
        return $this->json($request, $results, 200);
    }

    public function salesCategory(Request $request, Sale $so) {
        $start_date = $request->get('start_date');
        $end_date   = $request->get('end_date');
        $results    = $so->loadCategoryResume([
            'start_date' => $start_date,
            'end_date'   => $end_date 
        ]);
        return $this->json($request, $results, 200);
    }

    
    public function movements(Request $request, Sale $so){
        $start  = is_null($request->get('start'))  ? 0 : (int)$request->get('start');
        $length = is_null($request->get('length')) ? 10 : (int)$request->get('length');
        $columns = $request->get('columns');
        $order = $request->get('order');
        $orderDirection = isset($order[0]) ? $order[0]['dir'] :  'asc';
        $orderField = isset($columns[$order[0]['column']]['data']) ? $columns[$order[0]['column']]['data'] : 'seller_id';
        $filters   = !$request->input('filters') ? [] : $request->input('filters');
        $result = $so->getPaginatedMovements($filters, $start, 10, $orderField, $orderDirection);
        return $this->json($request, $result, 200);
    }
    public function movementsCategory(Request $request, Sale $so){
        $start  = is_null($request->get('start'))  ? 0 : (int)$request->get('start');
        $length = is_null($request->get('length')) ? 10 : (int)$request->get('length');
        $columns = $request->get('columns');
        $order = $request->get('order');
        $orderDirection = isset($order[0]) ? $order[0]['dir'] :  'asc';
        $orderField = isset($columns[$order[0]['column']]['data']) ? $columns[$order[0]['column']]['data'] : 'categories_id';
        $filters   = !$request->input('filters') ? [] : $request->input('filters');
        $result = $so->getPaginatedMovementsCategory($filters, $start, 10, $orderField, $orderDirection);
        return $this->json($request, $result, 200);
    }
    public function boxmovements(Request $request, Salebox $sb) {
        $start  = is_null($request->get('start'))  ? 0 : (int)$request->get('start');
        $length = is_null($request->get('length')) ? 10 : (int)$request->get('length');
        $columns = $request->get('columns');
        $order = $request->get('order');
        $orderDirection = isset($order[0]) ? $order[0]['dir'] :  'asc';
        $orderField = isset($columns[$order[0]['column']]['data']) ? $columns[$order[0]['column']]['data'] : 'created_at';
        $filters   = !$request->input('filters') ? [] : $request->input('filters');
        $result = $sb->getPaginateBoxdMovements($filters, $start, $length, $orderField, $orderDirection);
        return $this->json($request, $result, 200);
    }
    public function exportsalesCategory(Request $request, Sale $so) {
        $filters   = !$request->input('filters') ? [] : $request->input('filters');
        $results   = $so->getPaginatedMovementsCategory($filters, 0, 0, 'created_at', 'asc');
        $excel     = new Excel([
            'pathfile' => null,
            'filename' => 'ventas_categoria_'.Carbon::now()->format('d-m-Y'),
            'title' => 'Ventas por Categoria',
            'columns'  => [
                'Categoria', 'Acción', 'N.Factura/Boleta', 'Monto', 'Fecha'
            ]
        ]);
        $models = $results['rows'];
        foreach ($models as $model) {
            switch($model->payment_method){
                case 'intern': 
                    if ($model->type == 1){
                        $type = 10;
                    break;
                    }else{
                        $type = 11;
                    break;
                    }
                case 'cash': 
                    if ($model->type == 1){
                        $type = 4;
                    break;
                    }else{
                        $type = 7;
                    break;
                    }
                case 'cheque': 
                    if ($model->type == 1){
                        $type = 6;
                    break;
                    }else{
                        $type = 9;
                    break;
                    }
                case 'card': 
                    if ($model->type == 1){
                        $type = 5;
                    break;
                    }else{
                        $type = 8;
                    break;
                    }
                case 'diff': 
                    if ($model->type == 1){
                        $type = 12;
                    break;
                    }else{
                        $type = 13;
                    break;
                    }
                case 'app': 
                    if ($model->type == 1){
                        $type = 15;
                    break;
                    }else{
                        $type = 16;
                    break;
                    }
                case 'transfer': 
                    if ($model->type == 1){
                        $type = 17;
                    break;
                    }else{
                        $type = 18;
                    break;
                    }
            }

            $model->type = Utils::getTransactString($type);
            $model->created_ats = Utils::utcToLocalString($model->created_at);
        }
        $excel->setValues($results['rows'], [
            'name', 'type', 'folio', 'price', 'created_ats'
        ]);
        $excel->save();
    }
    
    public function exportsales(Request $request, Sale $so) {
        $filters   = !$request->input('filters') ? [] : $request->input('filters');
        $results   = $so->getPaginatedMovements($filters, 0, 0, 'created_at', 'asc');
        $excel     = new Excel([
            'pathfile' => null,
            'filename' => 'ventas_'.Carbon::now()->format('d-m-Y'),
            'title' => 'Ventas',
            'columns'  => [
                'Vendedor', 'Acción', 'N.Factura/Boleta', 'Monto', 'Fecha'
            ]
        ]);
        $models = $results['rows'];
        foreach ($models as $model) {
            switch($model->payment_method){
                case 'intern': 
                    if ($model->type == 1){
                        $type = 10;
                    break;
                    }else{
                        $type = 11;
                    break;
                    }
                case 'cash': 
                    if ($model->type == 1){
                        $type = 4;
                    break;
                    }else{
                        $type = 7;
                    break;
                    }
                case 'cheque': 
                    if ($model->type == 1){
                        $type = 6;
                    break;
                    }else{
                        $type = 9;
                    break;
                    }
                case 'card': 
                    if ($model->type == 1){
                        $type = 5;
                    break;
                    }else{
                        $type = 8;
                    break;
                    }
                case 'diff': 
                    if ($model->type == 1){
                        $type = 12;
                    break;
                    }else{
                        $type = 13;
                    break;
                    }
                case 'app': 
                    if ($model->type == 1){
                        $type = 15;
                    break;
                    }else{
                        $type = 16;
                    break;
                    }
                case 'transfer': 
                    if ($model->type == 1){
                        $type = 17;
                    break;
                    }else{
                        $type = 18;
                    break;
                    }
            }

            $model->type = Utils::getTransactString($type);
            $model->created_ats = Utils::utcToLocalString($model->created_at);
        }
        $excel->setValues($results['rows'], [
            'name', 'type', 'folio', 'total', 'created_ats'
        ]);
        $excel->save();
    }
    public function exportmovements(Request $request, Salebox $sb) {
        $filters    = !$request->input('filters') ? [] : $request->input('filters');
        $result     = $sb->getPaginateBoxdMovements($filters, -1, -1, 'created_at', 'asc');
        $excel     = new Excel([
            'pathfile' => null,
            'filename' => 'movimientos_caja_'.Carbon::now()->format('d-m-Y'),
            'title' => 'Movimientos Caja',
            'columns'  => [
                'Cajero', 'Vendedor', 'Acción', 'N.Factura/Boleta', 'Monto', 'Observaciones', 'Fecha'
            ]
        ]);
        $models = $result['rows'];
        foreach ($models as $model) {
            $model->type = Utils::getTransactString($model->type);
            $model->created_ats = Utils::utcToLocalString($model->created_at);
        }
        $excel->setValues($result['rows'], [
            'cajero', 'name', 'type', 'doc_id', 'amount', 'observations', 'created_ats'
        ]);
        $excel->save();
    }

    public function downloadpdf(Request $request) {
        $filters = $request->input('filters');
        $transactId = $filters['transact_id'];
        $saleBoxDocument = SaleBoxDocument::where('sale_box_transact_id', $transactId)->first();
        if($saleBoxDocument != null) {
            return $this->json($request, [
                'success' => true,
                'url' => asset('storage').'/'.$saleBoxDocument->filename
            ], 200);
        }
    }

    public function salesWithoutStock (Request $request){
            //
            $data = new \stdClass();
            
            
                    $start = $request->input('start');
                    $length = $request->input('length');
                    $draw = $request->input('draw');
            
                    $columns = $request->input('columns');
                    // $order = $request->input('order');
            
                    // $dir = $order[0]['dir'];
                    // $field = $columns[$order[0]['column']]['data'];
            
                    $filters = $request->input('filters');
                    $data->recordsFiltered = 0;
            
                    Schema::dropIfExists('temp_products');

                    $productList = DB::statement( DB::raw( "CREATE TEMPORARY TABLE temp_products(id INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY) 
                    select sum(qty) as qty ,item_name, round(avg(price),0) as avg_price, round(avg(price)*sum(qty),0) as total  
                    from sale_details 
                    where item_id is null 
                    and created_at between '".$request->get('start_date')."' and '".$request->get('end_date')."'
                    group by item_name order by qty desc;
                    ") 
                    );


                    $datas = DB::table('temp_products');
                    $data->draw = $draw;
                    $data->sales = $datas->get();

                    $data->recordsTotal = $datas->count();
                    if($data->recordsFiltered == 0) $data->recordsFiltered = $datas->count();

                    
                    
                    return $this->json($request, $data, 200);
    }

    function exportSalesWithoutStock(Request $request) {
        try{
            $datas = DB::table('temp_products')->get();
            $filters = $request->input('filters');

            $start_date = Utils::utcToLocalString($filters['start_date']);
            $end_date = Utils::utcToLocalString($filters['end_date']);
 
            $tittle = 'Vendido desde ';
            $contentTittle = date_format(date_create($start_date), 'd-m-y').' al '.date_format(date_create($end_date), 'd-m-y');
            
            $excel     = new Excel([
                'pathfile' => null,
                'filename' => 'productos_sin_stock_'.Carbon::now()->format('d-m-Y'),
                'title' => 'Ventas sin stock',
                'columns'  => [
                    'Cantidad', 'Producto', 'Total'
                ]
            ]);

            $excel->setValuesSWS($datas, [
                    'qty', 'item_name', 'total'
            ]);
            
            $excel->addTittleSWS($tittle,$contentTittle);
            $excel->save();
            return $path."/".$filename;

        }
        catch(\Exception $e) {
            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
            return $this->json($request, null, 500);
            
        }
    }
}
