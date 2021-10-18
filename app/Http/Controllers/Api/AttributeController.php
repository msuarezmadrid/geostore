<?php

namespace App\Http\Controllers\Api;


use DB;
use Log;
use Validator;
use Carbon\Carbon;
use App\Item;
use App\User;
use App\Attribute;
use App\Enterprise;
use App\LocationType;
use App\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class AttributeController extends ApiController
{
    private $_method = '';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $with = $request->get('with');

        $data = new \stdClass();

        $start = $request->input('start');
        $length = $request->input('length');
        $draw = $request->input('draw');
        
        $columns = $request->input('columns');
        $order = $request->input('order');

        $dir = $order[0]['dir'];
        $field = $columns[$order[0]['column']]['data'];

        $filters = $request->input('filters');
        

        if($with == null){

            $datas = new Attribute();
            $enterprise = $request->user()->enterprise;
            $datas = $datas->where('enterprise_id', $enterprise->id);
            $data->recordsTotal = $datas->count();

            if ($start !== null && $length !== null) {

                if ($filters !== null) {
                    if ($filters['name'] !== null) {
                        $datas = $datas->where('name', 'LIKE', "%{$filters['name']}%");
                    }
                }

                if ($order !== null) {
                    $datas = $datas->orderBy($field, $dir);
                }

                $data->draw = $draw;
                

                if ($length != -1) {
                    $datas = $datas->offset($start)->limit($length);
                }

            }
            $data->attributes = $datas->get();
            $data->recordsFiltered = count($data->attributes);

            return $this->json($request, $data, 200);
        }
        else{
            $datas = Attribute::get();
            return $this->json($request, $data, 200);
        }
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

            $data = new Attribute();
            $data->fill($request->all());
            $user = User::find($request->user()->id);
            $data->name = strtoupper($request->get('name'));
            $data->enterprise_id = $user->enterprise->id;
            $data->created_by = $request->user()->id;
            $data->updated_by = $request->user()->id;
            $data->save();
            return $this->json($request, $data, 201);

        } catch(\Exception $e) {
            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
            return $this->json($request, null, 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $with = $request->get('with');

        try {

            $data = Attribute::find($id);
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
    public function edit(Request $request, $id)
    {


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
        try
        {
            $this->_method = $request->method();

            $data = Attribute::find($id);

            if ($data === null)
            {
                return $this->json($request, $data, 404);
            }

            $validator = Validator::make($request->all(), $this->rules());
            if($validator->fails()){
                Log::info("message=[{$validator->errors()}]");
                $errors = ['errors' => $validator->errors()];
                return $this->json($request,$errors,200);
            }
            $data->fill($request->all());
            $data->name = strtoupper($request->get('name'));       
            $data->updated_at       = Carbon::now();
            $data->updated_by       = $request->user()->id;
            $data->save();

            return $this->json($request, $data, 200);
        }
        catch(\Exception $e) {
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
            $data = Attribute::find($id);

            if($data === null) {
                return $this->json($request, $data, 404);
            }

            $data->delete();
            return $this->json($request, $data, 200);
        } catch(\Exception $e) {
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
                    'name' => 'required|max:150|string',
                    'type' => 'required|max:150|string'
                ];
                break;

            case 'PUT':
                return [
                    'name' => 'required|max:150|string',
                    'type' => 'required|max:150|string'
                ];
                break;
        }

    }
}
