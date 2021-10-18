<?php

namespace App;

use App\Item;
use App\Client;
use App\Location;
use App\SaleOrder;
use App\SaleDetail;
use App\CreditNote;
use App\Config;
use App\SaleOrderItem;
use App\InvoiceReference;
use App\CreditNoteDetail;
use DB;
use Log;
use App\Enum\TransactDetails;
use Illuminate\Database\Eloquent\Model;



class Sale extends Model
{
    const IVA                        = 0.19;
    protected $fillable = ["code", 
                           "folio", 
                           "date", 
                           "location_id", 
                           "type", 
                           "client_id", 
                           "movement_status_id", 
                           "total",
                           "conciliation_status_id"];
    
    public function getPdfInfo() {
        $orders       = SaleOrder::where('sale_id', $this->id)->get();
        $sellerName   = Seller::find($this->seller_id)->name;
        $clientData   = Client::find($this->client_id);
        $pdfInfo      = [];
        foreach ($orders as $order) {
            $detail = [];
            $detail['id']             = $order->id;
            $detail['location_name']  = Location::find($order->location_id)->name;
            $detail['type']           = $this->type;
            $detail['doc_id']         = $order->code;
            $detail['date']           = $order->date;
            $detail['seller_name']    = $sellerName;
            $detail['client_name']    = $clientData->name;
            $detail['client_address'] = $clientData->address;
            $detail['client_id']      = $clientData->rut != null ? ($clientData->rut.'-'.$clientData->rut_dv) : "";
            $items                    = SaleDetail::where('sale_id', $this->id)->get();
            $detail['details']        = [];
            
            foreach ($items as $item) {                
                if ($item->item_id){
                    $codeItem = DB::table('items')->where('id', $item->item_id)->first()->manufacturer_sku;

                    $detail['details'][] = [
                        'item_name' => $item->item_name,
                        'qty'       => $item->qty,
                        'code'      => $codeItem,
                        'item_price'=> $item->price
                    ];
                }
                
            }
                        $pdfInfo[] = $detail;
        }
        return $pdfInfo;
    }
    public function getCreditDetails() {
        $details = SaleDetail::where('sale_id', $this->id)->get();
        foreach($details as $detail) {
            $qty = CreditNoteDetail::where('sale_detail_id', $detail->id)
                                   ->sum('qty');
                                   Log::info($qty);
            $detail->fix_qty = round($detail->qty - $qty,2);
             
        }
        return $details;
    }

