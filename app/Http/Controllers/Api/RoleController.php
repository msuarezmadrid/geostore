<?php

namespace App\Http\Controllers\Api;

use App\User;
use App\Role;
use App\Permission;
use App\RolePermission;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Carbon\Carbon;
use Validator;
use Log;


class RoleController extends ApiController
{
     private $_method = '';
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

        $filters = $request->input('filters');
        $data->recordsFiltered = 0;
        $datas = new Role();
        $user = User::find($request->user()->id);
        $datas = $datas->where('enterprise_id', $user->enterprise->id);
        if($start !== null && $length !== null) {

            if($filters !== null) {
                if($filters['nombre'] !== null) {
                    $datas = $datas->where('name','LIKE',"%{$filters['name']}%");
                }
            }

            if($order !== null) {
                $datas = $datas->orderBy($field, $dir);
            }

            $data->draw = $draw;
            $data->recordsFiltered = $datas->count();
            $datas = $datas->offset($start)->limit($length);
        }

        if($data->recordsFiltered == 0) $data->recordsFiltered = $datas->count();
        $data->recordsTotal = $datas->count();
        $data->roles = $datas->get();
        

        return $this->json($request, $data, 200);
    }

    public function show(Request $request, $id)
    {

        try{
            $data = new \stdClass();
            $role = Role::find($id);
            if($role === null){
                return $this->json($request, null, 404);
            }

            $data->role = $role;
            $data->permissions = $role->permissions;
            $data->recordsFiltered = count($role->permissions);
            $data->recordsTotal = count($role->permissions);

            if($request->get('with')== 'all-permissions'){
                $flag = false;
                $permissions = Permission::all();
                $data->allpermissions = $permissions;
                foreach ($data->allpermissions as $key => $value) {
                    foreach ($role->permissions as $permission) {
                        if($data->allpermissions[$key]->name == $permission->name){
                            $flag = true;
                        }
                    }
                    if($flag){
                        $data->allpermissions[$key]->value = 1;
                        $flag = false;
                    }else{
                        $data->allpermissions[$key]->value = 0;
                    }
                }
            }

            $data->recordsFiltered = count($data->allpermissions);
            $data->recordsTotal = count($data->allpermissions);

            return $this->json($request, $data, 200);
        }
        catch(\Exception $e) {
            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
            return $this->json($request, null, 500);
        }
    }

    public function updatePermissions(Request $request, $id)
    {
        try{
            $data = new \stdClass();
            $role = Role::find($id);
            if($role === null){
                return $this->json($request, null, 404);
            }
            $validator = Validator::make($request->all(), ['value' => 'required|boolean', 'permission_id' => 'required|integer']);
            if($validator->fails()){
                Log::info("message=[{$validator->errors()}]");
                $errors = ['errors' => $validator->errors()];
                return $this->json($request,$errors,200);
            }
            if($request->get('value')==1){
                $p = Permission::find($request->get('permission_id'));
                if($p!==null){
                    $rp = new RolePermission();
                    $rp->role_id = $id;
                    $rp->permission_id = $request->get('permission_id');
                    $rp->created_by = $request->user()->id;
                    $rp->updated_by = $request->user()->id;
                    $rp->save();
                }else{
                    $errors = ['errors' => ['permission' => 'El permiso que se quiere actualizar no existe']];
                    return $this->json($request,$errors,200);
                }
                
            }else{
                $role->permissions()->detach($request->get('permission_id'));
            }
            $data = $role;
            $role->save();

            return $this->json($request, $data, 200);
        }
        catch(\Exception $e) {
            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
            return $this->json($request, null, 500);
        }
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

            $user = User::find($request->user()->id);

            $data = new Role();
            $data->fill($request->all());
            $data->enterprise_id = $user->enterprise->id;
            $data->created_by = $user->id;
            $data->updated_by = $user->id;
            $data->save();
            return $this->json($request, $data, 201);

        } catch(\Exception $e) {
            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
            return $this->json($request, null, 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {

            $data = Role::find($id);
            if($data === null){
                return $this->json($request, null, 404);
            }
            $data->deleted_at = Carbon::now();
            $data->save();
            return $this->json($request, $data, 201);

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
