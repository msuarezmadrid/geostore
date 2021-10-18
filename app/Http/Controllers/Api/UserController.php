<?php

namespace App\Http\Controllers\Api;

use DB;
use Excel;
use Log;
use Image;
use Storage;
use App\User;
use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Hash;

class UserController extends ApiController
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
            $datas = new User();
            $user = User::find($request->user()->id);
            $datas = $datas->where('enterprise_id', $user->enterprise->id);
            $data->recordsTotal = $datas->count();

            if ($start !== null && $length !== null) {

                if ($filters !== null) {
                    if ($filters['name'] !== null) {
                        $datas = $datas->where('name', 'LIKE', "%{$filters['name']}%");
                    }
                    if ($filters['email'] !== null) {
                        $datas = $datas->where('email', 'LIKE', "%{$filters['email']}%");
                    }
                }

                if ($order !== null) {
                    $datas = $datas->orderBy($field, $dir);
                }

                $data->draw = $draw;
                $data->recordsFiltered = $datas->count();
                if ($length != -1) {
                    $datas = $datas->offset($start)->limit($length);
                }

            }
            $data->users = $datas->get();

            

            return $this->json($request, $data, 200);
        }
        else{

            $datas = User::get();

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

            $data = new User();
            $user = User::find($request->user()->id);

            $userCheck = $data->where('email', $request->input('email'))->first();

            if($userCheck != null) {
                $errors = ['errors' => [ 'email' => ['El correo ingresado ya está asociado a un usuario en el sistema']]];
                return $this->json($request,$errors,200);
            } else {
                $data->fill($request->all());
                $data->enterprise_id = $user->enterprise->id;
                $data->password = bcrypt($request->get('password'));
                $data->created_by = $request->user()->id;
                $data->updated_by = $request->user()->id;
                $data->save();
                return $this->json($request, $data, 201);
            }


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

            $data = User::find($id);
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

            $data = User::find($id);

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
            if ($request->get('password')!==null) {
                $data->password = bcrypt($request->get('password'));
            }
            
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
            $data = User::find($id);

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


    public function avatar(Request $request)
    {
        try
        {
            $this->_method = $request->method();

            $id = $request->input('id');

            $rule = [
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'id' => 'required|integer'
            ];

            $validator = Validator::make($request->all(), $rule);
                
            if($validator->fails()){
                Log::info("message=[{$validator->errors()}]");
                $errors = ['errors' => $validator->errors()];
                return $this->json($request,$errors,200);
            }

            $data = User::find($id);
            
            $data->fill($request->all());

            $avatar = $request->file('file');

            $filename = time() . '.' . $avatar->getClientOriginalExtension();

            $folder = public_path('uploads/avatars/');
            if (!file_exists($folder)) {
                mkdir($folder, 0777, true);
            }

            
            Image::make($avatar)->resize(300, 300)->save( public_path('uploads/avatars/' . $filename ));


            //Storage::disk('public')->putFileAs('avatar/', $avatar, $filename);
            
            $data->avatar = $filename;
            $data->save();

            
            return $this->json($request, $data, 201);
        }
        catch(\Exception $e)
        {
            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
            return $this->json($request, null, 500);
        }
    }

    public function export(Request $request)
    {
        try{
            $with = $request->get('with');
    
            $filters = $request->input('filters');
            $datas = new User();
            if ($with === null)
            {
                $datas = new User();
            } else 
            {
                $inflator = explode(',', $with);
                $datas = User::with($inflator);
            }
            
            if ($filters !== null)
            {
                if ($filters['name'] !== null) {
                    $datas = $datas->where('name', 'LIKE', "%{$filters['name']}%");
                }
                if ($filters['email'] !== null) {
                    $datas = $datas->where('email', 'LIKE', "%{$filters['email']}%");
                }

            }
            
            $total = $datas->count();
            $datas = $datas->select(DB::raw('name as Nombre, email as Email, admin as Administrador'))->get();
            
            Excel::create('Usuarios_'.Carbon::parse(Carbon::now())->format('d-m-Y'), function($excel) use( $datas,$total ) {

                $excel->sheet('Usuarios', function($sheet) use( $datas,$total ) {

                    $sheet->fromModel($datas,null, 'B2', true);
                    $sheet->setBorder('B2:D'.($total+2),'thin');
                    $sheet->cells('B2:D2', function($cells) {
                        $cells->setBackground('#e6eeff');
                    });
                    
                    
                    
                });
                    // Call writer methods here

            })->download('xls');
        }
        catch(\Exception $e) {
            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
            return $this->json($request, null, 500);
        }

        
    }

    public function profile(Request $request)
    {
        try
        {
            $this->_method = $request->method();

            $rule = [
                'name' => 'required|max:150|string',
                'email' => 'required|max:150|email',
                'password' => 'sometimes|required|confirmed'
            ];

            $data = $request->user();

            if ($data === null)
            {
                return $this->json($request, $data, 404);
            }

            $validator = Validator::make($request->all(), $rule);
            if($validator->fails()){
                Log::info("message=[{$validator->errors()}]");
                $errors = ['errors' => $validator->errors()];
                return $this->json($request,$errors,200);
            }

            $data->fill($request->except(['role_id', 'admin']));
            $data->password = Hash::make($data->password);
            $data->updated_at       = Carbon::now();
            $data->updated_by       = $data->id;
            $data->save();

            return $this->json($request, $data, 200);
        }
        catch(\Exception $e) {
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
                    'email' => 'required|max:150|email',
                    'admin' => 'required|numeric|digits_between: 0,1',
                    'role_id' => 'required|numeric|digits_between: 1,4',
                    'password' => 'required|confirmed'
                ];
                break;

            case 'PUT':
                return [
                    'name' => 'required|max:150|string',
                    'email' => 'required|max:150|email',
                    'admin' => 'required|numeric|digits_between: 0,1',
                    'role_id' => 'required|numeric|digits_between: 1,4',
                    'password' => 'sometimes|required|confirmed'
                ];
                break;
        }

    }
}
