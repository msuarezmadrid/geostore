<?php

namespace App\UseCases\AppPaymentType;

use DB;
use App\AppPaymentType;
use App\Http\Repositories\AppPaymentTypeRepository;
class DeleteAppPaymentTypeUseCase
{
    private $repository;

    public function __construct(AppPaymentTypeRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute($id) : bool
    {
        return $this->repository->delete($id);
    }
}
