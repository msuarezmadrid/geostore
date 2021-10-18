<?php

namespace App\UseCases\Comune;

use DB;
use Log;
use App\Comune;
use App\Http\Repositories\ComuneRepository;
use Illuminate\Support\Facades\Schema;

class CreateComuneUseCase
{
    private $repository;

    public function __construct(ComuneRepository $repository)
    {
        $this->repository = $repository;
    }

    public function execute(array $params) : Comune
    {
        $comune = new Comune();
        $maxId = $comune::orderBy('numId', 'desc')
        ->withTrashed()
        ->select(DB::raw('CONVERT(id, UNSIGNED) numId'))
        ->first();
        if($maxId == null) {
            $maxId = 0;
        } else {
            $maxId = $maxId->numId;
        }
        if (!Schema::hasColumn('comunes', 'created_by') && array_key_exists('created_by', $params)) {
            unset($params['created_by']);
        };
        if (!Schema::hasColumn('comunes', 'deleted_at') && array_key_exists('deleted_at', $params)) {
            unset($params['deleted_at']);
        };
        $params['id'] = $maxId + 1;
        $params['comune_detail'] = strtoupper($params['comune_detail']);
        return $this->repository->insert($params);
    }
}
