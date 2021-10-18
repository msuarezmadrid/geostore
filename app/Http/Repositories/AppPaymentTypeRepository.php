<?php

namespace App\Http\Repositories;

use DB;
use App\AppPaymentTypes;
use Log;

class AppPaymentTypeRepository
{
    private $repository;

    public function __construct(AppPaymentTypes $AppPaymentType)
    {
        $this->repository = $AppPaymentType;
    }

    public function add(array $params) : bool
    {
        return $this->repository
            ->fill($params)
            ->save();
    }

    public function insert(array $params): AppPaymentTypes
    {
        $this->repository->insert($params);
        return $this->repository->latest('id')->first();
    }

    public function edit(int $AppPaymentTypesId, array $params) : bool
    {
        return $this->repository
             ->findOrFail($AppPaymentTypesId)
             ->fill($params)
             ->save();
    }

    public function findById(int $AppPaymentTypesId) : AppPaymentTypes
    {
        return $this->repository->findOrFail($AppPaymentTypesId);
    }

    public function delete($AppPaymentTypesId) : bool
    {
        return $this->repository::findOrFail($AppPaymentTypesId)->delete();
    }
}
