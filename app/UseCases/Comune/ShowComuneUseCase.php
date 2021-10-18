<?php

namespace App\UseCases\Comune;

use App\Comune;
use App\Http\Repositories\ComuneRepository;

class ShowComuneUseCase
{
    private $repository;

    public function __construct(ComuneRepository $comuneRepository)
    {
        $this->repository = $comuneRepository;
    }

    public function execute(string $comuneId) : Comune
    {
        return $this->repository->findById($comuneId);
    }
}
