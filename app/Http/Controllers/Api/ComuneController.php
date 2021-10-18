<?php

namespace App\Http\Controllers\Api;

use App\Comune;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Comune\AddComuneRequest;
use App\UseCases\Comune\CreateComuneUseCase;
use App\UseCases\Comune\DeleteComuneUseCase;
use App\UseCases\Comune\GetComuneUseCase;
use App\UseCases\Comune\ShowComuneUseCase;

class ComuneController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, GetComuneUseCase $useCase)
    {
        $response = $useCase->execute($request->all());
        return $this->json($request, $response, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddComuneRequest $request, CreateComuneUseCase $useCase)
    {
        $request = $request->merge(['created_by' => $request->user()->id]);
        $request = $request->merge(['created_at' => Carbon::now()]);
        $data = $useCase->execute($request->all());
        return $this->json($request, $data, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(string $id, ShowComuneUseCase $useCase, Request $request)
    {
        $data = $useCase->execute($id.'');
        return $this->json($request, $data, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $id, DeleteComuneUseCase $useCase, Request $request)
    {
        $data = $useCase->execute($id.'');
        return $this->json($request, $data, 200);
    }
}
