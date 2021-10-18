<?php

namespace App;

use DB;
use Log;
use Illuminate\Database\Eloquent\Model;

class CreditNote extends Model
{
    const PAGE_ROWS = 10;

    public function getPaginated(array $filters = [], 
                                   int $start = 0, 
                                   int $limit = self::PAGE_ROWS, 
                                   string $orderField = 'id', 
                                   string $orderDirection = 'DESC') : array {
        $query = DB::table('credit_notes')
            ->select('*');
        Log::info($filters);
        if (array_key_exists('folio', $filters))
        {
            $query = $query->where('folio', 'like', '%' . $filters['folio'] . '%');
        }
        if ($filters['start_date'] !== null && $filters['end_date'] !== null)
        {
            $query = $query->whereBetween('created_at', array($filters['start_date'],$filters['end_date']) );
        }
        $query = $query->orderBy($orderField, $orderDirection);
        $query = $query->offset($start)->limit($limit);
        return [
            'filtered' => $query->count(),
            'rows' => $query->get(),
            'total' => DB::table('credit_notes')->count(),
        ];
    }
}
