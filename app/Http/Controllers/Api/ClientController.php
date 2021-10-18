<?php

namespace App\Http\Controllers\Api;

use Log;
use DB;
use Validator;
use App\Client;
use App\ClientContact;
use App\Contact;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class ClientController extends ApiController
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
        Log::info( __FUNCTION__ );

        $data = new \stdClass();

        $start = $request->input('start');
        $length = $request->input('length');
        $draw = $request->input('draw');
        
        $columns = $request->input('columns');
        $order = $request->input('order');

        $dir = $order[0]['dir'];
        $field = $columns[$order[0]['column']]['data'];

        $filters = $request->input('filters');
        
        $datas = new Client();
        $user = User::find($request->user()->id);
        $datas = Client::where('enterprise_id', $user->enterprise->id);
        $data->recordsTotal = $datas->count();

        Log::info( __FUNCTION__ . " | query: " . $datas->toSql() . " | record: " . $data->recordsTotal );

        if ($start !== null && $length !== null) 
        {
            if ($filters !== null) 
            {
               
                $data->recordsTotal = $datas->count();
                if($filters['name'] !== null) {
                    
                    $datas = $datas->where('name','LIKE',"%{$filters['name']}%");
                }

                if($filters['rut'] !== null) {
                    
                    $datas = $datas->where('rut','LIKE',"%{$filters['rut']}%");
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
            
            $data->clients = $datas->get();
            
            if($request->get('with')=="contacts"){
                $contacts = [];
                foreach ($data->clients as $key => $value) {
                    $con = ClientContact::where("client_id", $value->id)->get();
             
                    if($con !== null){
                        foreach ($con as $key => $value) {
                           array_push($contacts, $value);
                        }
                    }  
                }

                $data->contacts = $contacts;

                foreach ($data->contacts as $key => $value) {
                    
                    $sup = Client::where('id', $value->client_id)->first();
                    $con = Contact::where('id', $value->contact_id)->first();
                    $data->contacts[$key]->client = $sup;
                    $data->contacts[$key]->contact = $con;
                }

                $data->contactsTotal = count($contacts);
                $data->contactsFiltered = count($contacts);
            }

            return $this->json($request, $data, 200);

        }
        else{

            Log::info( __FUNCTION__ . " ELSE ");

            $data->clients = $datas->get();
            
            if($request->get('with')=="contacts"){
                $contacts = [];
                foreach ($data->clients as $key => $value) {
                    $con = ClientContact::where("client_id", $value->id)->get();

                    if($con !== null){
                        foreach ($con as $key => $value) {
                           array_push($contacts, $value);
                        }
                    }  
                }

                $data->contacts = $contacts;

                foreach ($data->contacts as $key => $value) {
                    
                    $sup = Client::where('id', $value->client_id)->first();
                    $con = Contact::where('id', $value->contact_id)->first();
                    $data->contacts[$key]->client = $sup;
                    $data->contacts[$key]->contact = $con;

                }

                $data->contactsTotal = count($data->contacts);
                $data->contactsFiltered = count($data->contacts);
            }

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
        Log::info(__FUNCTION__);
        try {

            $this->_method = $request->method();

            $validator = Validator::make($request->all(),[
                'name'              => 'required|max:191',
                'address'           => 'required|max:191',
                'comune_id'         => 'required|max:191',
                'email'             => 'required|max:191',
                'industries'        => 'required|max:191',
                'rut'               => 'required|string|min:9|max:10',
                'discount_percent'  => 'nullable|numeric|min:0|max:99'
                
            ],
            [
                'rut.required'      => 'Rut es obligatorio y debe ser escrito sin puntos y con guión',
            ]);

            if($validator->fails()){
                Log::info("message=[{$validator->errors()}]");
                $errors = ['errors' => $validator->errors()];
                return $this->json($request,$errors,200);
            }

            $rut = $request->input('rut');
            $rut = str_replace('.','',$rut);
            $rut = explode('-', $rut);
            
            if(count($rut) != 2) {
                return $this->json($request,['errors' => [
                    'rut' => [
                        'Rut ingresado incorrectamente'
                    ] 
                 ]],200);
            }

            $client = Client::where('rut', $rut[0])
                ->where('rut_dv', $rut[1])
                ->first();
            
            if($client != null) {
                return $this->json($request,['errors' => [
                   'rut' => [
                       'Ya existe rut en el sistema'
                   ] 
                ]],200);
            }
            if ( $request->get('internal_code') !== null ){
                $clientIC = Client::where('internal_code', $request->get('internal_code'))
                ->first();

                if($clientIC != null) {
                    return $this->json($request,['errors' => [
                    'internal_code' => [
                        'Ya existe codigo interno (rut chico) en el sistema'
                    ] 
                    ]],200);
                }
            }
            Log::info($request);
            $data = new Client();
            $data->fill($request->all());
            $user = User::find($request->user()->id);
            $data->rut     = $rut[0];
            $data->rut_dv  = $rut[1];
            $data->name = strtoupper($request->get('name'));
            $data->address = strtoupper($request->get('address'));
            $data->comune_id = strtoupper($request->get('comune_id'));

            $data->phone = $request->get('phone');

            $data->email = strtolower($request->get('email'));

            $data->industries = strtoupper($request->get('industries'));
            $data->internal_code    = strtoupper($request->get('internal_code'));            
            $data->enterprise_id = $user->enterprise->id;
            $data->has_discount = $request->get('has_discount');
            $data->discount_percent = $request->get('discount_percent');
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
        Log::info(__FUNCTION__);
        try {

            $data = Client::find($id);
            
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

    public function showClients(Request $request, $id)
    {
        Log::info(__FUNCTION__);
        try {
            $datas = new \stdClass();
            $data = Client::find($id);
            if($data === null) {
                return $this->json($request, $data, 404);
            }
            $datas->client = $data;

           
            return $this->json($request, $datas, 200);

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
        Log::info(__FUNCTION__);
        try{
            $this->_method = $request->method();
            $this->_id     = $id;
            
            $data = Client::find($id);

            if ($data === null)
            {
                return $this->json($request, $data, 404);
            }

            $validator = Validator::make($request->all(),[
                'name'            => 'required|max:150',
                'address'            => 'max:150',
                'rut'            => 'required|string|min:9|max:10',
                'discount_percent'  => 'nullable|numeric|min:0|max:99'
                
            ],
            [
                'rut.required' => 'Rut es obligatorio y debe ser escrito sin puntos y con guión'
            ]);

            

            if($validator->fails()){
                Log::info("message=[{$validator->errors()}]");
                $errors = ['errors' => $validator->errors()];
                return $this->json($request,$errors,200);
            }

            $rut = $request->input('rut');
            $rut = str_replace('.','',$rut);
            $rut = explode('-', $rut);
            
            if(count($rut) != 2) {
                return $this->json($request,['errors' => [
                    'rut' => [
                        'Rut ingresado incorrectamente'
                    ] 
                 ]],200);
            }

            $clientV = Client::where('id', $id)
                ->first();
            
            if($clientV->rut != $rut[0]) {

                $client = Client::where('rut', $rut[0])
                    ->where('rut_dv', $rut[1])
                    ->first();

                if($client != null){
                    return $this->json($request,['errors' => [
                        'rut' => [
                            'Ya existe rut en el sistema'
                        ] 
                    ]],200);
                }
            }
            if ( $request->get('internal_code') !== null ){
                        
                if($clientV->internal_code != $request->get('internal_code')) {
                            
                    $client = Client::where('internal_code', $request->get('internal_code'))
                        ->first();

                    if($client != null){
                        return $this->json($request,['errors' => [
                            'internal_code' => [
                                'Ya existe codigo interno (rut chico) en el sistema'
                            ]
                        ]],200);
                    }
                }
            }
            
            if ($validator->fails())
            {
                Log::info("message=[{$validator->errors()}]");
                $errors = ['errors' => $validator->errors()];
                return $this->json($request,$errors,200);
            }

            $user = $request->user();

            $data->fill($request->all());      
            
            $user = User::find($request->user()->id);
            
            $data->rut              = $rut[0];
            $data->rut_dv           = $rut[1];
            $data->name             = strtoupper($request->get('name'));
            $data->address          = strtoupper($request->get('address'));
            $data->comune_id        = strtoupper($request->get('comune_id'));

            $data->phone            = $request->get('phone');

            $data->email            = strtolower($request->get('email'));

            $data->industries       = strtoupper($request->get('industries'));
            $data->internal_code    = strtoupper($request->get('internal_code'));
            $data->has_discount     = $request->get('has_discount');
            $data->discount_percent = $request->get('discount_percent');
            $data->enterprise_id    = $user->enterprise->id;
            $data->updated_at       = Carbon::now();
            $data->updated_by       = $request->user()->id;

            $data->save();
            return $this->json($request, $data, 200);
        }
    
        catch(\Exception $e) 
        {
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
        Log::info(__FUNCTION__);
        try{
            $data = Client::find($id);

            if($data === null) {
                return $this->json($request, $data, 404);
            }

            $contacts = ClientContact::where('Client_id', $id)->first();
            
            if ( $contacts !== null){
                $data = new \stdClass();
                $errors = ["message" => "El Cliente posee contactos. No puede ser eliminado"];
                $data->errors = $errors;
                
                return $this->json($request, $data, 200);
            }

            $data->delete();
            return $this->json($request, $data, 200);
        } catch(\Exception $e) {
            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
            return $this->json($request, null, 500);
        }
    }

    public function list(Request $request, $type, $value) {
        Log::info(__FUNCTION__);
        switch($type) {
            case 'name' :
                $clients = Client::where('name', 'like', '%'.(string)$value.'%')
                                 ->limit(10)
                                 ->get([
                                     'id',
                                     'name'
                                 ]);
                return $this->json($request, $clients, 200);
            break;
            case 'rut':

                
                $clients['rut']                    = Client::where(DB::raw('CONCAT(rut,"-", rut_dv)'),'like', '%'.$value.'%')
                                                    ->limit(10)
                                                    ->get([
                                                        'id',
                                                        'rut',
                                                        'rut_dv'
                                                        ]);
                $clients['codigo_interno'] = Client::where('internal_code','like', '%'.$value.'%')
                                                        ->limit(3)
                                                        ->get([
                                                        'id',
                                                        'internal_code'
                                                        ]);
                
                //  print_r($clients);
                //  exit;                       
                
                return $this->json($request, $clients, 200);                   
            break;
        }
    }
    
    public function save(Request $request) {
        Log::info(__FUNCTION__);
        try {
            $emails = $request->input('email');
            $emailSplit = explode(',', $emails);
            if(count($emailSplit) > 1) {
                $validator = Validator::make([
                    'rut' => $request->input('rut'),
                    'name' => $request->input('name'),
                    'email' => $emailSplit,
                    'emailLength' => $emails,
                    'address' => $request->input('address'),
                    'comune_id' => $request->input('comune_id'),
                    'industries' => $request->input('industries')
                ], [
                    'rut'     => 'required|string',
                    'name'    => 'required|string',
                    'email.*'   => 'required|email',
                    'emailLength' => 'required|max:191',
                    'address' => 'required|string',
                    'comune_id' => 'sometimes|string',
                    'industries' => 'sometimes|string'
                ]);
                if ($validator->fails()) {
                    throw new \Exception(0);
                }
            } else {
                $validator = Validator::make($request->all(), [
                    'rut'     => 'required|string',
                    'name'    => 'required|string',
                    'email'   => 'sometimes|email|max:191',
                    'address' => 'required|string',
                    'comune_id' => 'sometimes|string',
                    'industries' => 'sometimes|string'
                ]);
                if ($validator->fails()) {
                    throw new \Exception(0);
                }
            }
            $rut = $request->input('rut');
            $rut = str_replace('.','',$rut);
            $rut = explode('-', $rut);
            if(count($rut) != 2) {
                throw new \Exception(0);
            }
            $client = Client::where('rut', $rut[0])
                            ->where('rut_dv', $rut[1])
                            ->first();
            if($client != null) {
                throw new \Exception(1);
            }
            $client = new Client();
            $client->rut            = $rut[0];
            $client->rut_dv         = $rut[1];
            $client->name           = strtoupper($request->input('name'));
            $client->comune_id      = strtoupper($request->get('comune_id'));
            $client->industries     = strtoupper($request->get('industries'));
            $client->email          = $request->input('email');
            $client->address        = strtoupper($request->input('address'));
            $client->enterprise_id  = $request->user()->enterprise->id;
            $client->created_by     = $request->user()->id;

            $client->save();
            return $this->json($request, null, 201);
        } catch (\Exception $e) {
            switch($e->getMessage()) {
                case 0:
                    return $this->json($request, null, 401);
                break;
                case 1:
                    return $this->json($request, null, 402);
                break;
                default :
                    Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
                    return $this->json($request, null, 500);
                break;
            }
        }
    }
    
    private function rules()
    {
        Log::info(__FUNCTION__);
        switch ($this->_method)
        {
            case 'POST':
                return [
                    'name'            => 'required|max:150',

                ];
                break;

            case 'PUT':
                return [
                    'name'            => 'required|max:150'
                ];
                break;
        }

    }

    public function messages()
    {
        Log::info(__FUNCTION__);
        return [
            'nombre.required' => 'Nombre es requerido',
        ];
    }
}
