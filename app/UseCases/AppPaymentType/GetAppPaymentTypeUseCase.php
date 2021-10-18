<?php

namespace App\UseCases\AppPaymentType;

use App\AppPaymentTypes;
use App\Http\Repositories\AppPaymentTypesRepository;
use App\Http\Repositories\LocationDetailRepository;

class GetAppPaymentTypeUseCase
{
    private $appPaymentType;

    public function __construct(AppPaymentTypes $AppPaymentType)
    {
        $this->appPaymentType = $AppPaymentType;
    }

    public function execute(array $params) : \stdClass
    {
        $response = new \stdClass();
        $response->recordsFiltered = 0;
        $response->recordsTotal = 0;
        if(array_key_exists('length', $params))
        {
            $total = $this->appPaymentType::count();
            $response->recordsFiltered = $total;
            $response->recordsTotal = $total;
        }
        $response->rows  = 
            $this->appPaymentType
            ::when(array_key_exists('start', $params), function ($query) use ($params) {
                return $query->offset($params['start']);
            })
            ->when(array_key_exists('length', $params), function ($query) use ($params) {
                return $query->limit($params['length']);
            })
            ->select(['*'])
            ->get();
        return $response;
    }
}