    public function loadResume($params) {
        $total  = 0;
        $paymentMethodsA = [];
        $paymentMethods = Config::where('value','1')->select('param')->get();
        foreach ($paymentMethods as $pm){
            $paymentMethodsA[] = strtolower($pm->param);
        }

        $sales  = (new Sale())->where('sales.created_at', '>=', $params['start_date'])
                        ->where('sales.created_at', '<=', $params['end_date'])
                        ->whereIn('payment_method',$paymentMethodsA)
						->join('sellers', 'sales.seller_id', '=', 'sellers.id')
                        ->select([
                            'sellers.name',
                            'sales.seller_id',
                            DB::raw('sum(sales.total) as sum_total')
                       ])
                        ->groupBy('sales.seller_id')
                        ->orderBy('sellers.name', 'asc')
                        ->get();
        $amountsByType = (new Sale())->where('sales.created_at', '>=', $params['start_date'])
                                             ->where('sales.created_at', '<=', $params['end_date'])
                                             ->join('sellers', 'sales.seller_id', '=', 'sellers.id')
                                             ->whereIn('payment_method',$paymentMethodsA)
                                             ->select([
                                                'sales.seller_id',
                                                'sales.payment_method',
                                                DB::raw('sum(sales.total) as total')
                                            ])
                                            ->groupBy('sales.seller_id','sales.payment_method')
                                            ->orderBy('sellers.name', 'asc')
                                            ->get();
        
        foreach ($sales as $sale) {
            $total                  += $sale->sum_total;
            $sale->seller_quantity   = Sale::where('seller_id', $sale->seller_id)
                                                ->where('created_at', '>=', $params['start_date'])
                                                ->where('created_at', '<=', $params['end_date'])
                                                ->count();
            foreach ($amountsByType as $abt) {
                if ($abt->seller_id == $sale->seller_id) {
                    if (in_array('card', $paymentMethodsA)){
                        $sale->card_total    += (in_array($abt->payment_method, ['card']) ? $abt->total : 0);  
                    }else{
                        $sale->card_total = null;
                    }
                    if (in_array('cash', $paymentMethodsA)){
                        $sale->cash_total    += (in_array($abt->payment_method, ['cash']) ? $abt->total : 0);
                    }else{
                        $sale->cash_total = null;
                    }
                    if (in_array('cheque', $paymentMethodsA)){
                        $sale->cheque_total  += (in_array($abt->payment_method, ['cheque']) ? $abt->total : 0);
                    }else{
                        $sale->cheque_total = null;
                    }
                    if (in_array('intern', $paymentMethodsA)){
                        $sale->intern_total  += (in_array($abt->payment_method, ['intern']) ? $abt->total : 0);
                    }else{
                        $sale->intern_total = null;
                    }
                    if (in_array('mix', $paymentMethodsA)){
                        $sale->mix_total     += (in_array($abt->payment_method, ['diff']) ? $abt->total : 0);
                    }else{
                        $sale->mix_total = null;
                    }
                    if (in_array('app', $paymentMethodsA)){
                        $sale->app_total     += (in_array($abt->payment_method, ['app']) ? $abt->total : 0);
                    }else{
                        $sale->app_total = null;
                    }
                    if (in_array('transfer', $paymentMethodsA)){
                        $sale->transfer_total     += (in_array($abt->payment_method, ['transfer']) ? $abt->total : 0);
                    }else{
                        $sale->transfer_total = null;
                    }
                }
            }
        }

        return [
            'recordsTotal'    => count($sales),
            'recordsFiltered' => count($sales),
            'rows'            => $sales,
            'total'           => $total
        ];        
    }

    private function branchToList($tree) {
        $output = array();
        foreach($tree as $branch) {
            if(isset($branch->childs)) {
                $output = array_merge($output, $this->branchToList($branch->childs));
            } else {
                $output[] = $branch;
            }
        }
        return $output;
    }
    
