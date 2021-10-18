<?php

namespace App\Http\Controllers\Api;

use App\AppPaymentTypes;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Http\Requests\AppPaymentType\AddAppPaymentTypeRequest;
use App\UseCases\AppPaymentType\CreateAppPaymentTypeUseCase;
use App\UseCases\AppPaymentType\DeleteAppPaymentTypeUseCase;
use App\UseCases\AppPaymentType\GetAppPaymentTypeUseCase;
use Barryvdh\Snappy\PdfFaker;
use Illuminate\Support\Facades\Schema;

class AppPaymentTypesController extends ApiController
{
    public function index(Request $request, GetAppPaymentTypeUseCase $useCase)
    {
        if(Schema::hasTable('app_payment_types')) {
            $response = $useCase->execute($request->all());
            return $this->json($request, $response, 200);
        }
        $fakeReturn = new \stdClass();
        $fakeReturn->rows = [];
        $defaultType = new AppPaymentTypes();
        $defaultType->id = -1;
        $defaultType->name = "FALTA HABILITAR OPCIONES DE APLICACIONES";
        $fakeReturn->rows[] = $defaultType;
        $fakeReturn->recordsTotal = 1;
        $fakeReturn->recordsFiltered = 1;
        return $this->json($request, $fakeReturn, 404);
    }

    public function store(AddAppPaymentTypeRequest $request, CreateAppPaymentTypeUseCase  $useCase)
    {

        if(Schema::hasTable('app_payment_types')) {
            $request = $request->merge(['created_by' => $request->user()->id]);
            $data = $useCase->execute($request->all());
            return $this->json($request, $data, 201);
        }
        return $this->json($request, false, 404);
    }

    public function destroy($id, Request $request, DeleteAppPaymentTypeUseCase  $useCase)
    {
        if(Schema::hasTable('app_payment_types')) {
            $data = $useCase->execute($id, $request->all());
            return $this->json($request, $data, 200);
        }
        return $this->json($request, false, 404);
    }
}
