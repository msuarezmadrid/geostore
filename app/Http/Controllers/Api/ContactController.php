<?php

namespace App\Http\Controllers\Api;

use Log;
use Validator;
use App\SupplierContact;
use App\ClientContact;
use App\Contact;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ContactController extends ApiController
{
    private $_method = '';
	private $_id     = '';
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        Log::INFO(__FUNCTION__);

        $data = new \stdClass();
        die("TESTING");
        $start = $request->input('start');
        $length = $request->input('length');
        $draw = $request->input('draw');
        
        $columns = $request->input('columns');
        $order = $request->input('order');

        $dir = $order[0]['dir'];
        $field = $columns[$order[0]['column']]['data'];

        $filters = $request->input('filters');
		
		$datas = new Contact();
		$user = User::find($request->user()->id);
        $datas = Contact::where('enterprise_id', $user->enterprise->id);

        $data->recordsTotal = $datas->count();
		if ($start !== null && $length !== null) 
		{
			if ($filters !== null) 
			{

                if($filters['type'] !== null) {
                    $datas = $datas->where('type','=',$filters['type']);
                }
                
                $data->recordsTotal = $datas->count();
                if($filters['name'] !== null) {
                    
                    $datas = $datas->where('name','LIKE',"%{$filters['name']}%");
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
			
			$data->contacts = $datas->get();
			
            Log::INFO(__FUNCTION__ . "datas: " . json_encode($datas) . " -.");
            

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

            $data = new Contact();
            $data->fill($request->all());
            $user = User::find($request->user()->id);
            $data->enterprise_id = $user->enterprise->id;
            $data->created_by = $request->user()->id;
            $data->updated_by = $request->user()->id;
            $data->save();

            if($request->get('supplier_id')!==null){
                $scon = new SupplierContact();
                $scon->supplier_id = $request->get('supplier_id');
                $scon->contact_id = $data->id;
                $scon->save();
            }

            if($request->get('client_id')!==null){
                $scon = new ClientContact();
                $scon->client_id = $request->get('client_id');
                $scon->contact_id = $data->id;
                $scon->save();
            }
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
        try {

            $data = Contact::find($id);
            
			if ($data === null)
			{
                return $this->json($request, $data, 404);
            }
            else
			{				
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
			
			$data = Contact::find($id);
			
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
            $data = Contact::find($id);

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
                    'name'            => 'required|max:150',
                    'email'        => 'email|max:150'
                ];
                break;

            case 'PUT':
                return [
                    'name'            => 'required|max:150',
                    'lastname'        => 'required|max:150'
                ];
                break;
        }

    }
}