    public function loadCategoryResume($params) {
        
        $total  = 0;
        $paymentMethodsA = [];
        $paymentMethods = Config::where('value','1')->select('param')->get();
        foreach ($paymentMethods as $pm){
            $paymentMethodsA[] = strtolower($pm->param);
        }
        $category = new Category();
        $fullMap = $category->fullMap();
        //iterate over fullmap
        $categories = $this->branchToList($fullMap);
        $categoryIds = array();
        foreach($categories as $category) {
            $categoryIds[] = $category->id;
        }

        $invoices = (new Sale())->where('sales.created_at', '>=', $params['start_date'])
            ->where('sales.created_at', '<=', $params['end_date'])
            ->where('sales.type', 2)
            ->whereIn('payment_method',$paymentMethodsA)
            ->where(function($query) use ($categoryIds) {
                $query->whereIn('items.category_id', $categoryIds)
                ->orWhereNull ('items.category_id');
            })
            ->join('sale_details', 'sales.id', '=', 'sale_details.sale_id')
            ->join('items', 'sale_details.item_id', '=', 'items.id')
            ->leftJoin('categories', 'items.category_id', '=', 'categories.id')
            ->select([
                DB::raw('IFNULL(categories.name,\'Sin Categoria\') as name'),
                DB::raw('IFNULL(categories.id, NULL) as categories_id'),
                DB::raw('round(sum(sale_details.price * sale_details.qty) * ' . (1+self::IVA) . ') as sum_total'),
                'items.category_id'
            ])
            ->groupBy('items.category_id');
        $tickets = (new Sale())->where('sales.created_at', '>=', $params['start_date'])
            ->where('sales.created_at', '<=', $params['end_date'])
            ->where('sales.type', 1)
            ->whereIn('payment_method',$paymentMethodsA)
            ->where(function($query) use ($categoryIds) {
                $query->whereIn('items.category_id', $categoryIds)
                ->orWhereNull ('items.category_id');
            })
            ->join('sale_details', 'sales.id', '=', 'sale_details.sale_id')
            ->join('items', 'sale_details.item_id', '=', 'items.id')
            ->leftJoin('categories', 'items.category_id', '=', 'categories.id')
            ->select([
                DB::raw('IFNULL(categories.name,\'Sin Categoria\') as name'),
                DB::raw('IFNULL(categories.id, NULL) as categories_id'),
                DB::raw('round(sum(sale_details.price * sale_details.qty)) as sum_total'),
                'items.category_id'
            ])
            ->groupBy('items.category_id');
        $sales = Model::selectRaw('name, categories_id, sum(sum_total) as sum_total')
        ->from(DB::raw('(' . $invoices->toSql() . ' UNION ALL ' . $tickets->toSql() . ') as totalizado'))
        ->mergeBindings($invoices->getQuery())
        ->mergeBindings($tickets->getQuery())
        ->groupBy('name', 'categories_id')
        ->orderBy('name', 'desc')
        ->get();
        $invoicesByType = (new Sale())->where('sales.created_at', '>=', $params['start_date'])
            ->where('sales.created_at', '<=', $params['end_date'])
            ->where('sales.type', 2)
            ->join('sale_details', 'sales.id', '=', 'sale_details.sale_id')
            ->join('items', 'sale_details.item_id', '=', 'items.id')
            ->whereIn('payment_method',$paymentMethodsA)
            ->where(function($query) use ($categoryIds) {
                $query->whereIn('items.category_id', $categoryIds)
                ->orWhereNull ('items.category_id');
            })
            ->select([
                'items.category_id',
                'sales.payment_method',
                DB::raw('round(sum(sale_details.price * sale_details.qty) * '. (1+self::IVA) .') as total')
            ])
            ->groupBy('items.category_id','sales.payment_method');
        $ticketsByType = (new Sale())->where('sales.created_at', '>=', $params['start_date'])
            ->where('sales.created_at', '<=', $params['end_date'])
            ->where('sales.type', 1)
            ->join('sale_details', 'sales.id', '=', 'sale_details.sale_id')
            ->join('items', 'sale_details.item_id', '=', 'items.id')
            ->whereIn('payment_method',$paymentMethodsA)
            ->where(function($query) use ($categoryIds) {
                $query->whereIn('items.category_id', $categoryIds)
                ->orWhereNull ('items.category_id');
            })
            ->select([
                'items.category_id',
                'sales.payment_method',
                DB::raw('sum(sale_details.price * sale_details.qty) as total')
            ])
            ->groupBy('items.category_id','sales.payment_method');
        $amountsByType = Model::selectRaw('category_id, payment_method, sum(total) as total')
            ->from(DB::raw('(' . $invoicesByType->toSql() . ' UNION ALL ' . $ticketsByType->toSql() . ') as totalizado'))
            ->mergeBindings($invoicesByType->getQuery())
            ->mergeBindings($ticketsByType->getQuery())
            ->groupBy('category_id','payment_method')
            ->get();
        foreach ($sales as $sale) {
            $total                  += $sale->sum_total;
            $sale->category_quantity   = Sale::where('items.category_id', $sale->categories_id)
                                                ->where('sales.created_at', '>=', $params['start_date'])
                                                ->where('sales.created_at', '<=', $params['end_date'])
                                                ->join('sale_details', 'sales.id', '=', 'sale_details.sale_id')
                                                ->join('items', 'sale_details.item_id', '=', 'items.id')
                                                ->count();
            foreach ($amountsByType as $abt) {
                if ($abt->category_id == $sale->categories_id) {
                    if (in_array('card', $paymentMethodsA)){
                        $sale->card_total    += (in_array($abt->payment_method, ['card']) ? $abt->total : 0);  
                    }else{
                        $sale->card_total = null;
                    }
                    if (in_array('cash', $paymentMethodsA)){
                        $sale->cash_total    += (in_array($abt->payment_method, ['cash']) ? $abt->total : 0);
                    }else{
                        $sale->cash_total = null;
                    }
                    if (in_array('cheque', $paymentMethodsA)){
                        $sale->cheque_total  += (in_array($abt->payment_method, ['cheque']) ? $abt->total : 0);
                    }else{
                        $sale->cheque_total = null;
                    }
                    if (in_array('intern', $paymentMethodsA)){
                        $sale->intern_total  += (in_array($abt->payment_method, ['intern']) ? $abt->total : 0);
                    }else{
                        $sale->intern_total = null;
                    }
                    if (in_array('mix', $paymentMethodsA)){
                        $sale->mix_total     += (in_array($abt->payment_method, ['diff']) ? $abt->total : 0);
                    }else{
                        $sale->mix_total = null;
                    }
                    if (in_array('app', $paymentMethodsA)){
                        $sale->app_total     += (in_array($abt->payment_method, ['app']) ? $abt->total : 0);
                    }else{
                        $sale->app_total = null;
                    }
                    if (in_array('transfer', $paymentMethodsA)){
                        $sale->transfer_total     += (in_array($abt->payment_method, ['transfer']) ? $abt->total : 0);
                    }else{
                        $sale->transfer_total = null;
                    }
                }
            }
            if (in_array('card', $paymentMethodsA)){
                $sale->card_total    = round($sale->card_total, 2);
            }
            if (in_array('cash', $paymentMethodsA)){
                $sale->cash_total    = round($sale->cash_total, 2);
            }
            if (in_array('cheque', $paymentMethodsA)){
                $sale->cheque_total  = round($sale->cheque_total, 2);
            }
            if (in_array('intern', $paymentMethodsA)){
                $sale->intern_total  = round($sale->intern_total, 2);
            }
            if (in_array('mix', $paymentMethodsA)){
                $sale->mix_total     = round($sale->mix_total, 2);
            }
            if (in_array('app', $paymentMethodsA)){
                $sale->app_total     = round($sale->app_total, 2);
            }
            if (in_array('transfer', $paymentMethodsA)){
                $sale->transfer_total     = round($sale->transfer_total, 2);
            }
        }
        return [
            'recordsTotal'    => count($sales),
            'recordsFiltered' => count($sales),
            'rows'            => $sales,
            'total'           => $total
        ];      
    }

