<?php

namespace App;

use DB;
use App\SaleBoxDetail;
use App\Enum\TransactDetails;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Log;

class SaleBox extends Model
{
    protected $fillable = ['name', 'branch_office_id'];
    public function addTransaction($params) {
        $sbt         = new SaleBoxDetail();
        $sbt->fill($params);
        $sbt->save();
    }
    public function searchTransaction($params) {
        $transact    = (new SaleBoxDetail())->where('seller', $params['seller'])
                                            ->where('sale_box_id', $params['sale_box'])
                                            ->where('type',TransactDetails::SALE_BOX_OPEN)
                                            ->orderBy('created_at', 'desc')
                                            ->limit(1)
                                            ->first();
        if($transact) {
           return $transact->transact_id; 
        }  else return false;
    }
    public function checkOtherOrders($box_id, $user) {
        $value = DB::table('sale_boxes')
                   ->where('seller', $user)
                   ->where('id', '<>', $box_id)->count();
        return $value > 0 ? false : true;
    }

    public function getPaginateBoxdMovements(array $filters        = [], 
                                                int $start         = 0, 
                                                int $limit         = self::PAGE_SIZE, 
                                                string $orderField = 'created_at', 
                                                string $orderDirection = 'ASC') : array  {
        $query  =   SaleBoxDetail::where('type', TransactDetails::SALE_BOX_OPEN)
                    ->when(array_key_exists('start_date', $filters), function($query) use($filters) {
                        $query->where('created_at', '>=', $filters['start_date']);
                    })
                    ->when(array_key_exists('end_date', $filters), function($query) use($filters) {
                        $query->where('created_at', '<=', $filters['end_date']);
                    })
                    ->when(array_key_exists('box_id', $filters), function($query) use($filters) {
                        $query->where('sale_box_id', $filters['box_id']);
                    })
                    ->select([
                        'transact_id'
                    ]);
        $totalQuery = $query
        ->distinct()
        ->count();
        if($start != -1 && $limit != -1) {
            $query = $query->offset($start)
            ->limit($limit);
        }
        $query = $query
        ->get();
        $transacts = [];
        foreach($query as $q) {
            $transacts[] = $q->transact_id;
        }
        $querySelect = [
            'sale_box_details.id',
            'sale_box_details.transact_id',
            'sale_box_details.type',
            'sale_box_details.amount',
            'sale_box_details.doc_id',
            'sale_box_details.observations',
            'sale_box_details.created_at',
            'sellers.name',
            'users.name as cajero'
        ];
        if(Schema::hasTable('sale_box_documents')) {
            $querySelect[] = DB::raw('IFNULL(sale_box_documents.filename, \'\') as sale_box_document');
        }
        $query  = SaleBoxDetail::whereIn('sale_box_details.transact_id', $transacts)
                               ->leftJoin('users', 'sale_box_details.seller', '=', 'users.id')
                               ->leftJoin('sales', 'sale_box_details.doc_id', '=', 'sales.folio')
                               ->leftJoin('sellers', 'sales.seller_id', '=', 'sellers.id')
                               ->when(Schema::hasTable('sale_box_documents'), function($query) {
                                   $query->leftJoin('sale_box_documents', 'sale_box_documents.sale_box_transact_id', '=', 'sale_box_details.transact_id');
                               })
                               //whereRaw buscará si es boleta o factura, para así diferenciar los doc_id, respecitvamente a su tipo
                               ->whereRaw('case when sale_box_details.type in (4,5,6,10,12,15,17) then sales.type=1 else
                               case when sale_box_details.type in (7,8,9,11,13,16,18) then sales.type=2 else sale_box_details.type end
                               end')
                               ->select($querySelect)
                               ->orderBy($orderField, $orderDirection)
                               ->get();
        return [
            'filtered' => $totalQuery,
            'rows'     => $query,
            'total'    => $totalQuery
        ];
    }

}
