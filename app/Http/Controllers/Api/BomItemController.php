<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\BomItem;
use Log;
use Carbon\Carbon;
use Validator;
use App\User;
class BomItemController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

            $data = new BomItem();
            $data->fill($request->all());
            $user = User::find($request->user()->id);
            //$data->enterprise_id = $user->enterprise->id;
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
    public function show($id)
    {
        //
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
            $this->_id     = $id;
            
            $data = BomItem::find($id);
            
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
            $data->updated_by       = $request->user()->id;;
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
         try {
            $data = BomItem::find($id);
            if($data === null){
                return $this->json($request, null, 404);
            }



            /*$purchasesItems = PurchaseOrderItem::where('item_id', $id)->get();
            if(count($purchasesItems)>0){
                $data = new \stdClass();
                $errors = "El Producto forma parte de al menos una Orden de Entrada. No puede ser eliminado.";
                $data->errors = $errors;
                return $this->json($request, $data, 200);
            }*/

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
                    'item_id'               => 'required|integer',
                    'child_item_id'         => 'required|integer',
                    'amount'                => 'required|numeric',
                    'unit_of_measure_id'    => 'required|integer'
                ];
                break;
            case 'PUT':
                return [
                    'amount'                => 'required|numeric',
                    'unit_of_measure_id'    => 'required|integer'
                ];
                break;
        }

    }
}