	public function getPaginatedMovements(array $filters       = [], 
                                            int $start         = 0, 
                                            int $limit         = self::PAGE_SIZE, 
                                            string $orderField = 'id', 
                                            string $orderDirection = 'DESC') : array  {

        $paymentMethodsA = [];
        $paymentMethods = Config::where('value','1')->select('param')->get();
        foreach ($paymentMethods as $pm){
            $paymentMethodsA[] = strtolower($pm->param);
        }
        $query  =   Sale::join('sellers', 'sales.seller_id', '=', 'sellers.id')
                    ->whereIn('payment_method',$paymentMethodsA)
					->when(array_key_exists('start_date', $filters), function($query) use($filters) {
                        $query->where('sales.created_at', '>=', $filters['start_date']);
                    })
                    ->when(array_key_exists('end_date', $filters), function($query) use($filters) {
                        $query->where('sales.created_at', '<=', $filters['end_date']);
                    })
                    ->when(array_key_exists('seller_id', $filters), function($query) use($filters) {
                        $query->where('sales.seller_id', $filters['seller_id']);
					})
					->when(array_key_exists('type', $filters), function($query) use($filters) {
                        switch ($filters['type']) {
							case 2:
								$query->where('sales.payment_method','cash');
							break;
							case 4:
								$query->where('sales.payment_method','card');
							break;
							case 3:
								$query->where('sales.payment_method','cheque');
                            break;
                            case 5:
								$query->where('sales.payment_method','intern');
                            break;
                            case 6:
								$query->where('sales.payment_method','diff');
							break;
                            case 15:
								$query->where('sales.payment_method','app');
							break;
                            case 17:
								$query->where('sales.payment_method','transfer');
							break;
						}
					})
                    ->select([
                        'sales.seller_id',
                        'sales.payment_method',
                        'sales.type',
                        DB::raw('IFNULL(sales.folio, \'NO EMITIDO EN SISTEMA\') as folio'),
						'sales.total',
                        'sales.created_at',
                        'sellers.name'
                    ]);
        $count   = $query->count();
        $res     = $query->orderBy($orderField, $orderDirection)
                       ->when($limit > 0, function($query) use ($start, $limit) {
                           $query->offset($start)
                                 ->limit($limit);
                       })
                       ->get();
		return [
            'filtered' => $count,
            'rows'     => $res,
            'total'    => $count
        ];               
    }

