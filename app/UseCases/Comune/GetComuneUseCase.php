<?php

namespace App\UseCases\Comune;

use App\Comune;
use App\Http\Repositories\ComunesRepository;
use App\Http\Repositories\LocationDetailRepository;
use Illuminate\Support\Facades\Schema;

class GetComuneUseCase
{
    private $comune;

    public function __construct(Comune $comune)
    {
        $this->comune = $comune;
    }

    public function execute(array $params) : \stdClass
    {
        $response = new \stdClass();
        $response->recordsFiltered = 0;
        $response->recordsTotal = 0;
        if(array_key_exists('length', $params))
        {
            $total = $this->comune::when(!Schema::hasColumn('comunes', 'deleted_at'), function ($query) {
                return $query->withTrashed();
            })
            ->when(array_key_exists('comune_detail', $params), function ($query) use ($params) {
                return $query->where('comune_detail', 'LIKE', "%{$params['comune_detail']}%");
            })->count();
            $response->recordsFiltered = $total;
            $response->recordsTotal = $total;
        }
        $response->rows  = 
            $this->comune
            ::when(array_key_exists('start', $params), function ($query) use ($params) {
                return $query->offset($params['start']);
            })
            ->when(array_key_exists('length', $params), function ($query) use ($params) {
                return $query->limit($params['length']);
            })
            ->when(array_key_exists('comune_detail', $params), function ($query) use ($params) {
                return $query->where('comune_detail', 'LIKE', "%{$params['comune_detail']}%");
            })
            ->when(array_key_exists('id', $params), function($query) use ($params) {
                return $query->where('id', $params['id']);
            })
            ->when(!Schema::hasColumn('comunes', 'deleted_at'), function ($query) {
                return $query->withTrashed();
            })
            ->orderBy('comune_detail', 'ASC')
            ->select(['*'])
            ->get();
        return $response;
    }
}
