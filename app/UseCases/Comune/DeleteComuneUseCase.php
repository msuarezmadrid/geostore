<?php

namespace App\UseCases\Comune;

use DB;
use App\Comune;
use App\Http\Repositories\ComuneRepository;
class DeleteComuneUseCase
{
    private $repository;

    public function __construct(ComuneRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(string $id) : bool
    {
        return $this->repository->delete($id);
    }
}