	public function getPaginatedMovementsCategory(array $filters       = [], 
                                            int $start         = 0, 
                                            int $limit         = self::PAGE_SIZE, 
                                            string $orderField = 'id', 
                                            string $orderDirection = 'DESC') : array  {

        $paymentMethodsA = [];
        $paymentMethods = Config::where('value','1')->select('param')->get();
        foreach ($paymentMethods as $pm){
            $paymentMethodsA[] = strtolower($pm->param);
        }
        $category = new Category();
        $fullMap = $category->fullMap();
        $categories = $this->branchToList($fullMap);
        $categoryIds = array();
        foreach($categories as $category) {
            $categoryIds[] = $category->id;
        }
        $ticketQuery = Sale::join('sale_details', 'sales.id', '=', 'sale_details.sale_id')
        ->where('sales.type', 1)
        ->join('items', 'sale_details.item_id', '=', 'items.id')
        ->leftJoin('categories', 'items.category_id', '=', 'categories.id')
                    ->whereIn('payment_method',$paymentMethodsA)
                    ->where(function($query) use ($categoryIds) {
                        $query->whereIn('items.category_id', $categoryIds)
                        ->orWhereNull ('items.category_id');
                    })
					->when(array_key_exists('start_date', $filters), function($query) use($filters) {
                        $query->where('sales.created_at', '>=', $filters['start_date']);
                    })
                    ->when(array_key_exists('end_date', $filters), function($query) use($filters) {
                        $query->where('sales.created_at', '<=', $filters['end_date']);
                    })
                    ->when(array_key_exists('category_id', $filters), function($query) use($filters) {
                        if($filters['category_id'] ==  null) {
                            $query->whereNull('items.category_id');

                        } else {
                            $query->where('items.category_id', $filters['category_id']);
                        }
					})
					->when(array_key_exists('type', $filters), function($query) use($filters) {
                        switch ($filters['type']) {
							case 2:
								$query->where('sales.payment_method','cash');
							break;
							case 4:
								$query->where('sales.payment_method','card');
							break;
							case 3:
								$query->where('sales.payment_method','cheque');
                            break;
                            case 5:
								$query->where('sales.payment_method','intern');
                            break;
                            case 6:
								$query->where('sales.payment_method','diff');
							break;
                            case 15:
								$query->where('sales.payment_method','app');
							break;
                            case 17:
								$query->where('sales.payment_method','transfer');
							break;
						}
                    })
                    ->select([
                        DB::raw('IFNULL(categories.name,\'Sin Categoria\') as name'),
                        DB::raw('IFNULL(categories.id, NULL) as categories_id'),
                        'sales.payment_method',
                        'sales.type',
                        DB::raw('IFNULL(sales.folio, \'NO EMITIDO EN SISTEMA\') as folio'),
						DB::raw('round(sale_details.price*sale_details.qty) as price'),
                        'sales.created_at'
                    ]);
        $invoiceQuery = Sale::join('sale_details', 'sales.id', '=', 'sale_details.sale_id')
        ->where('sales.type', 2)
        ->join('items', 'sale_details.item_id', '=', 'items.id')
        ->leftJoin('categories', 'items.category_id', '=', 'categories.id')
                    ->whereIn('payment_method',$paymentMethodsA)
                    ->where(function($query) use ($categoryIds) {
                        $query->whereIn('items.category_id', $categoryIds)
                        ->orWhereNull ('items.category_id');
                    })
					->when(array_key_exists('start_date', $filters), function($query) use($filters) {
                        $query->where('sales.created_at', '>=', $filters['start_date']);
                    })
                    ->when(array_key_exists('end_date', $filters), function($query) use($filters) {
                        $query->where('sales.created_at', '<=', $filters['end_date']);
                    })
                    ->when(array_key_exists('category_id', $filters), function($query) use($filters) {
                        if($filters['category_id'] ==  null) {
                            $query->whereNull('items.category_id');

                        } else {
                            $query->where('items.category_id', $filters['category_id']);
                        }
					})
					->when(array_key_exists('type', $filters), function($query) use($filters) {
                        switch ($filters['type']) {
							case 2:
								$query->where('sales.payment_method','cash');
							break;
							case 4:
								$query->where('sales.payment_method','card');
							break;
							case 3:
								$query->where('sales.payment_method','cheque');
                            break;
                            case 5:
								$query->where('sales.payment_method','intern');
                            break;
                            case 6:
								$query->where('sales.payment_method','diff');
							break;
                            case 15:
								$query->where('sales.payment_method','app');
							break;
                            case 17:
								$query->where('sales.payment_method','transfer');
							break;
						}
                    })
                    ->select([
                        DB::raw('IFNULL(categories.name,\'Sin Categoria\') as name'),
                        DB::raw('IFNULL(categories.id, NULL) as categories_id'),
                        'sales.payment_method',
                        'sales.type',
                        DB::raw('IFNULL(sales.folio, \'NO EMITIDO EN SISTEMA\') as folio'),
						DB::raw('round(sale_details.price*sale_details.qty * ' . (1+self::IVA) . ') as price'),
                        'sales.created_at'
                    ]);
                    
        $query = Model::selectRaw('
            name,
            categories_id,
            payment_method,
            type,
            folio,
            price,
            created_at')
        ->from(DB::raw('(' . $invoiceQuery->toSql() . ' UNION ALL ' . $ticketQuery->toSql() . ') as unido'))
        ->mergeBindings($invoiceQuery->getQuery())
        ->mergeBindings($ticketQuery->getQuery());
        $count   = $query->count();
        $res     = $query->orderBy($orderField, $orderDirection)
                       ->when($limit > 0, function($query) use ($start, $limit) {
                           $query->offset($start)
                                 ->limit($limit);
                       })
                       ->get();
		return [
            'filtered' => $count,
            'rows'     => $res,
            'total'    => $count
        ];               
    }

    public function getNoConciliatedDocuments($client, $type) {
        return  Sale::join('conciliation_statuses', 'sales.conciliation_status_id', '=', 'conciliation_statuses.id')
                    ->where('sales.client_id', $client)
                    ->where('sales.type', $type)
                    ->whereIN('conciliation_statuses.name', [
                        'NO_CONCILIATE', 'PART_CONCILIATE'
                    ])
                    ->select([
                        'sales.id',
                        'sales.type',
                        DB::raw('IFNULL(sales.folio, \'NO EMITIDO EN SISTEMA\') as folio'),
                        'sales.date',
                        'sales.total',
                        'sales.conciliated'
                    ])
                    ->get();
    }

    public function getConciliatedDocuments($client, $type, $id) {

        return  Sale::join('conciliation_statuses', 'sales.conciliation_status_id', '=', 'conciliation_statuses.id')
                    ->join('conciliation_details', 'sales.id', '=', 'conciliation_details.doc_id')
                    ->where('conciliation_details.conciliation_id', $id)
                    ->where('sales.client_id', $client)
                    ->where('sales.type', $type)
                    ->whereIN('conciliation_statuses.name', [
                        'NO_CONCILIATE', 'PART_CONCILIATE','CONCILIATE'
                    ])
                    ->select([
                        'sales.id',
                        'sales.type',
                        DB::raw('IFNULL(sales.folio, \'NO EMITIDO EN SISTEMA\') as folio'),
                        'sales.date',
                        'sales.total',
                        'sales.conciliated',
                        'conciliation_details.amount'
                    ])
                    ->get();
    }

}
