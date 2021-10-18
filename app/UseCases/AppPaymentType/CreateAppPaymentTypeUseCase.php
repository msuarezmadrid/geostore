<?php

namespace App\UseCases\AppPaymentType;

use DB;
use Log;
use App\AppPaymentType;
use App\Http\Repositories\AppPaymentTypeRepository;
class CreateAppPaymentTypeUseCase
{
    private $repository;

    public function __construct(AppPaymentTypeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(array $params) : bool
    {
        return $this->repository->add($params);
    }
}
