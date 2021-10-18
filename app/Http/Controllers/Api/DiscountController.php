<?php


namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Discount;
use App\Http\Controllers\ApiController;
use Log;
use Validator;
use DB;
use Carbon\Carbon;

class DiscountController extends ApiController
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
        
        $datas = new Discount();
        //$user = User::find($request->user()->id);
        //$datas = Category::where('enterprise_id', $user->enterprise->id);
        //return $this->json($request, $datas->get(), 200);
        $data->recordsTotal = $datas->count();
        if ($start !== null && $length !== null) 
        {
            if ($filters !== null) 
            {
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
            
            $data->discounts = $datas->get();
           
            return $this->json($request, $data, 200);
            
        }else
        {

            $data->discounts = $datas->get();
           /* foreach ($data->categories as $key => $value) {
                $cat = Category::find($value->id);
                $data->categories[$key]->full_route = $cat->fullRoute();
            }*/
            $data->recordsTotal = count($data->discounts);
            $data->recordsFiltered = count($data->discounts);

            return $this->json($request, $data, 200);
        }

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
            $data = new Discount();
            $data->fill($request->all());
           // $user = User::find($request->user()->id);
            /*$data->enterprise_id = $user->enterprise->id;
            $data->created_by = $request->user()->id;
            $data->updated_by = $request->user()->id;*/
            $data->save();

            
            DB::commit();
            return $this->json($request, $data, 201);

        } catch(\Exception $e) {
            DB::rollback();
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
            $data = Discount::find($id);

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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        try {

            $data = Discount::find($id);
            
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
            $this->_id     = $id;
            
            $data = Discount::find($id);
            
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
            $data->updated_at       = Carbon::now();
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
                    'percent' => 'required'
                ];
                break;

            case 'PUT':
                return [
                    'name' => 'required|max:150|string',
                    'percent' => 'required'
                ];
                break;
        }

    }

}
