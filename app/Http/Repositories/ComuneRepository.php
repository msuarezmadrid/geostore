<?php

namespace App\Http\Repositories;

use DB;
use App\Comune;
use Log;
use Illuminate\Support\Facades\Schema;

class ComuneRepository
{
    private $repository;

    public function __construct(Comune $comune)
    {
        $this->repository = $comune;
    }

    public function add(array $params) : bool
    {
        return $this->repository
            ->fill($params)
            ->save();
    }

    public function insert(array $params): Comune
    {
        $this->repository->insert($params);
        return $this->repository
        ->when(!Schema::hasColumn('comunes', 'deleted_at'), function ($query) {
            return $query->withTrashed();
        })
        ->raw('ORDER BY CONVERT(id, UNSIGNED) DESC')
        ->first();
    }

    public function edit(string $comuneId, array $params) : bool
    {
        return $this->repository
             ->findOrFail($comuneId)
             ->fill($params)
             ->save();
    }

    public function findById(string $comuneId) : Comune
    {
        return $this->repository->findOrFail($comuneId);
    }

    public function delete(string $comuneId) : bool
    {
        if(!Schema::hasColumn('comunes', 'deleted_at')) {
            return $this->repository::where('id', ''.$comuneId)
            ->withTrashed()
            ->firstOrFail()->forceDelete();
        } else {
            return $this->repository::where('id', ''.$comuneId)
            ->firstOrFail()->delete();
        }
    }
}
