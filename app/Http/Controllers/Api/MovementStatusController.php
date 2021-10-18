<?php

namespace App\Http\Controllers\Api;


use DB;
use Log;
use Image;
use Storage;
use Validator;
use Carbon\Carbon;
use App\MovementStatus;
use App\User;
use App\Enterprise;
use App\LocationType;
use App\Location;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class MovementStatusController extends ApiController
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

            $datas = new MovementStatus();
            /*$enterprise = $request->user()->enterprise;
            $datas = $datas->where('enterprise_id', $enterprise->id);*/
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
            $data->movement_statuses = $datas->get();
			$data->recordsFiltered = count($data->movement_statuses);

            return $this->json($request, $data, 200);
        }
        else{
            $datas = MovementStatus::get();
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
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        
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
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        
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
                    'color' => 'required|max:10'
                ];
                break;
        }

    }
}
