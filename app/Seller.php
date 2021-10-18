<?php

namespace App;

use DB;
use App\Seller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Seller extends Model
{
    use SoftDeletes;
    protected $dates = ["deleted_at"];
    protected $fillable = [
        'name', 'code'
    ];
    const PAGE_SIZE = 10;
    public function getPaginated(array $filters       = [],
                                   int $start         = 0,
                                   int $limit         = self::PAGE_SIZE,
                                   string $orderField = 'id',
                                   string $orderDirection = 'DESC') : array  {
        $query = new Seller();

        $count = $query->count();
        $res   = $query->orderBy($orderField, $orderDirection)
                       ->offset($start)
                       ->limit($limit)
                       ->get();
        return [
                'filtered' => $count ,
                'rows'     => $res,
                'total'    => DB::table('sale_box_details')->count(),
        ];
    }
}
