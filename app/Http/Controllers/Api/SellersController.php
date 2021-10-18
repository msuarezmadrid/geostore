<?php

namespace App\Http\Controllers\Api;

use Log;
use Validator;
use App\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SellersController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $start  = is_null($request->get('start'))  ? 0 : (int)$request->get('start');
        $length = is_null($request->get('length')) ? 10 : (int)$request->get('length');
        $columns = $request->get('columns');
        $order = $request->get('order');
        $orderDirection = isset($order[0]) ? $order[0]['dir'] :  'asc';
        $orderField = isset($columns[$order[0]['column']]['data']) ? $columns[$order[0]['column']]['data'] : 'id';
        $filters   = !$request->input('filters') ? [] : $request->input('filters');
        $result = (new Seller)->getPaginated($filters, $start, 10, $orderField, $orderDirection);
        return $this->json($request, $result, 200);
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
    public function store(Request $request)
    {
        try {
            $this->_method = $request->method();
            $validator = Validator::make($request->all(), $this->rules());
            if($validator->fails()) {
                Log::info("message=[{$validator->errors()}]");
                $errors = ['errors' => $validator->errors()];
                return $this->json($request,$errors,200);
            }
            $data = new Seller();
            $data->fill($request->all());
            $data->code = 0;
            $data->save();
            return $this->json($request, $data, 201);
        } catch(\Exception $e) {
            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
            return $this->json($request, null, 500);
        }    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        try {
            $data = Seller::where('id', $id)->first();
            if($data === null) {
                return $this->json($request, $data, 404);
            }
            return $this->json($request, $data, 200);
        } catch(\Exception $e) {
            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
            return $this->json($request, null, 500);
        }
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
        try {
            //PRIMERO SE VALIDA QUE LA INFORMACION SEA CORRECTA
            $this->_method = $request->method();
            $this->_id     = $id;
            $data = Seller::find($id);
            if ($data === null) {
                return $this->json($request, $data, 404);
            }
            $validator = Validator::make($request->all(), $this->rules());
            if ($validator->fails()) {
                Log::info("message=[{$validator->errors()}]");
                $errors = ['errors' => $validator->errors()];
                return $this->json($request,$errors,200);
            }
            $data->fill($request->all());
            $data->save();
            return $this->json($request, $data, 200);
        } catch(\Exception $e) {
            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
            return $this->json($request, null, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try{
            $data = Seller::find($id);
            if($data === null) {
                return $this->json($request, $data, 404);
            }
            /*$items = DB::table('items')->where('brand_id', $id)->count();
            if($items > 0){
                $errors = [ 'errors' => ['message' => 'La marca seleccionada está asociada a productos. no puede ser eliminada']];
                return $this->json($request, $errors, 200);
            }*/
            $data->delete();
            return $this->json($request, $data, 200);
        } catch(\Exception $e) {
            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
            return $this->json($request, null, 500);
        }
    }

    public function list(Request $request) {

        $sellers = Seller::all();
        
        return $this->json($request, $sellers, 200);
    }

    private function rules()
    {
        switch ($this->_method) {
            case 'POST':
                return [
                    'name'            => 'required|max:150'
                ];
                break;
            case 'PUT':
                return [
                    'name'            => 'required|max:150'
                ];
                break;
        }
    }


}
