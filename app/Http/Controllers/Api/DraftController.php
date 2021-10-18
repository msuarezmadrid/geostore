<?php

namespace App\Http\Controllers\Api;

use Log;
use Validator;
use App\Draft;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiController;

class DraftController extends ApiController
{
    private $_method = "";
	private $_id 	 = "";
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
		
		$with = $request->input('with');

        $filters = $request->input('filters');
		
		if($with === null){
            $datas = new Draft();
        } else {
            $inflator = explode(',', $with);
            $datas = Draft::with($inflator);
        }
		
		if ($request->user()->admin == 0)
		{
			$datas = $datas->where('created_by',$request->user()->id);
		}
		
		if ($start !== null && $length !== null) 
		{
			$datas = $datas->join('path_details','drafts.path_detail_id','=','path_details.id');
			
			foreach($inflator as $with)
			{
				if($with == 'contact')
				{
					$datas = $datas->join('contacts','drafts.contact_id','=','contacts.id');
				}
			}
			
			
			if ($filters !== null) 
			{
                if ($filters['name'] !== null)
				{
                    $datas = $datas->where('drafts.name', 'LIKE', "%{$filters['name']}%");
                }
				if ($filters['path'] !== null)
				{
                    $datas = $datas->where('path_details.path_id', '=',$filters['path']);
                }
            }

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
			
			$data->drafts = $datas->select(DB::raw('drafts.id,
													drafts.description,
													contacts.name as contactName,
													drafts.meeting_date,
													drafts.time_date,
													drafts.next_meeting'))->get();

            $data->recordsTotal = Draft::count();

            return $this->json($request, $data, 200);
			
			
		}
		else
		{
			if (isset($filters['status']))
			{
				$datas = $datas->where('path_status_id', '=', $filters['status']);
			}
			
			
			$data->drafts = $datas->get();

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
        try {

            $this->_method = $request->method();
            $validator = Validator::make($request->all(), $this->rules());

            if($validator->fails()){
                Log::info("message=[{$validator->errors()}]");
                $errors = ['errors' => $validator->errors()];
				return $this->json($request,$errors,200);
            }

            $data = new Draft();
            $data->fill($request->all());
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
    public function show(Request $request,$id)
    {
        $with = $request->get('with');

        try {

            if($with == null){
                $data = Draft::find($id);
                if($data === null) {
                    return $this->json($request, $data, 404);
                }
                return $this->json($request, $data, 200);
            }
            else {
                //$inflator = explode(',', $with);
                //$datas = Program::avaliableCourses();
                $data = new \stdClass();
                $draw = $request->input('draw');

                //$datas = Program::find($id)->$with();
				$inflator = explode(',',$with);
				
                $datas = Draft::with($inflator)->where('id',$id);
                $data->recordsFiltered = $datas->count();
                $data->drafts = $datas->get();

                $data->draw = $draw;
                $data->recordsTotal = count($datas);
                return $this->json($request, $data, 200);
            }

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
        try
		{
			//PRIMERO SE VALIDA QUE LA INFORMACION SEA CORRECTA 
			$this->_method = $request->method();
			$this->_id	   = $id;
			
			$data = Draft::find($id);
			
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
    public function destroy(Request $request,$id)
    {
        try{
            $data = Draft::find($id);

            if($data === null) {
                return $this->json($request, $project, 404);
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
                    'contact_id'      => 'required|integer',
					'path_detail_id'  => 'required|integer',
					'meeting_date'	  => 'required|date',
					'next_meeting'	  => 'required|date',
					'time_date'		  => 'required|max:5',
					'description'	  => 'required'
                ];
                break;

            case 'PUT':
                return [
                    'contact_id'      => 'required|integer',
					'meeting_date'	  => 'required|date',
					'next_meeting'	  => 'required|date',
					'time_date'		  => 'required|max:5',
					'description'	  => 'required'
                ];
                break;
        }

    }
	
	
}
