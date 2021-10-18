<?php

namespace App\Http\Controllers\Api;

use Log;
use Validator;
use App\Device;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class DeviceController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = new \stdClass();
		
		$start 		= $request->input('start');
        $length 	= $request->input('length');
        $draw 		= $request->input('draw');
		
		$columns 	= $request->input('columns');
        $order 		= $request->input('order');
		
		$dir = $order[0]['dir'];
        $field = $columns[$order[0]['column']]['data'];
		
		$with  		= $request->input('with');
		
		if ($with != null)
		{
			$inflator = explode(',',$with);
			$datas    = Device::with($inflator);
		}			
		else
		{
			$datas = new Device();
		}
		
		if ($start !== null && $length !== null) 
		{
			if ($order !== null) 
			{
                $datas = $datas->orderBy($field, $dir);
            }
			
			$data->draw = $draw;
            $data->recordsFiltered = $datas->count();
            
			if ($length != -1) 
			{
                $datas = $datas->offset($start)->limit($length);
            }
			
			$data->devices = $datas->get();
			

            $data->recordsTotal = Device::count();

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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        
        $device = Device::find($id);

        if ($device == null)
        {
            return $this->json($request,$device,404);
        }

        return $this->json($request,$device,200);

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
        try
		{
			//PRIMERO SE VALIDA QUE LA INFORMACION SEA CORRECTA 
			$this->_method = $request->method();
			$this->_id	   = $id;
			
			$data = Device::find($id);
			
			if ($data === null)
			{
                return $this->json($request, $data, 404);
            }
			
			$validator = Validator::make($request->all(), $this->rules()); 
			
			if ($validator->fails())
			{
                Log::info("message=[{$validator->errors()}]");
                $errors = ['errors' => $validator->errors()];
				return $this->json($request,$errors,200);
            }
			
			$data->fill($request->all());
			$data->updated_at		= Carbon::now();
			$data->updated_by		= $request->user()->id;;
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
    public function destroy($id)
    {
        //
    }
	
	private function rules()
    {
        switch ($this->_method)
        {
            case 'POST':
                return [
                    'name'            => 'required|max:150',
                    'email'           => 'required|email',
					'phone'           => 'required',
                ];
                break;

            case 'PUT':
                return [
                    'status'            => 'required|integer'
                ];
                break;
        }

    }
	
}
