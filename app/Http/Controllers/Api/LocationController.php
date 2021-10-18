<?php

namespace App\Http\Controllers\Api;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ApiController;
use Log;
use Validator;
use App\User;
use Carbon\Carbon;
use App\Location;
use App\LocationType;
class LocationController extends ApiController
{
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


        $datas = new Location();
        $enterprise = $request->user()->enterprise;
        $datas = $datas->where('enterprise_id', $enterprise->id);
        $data->recordsTotal = $datas->count();
        
        if($start !== null && $length !== null) {

            if($filters !== null) {
                if($filters['name'] !== null) {
                    $datas = $datas->where('name','LIKE',"%{$filters['name']}%");
                }
                if($filters['description'] !== null) {
                    $datas = $datas->where('description','LIKE',"%{$filters['description']}%");
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
        $locats = $datas;
        $data->locations = $datas->get();

        $datas = Location::where('location_id', null)->where('enterprise_id', $enterprise->id)->get();
        $orderedTree = [];
        $childs = [];
        foreach ($datas as $key => $value) {
            $loc = Location::find($value->id);
            $tree = $loc->orderedTree();
            foreach ($tree as $key2 => $value2) {
                array_push($orderedTree, $value2);
            }

            $ch = $loc->allChildIds();
            
            array_push($childs, $ch);
            
        }

        $data->tree = $orderedTree;
        $data->childs = $childs;
        //tree

        $locats = $locats->where('location_id', null)->get();
        $locationsTree = [];
        foreach ($locats as $key => $value) {
            $locData  = new \stdClass();
            $locData->location = $value;

            $locData->location_type = LocationType::where('id', $value->location_type_id)->first();
            $locData->childrens = $value->childrens();
            array_push($locationsTree, $locData);
        }

        $data->locations_tree = $locationsTree;
        foreach ($data->locations as $key => $value) {
            $loc = Location::find($value->id);

            $locs = [];
            array_push($locs, $loc->name);
            while($loc->location_id !==null){
                
                $loc = Location::find($loc->location_id);
                
                if($loc!==null) array_push($locs, $loc->name);
            }
            $data->locations[$key]->route = $locs;
        }

        return $this->json($request, $data, 200);


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

            $data = new Location();
            $data->fill($request->all());
            $user = User::find($request->user()->id);

            if($request->get('code')!==null){
                $datas = Location::where('code', $request->get('code'))->where('enterprise_id', $user->enterprise->id)->first();

                if($datas !== null){
                    $errors = ['errors' => ["code"=>'El código proporcionado se encuentra en uso.']];
                    return $this->json($request,$errors,200);
                }

            }else{
                $data->code = "LOC".$data->id;
            }

            
            $user = User::find($request->user()->id);
            $data->name = strtoupper($request->get('name'));
            $data->enterprise_id = $user->enterprise->id;
            $data->created_by = $request->user()->id;
            $data->updated_by = $request->user()->id;
            $data->save();

            if($request->get('code')!==null){
                $data->code = $request->get('code');

            }else{
                $data->code = "LOC".$data->id;
            }
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
        try {
            $data = Location::find($id);

            if($data === null) {
                return $this->json($request, $data, 404);
            }

            if ($request->get('with')=="possibleFathers"){
                $datas = $data->possibleFathers();
                $cantBeFathers = $datas->cantBeFathers;
                $ids = [];
                foreach ($cantBeFathers as $key => $value) {
                    array_push($ids, $value->id);
                }

                $datas = Location::whereNotIn('id', $ids)->where('location_id', null)->get();
                $data = new \stdClass();
                $data->locations = $datas;
                $orderedTree = [];
                foreach ($data->locations as $key => $value) {
                    $loc = Location::find($value->id);
                    $tree = $loc->orderedTree();
                    foreach ($tree as $key2 => $value2) {
                        if($value2["id"]!=$id){
                            array_push($orderedTree, $value2);  
                        } 
                    }
                }

                $data->tree = $orderedTree;

                return $this->json($request, $data, 200);
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
            $data = Location::find($id);

            if($data === null) {
                return $this->json($request, $data, 404);
            }

            $this->_method = $request->method();
            $validator = Validator::make($request->all(), $this->rules());

            if($validator->fails()){
                Log::info("message=[{$validator->errors()}]");
                $errors = ['errors' => $validator->errors()];
                return $this->json($request,$errors,200);
            }

            $data->fill($request->all());
            $user = User::find($request->user()->id);
            $data->name = strtoupper($request->get('name'));
            $data->enterprise_id = $user->enterprise->id;
            $data->updated_by = $request->user()->id;
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
            $data = Location::find($id);

            if($data === null) {
                return $this->json($request, $data, 404);
            }

            $locs = Location::where('location_id', $id)->get();

            if($locs !== null){
                $data = new \stdClass();
                $errors = "El Almacén posee sub-almacenes. No puede ser eliminado.";
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

    private function rules()
    {
        switch ($this->_method)
        {
            case 'POST':
                return [
                    'name' => 'required|max:150'
                    
                    /*'x' => 'integer',
                    'y' => 'integer',
                    'z' => 'integer',
                    'latitude' => 'float',
                    'longitude' => 'float',*/
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
