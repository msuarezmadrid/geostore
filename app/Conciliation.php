<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Conciliation extends Model
{
    const PAGE_ROWS = 10;

    public function getPaginated(array $filters = [], 
                                   int $start = 0, 
                                   int $limit = self::PAGE_ROWS, 
                                   string $orderField = 'id', 
                                   string $orderDirection = 'DESC') : array {
        $query = DB::table('conciliations')
                    ->join('users', 'conciliations.created_by', '=', 'users.id')
                    ->join('clients', 'conciliations.client_id', '=', 'clients.id')
            ->select([
                'conciliations.id',
                DB::raw('users.name as uname') ,
                'clients.name',
                'conciliations.created_at'
            ]);
        /*if (array_key_exists('folio', $filters))
        {
            $query = $query->where('folio', 'like', '%' . $filters['folio'] . '%');
        }*/
        $query = $query->orderBy($orderField, $orderDirection)
                       ->offset($start)->limit($limit);
        return [
            'filtered' => $query->count(),
            'rows' => $query->get(),
            'total' => DB::table('conciliations')->count(),
        ];
    }

    public function getDocuments($type) {
        switch ($type) {
            case 1:
               return DB::table('conciliation_details')
               ->join('sales', 'conciliation_details.doc_id', '=', 'sales.id')
               ->where('conciliation_details.conciliation_id', $this->id)
               ->where('conciliation_details.type', $type)
               ->select([
                 'sales.type',
                 'sales.total',
                 'conciliation_details.amount',
                 'sales.folio',
                 'sales.date',
                 DB::raw('sales.total - sales.conciliated as remain')
               ])
               ->get();    
            break;
            case 2:
               //return DB::table(''); 
            break;
        }
    }

}
