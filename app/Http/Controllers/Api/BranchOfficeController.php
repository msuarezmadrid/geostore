<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\BranchOffice;
use Validator;
use DB;
use Log;

class BranchOfficeController extends ApiController
{
    public function index(Request $request)
    {
        $data = new \stdClass();

        $start = $request->input('start');
        $length = $request->input('length');
        $draw = $request->input('draw');

        $columns = $request->input('columns');
        $order = $request->input('order');

        $dir = $order[0]['dir'];
        $field = $columns[$order[0]['column']]['data'];

        $filters = $request->input('filters');
        $data->recordsFiltered = 0;


        $datas = new BranchOffice();
        $enterprise = $request->user()->enterprise;
        $datas = $datas->where('enterprise_id', $enterprise->id);
        $data->recordsTotal = $datas->count();

        if($start !== null && $length !== null) {


            if($order !== null) {
                $datas = $datas->orderBy($field, $dir);
            }

            $data->draw = $draw;
            $data->recordsFiltered = $datas->count();
            $datas = $datas->offset($start)->limit($length);
        }

        $data->branch_office = $datas->get();
        if($data->recordsFiltered == 0) $data->recordsFiltered = $datas->count();

        return $this->json($request, $data, 200);
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

            if($validator->fails()){
                Log::info("message=[{$validator->errors()}]");
                $errors = ['errors' => $validator->errors()];
                return $this->json($request,$errors,200);
            }
            DB::beginTransaction();
            $data = new BranchOffice();
            $data->fill($request->all());
            $data->enterprise_id = $request->user()->enterprise->id;
            $data->save();
            DB::commit();
            return $this->json($request, $data, 201);

        } catch(\Exception $e) {
            DB::rollback();
            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
            return $this->json($request, null, 500);
        }
    }

     private function rules()
    {
        switch ($this->_method)
        {
            case 'POST':
                return [
                    'name' => 'required|max:150|string'
                ];
                break;

            case 'PUT':
                return [
                    'name' => 'required|max:150|string'
                ];
                break;
        }

    }
}