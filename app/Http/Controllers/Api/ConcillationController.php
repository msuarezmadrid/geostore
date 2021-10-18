<?php

namespace App\Http\Controllers\Api;

use Log;
use DB;
use Validator;
use App\ConcilliationDoc;
use App\ConciliationDetail;
use App\File;
use App\Client;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Image;
use Illuminate\Support\Facades\Storage;

class ConcillationController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try{
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


            $datas = new ConcilliationDoc();
            $data->recordsTotal = $datas->count();
            
            if($start !== null && $length !== null) {

                if($filters !== null) {
                    if($filters['movement_status_id'] !== null) {
                        $datas = $datas->where('status',$filters['movement_status_id']);
                        $data->recordsTotal = $datas->count();
                    }
                    if($filters['code'] !== null) {
                        $datas = $datas->where('id','LIKE',"%{$filters['code']}%");
                    }

                    if($filters['client_id'] !== null) {
                        $datas = $datas->where('client_id',$filters['client_id']);
                    }
                    
                    if($filters['start_date'] !== null && $filters['end_date'] !== null) {
                        $datas = $datas->whereBetween('created_at', array($filters['start_date'],$filters['end_date']) );
                    }
                }

                if($order !== null) {
                    $datas = $datas->orderBy($field, $dir);
                }

                $data->draw = $draw;
                $data->recordsFiltered = $datas->count();
                $datas = $datas->offset($start)->limit($length);
            }

            
            $data->concilliations = $datas->get();
            if($data->recordsFiltered == 0) $data->recordsFiltered = $datas->count();
            
            foreach ($data->concilliations as $key => $value) {
                $data->concilliations[$key]->client = Client::find($value->client_id);
                if($filters['movement_status_id'] == 'Conciliado'){
                    $data->concilliations[$key]->conciliation_detail = ConciliationDetail::where('doc_id', '=',$value->id)->get();
                }
            }
            
            return $this->json($request, $data, 200);
        }catch(\Exception $e) {
            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
            return $this->json($request, null, 500);
            
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
            


            DB::beginTransaction();
            $data = new ConcilliationDoc();
            $user = User::find($request->user()->id);
            $data->fill($request->all());
            switch($request->get('payment_method')){
                case '0':
                    $data->payment_method = 'cash';
                break;
                case '1':
                    $data->payment_method = 'card';
                break;
                case '2':
                    $data->payment_method = 'cheque';
                break;
                case '3':
                    $data->payment_method = 'transfer';
                break;
            }
            $data->save();

            if ($request->file('file')!==null) {
                $fileData = new File();
                $fileData->description = "concilliation_image";
                $fileData->object_id = $data->id;
                $fileData->object_type = "concilliation_docs";
                $fileData->type = "IMG";

                $file = $request->file('file');

                if ($file->getClientSize() >= '5500000'){
                    $errors = ['errors' => 'Tamaño de archivo no debe superar los 5 MB'];
                    return $this->json($request,$errors,200);
                }

                $fileRoute = str_replace(' ','_', $file->getClientOriginalName());
                $filename = time(). "_" . $fileRoute;

                $fileData->name = $filename;

                $folder = public_path('storage/uploads/concilliation/');
                if (!file_exists($folder)) {
                    mkdir($folder, 0777, true);
                }

                if(strpos($file->getClientMimeType(),'image')!== false){
                    Image::make($file)->save( public_path('storage/uploads/concilliation/' . $filename ));
                }else{
                
                    Storage::disk('public')->put('uploads/concilliation/'.$filename, file_get_contents($file) );
                    $fileData->description = "concilliation_file";
                    $fileData->type = "DOC";
                }

                //Storage::disk('public')->putFileAs($data->object_type.'/'.$data->object_id, $file, $filename);

                //$storagePath  = Storage::disk('uploads')->getDriver()->getAdapter()->getPathPrefix();

                
                $fileData->route = 'uploads/concilliation';

                $fileData->filename = $filename;
                $fileData->enterprise_id            = $user->enterprise->id;
                $fileData->created_by               = $request->user()->id;
                $fileData->updated_by               = $request->user()->id;
                $fileData->save();
            }

            DB::commit();
            return $this->json($request, $data, 201);

        } catch(\Exception $e) {
            DB::rollback();
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
            $datas = new \stdClass();
            $data = ConcilliationDoc::find($id);

            if($data === null) {
                return $this->json($request, $data, 404);
            }
            $datas->concilliation = $data;

            $imgs = File::where('object_id', $id)
                        ->where('object_type', 'concilliation_docs')
                        ->where('type', "IMG")
                        ->get();

            $datas->images = $imgs;
            return $this->json($request, $datas, 200);
			
            
            

        } catch(\Exception $e) {
            Log::critical("code=[{$e->getCode()}] file=[{$e->getFile()}] line=[{$e->getLine()}] message=[{$e->getMessage()}]");
            return $this->json($request, null, 500);
        }    }

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
            $data = ConcilliationDoc::find($id);


            if($data === null) {
                return $this->json($request, $data, 404);
            }

            if($request->get('movement_status_id')=='No Conciliado'){
                DB::beginTransaction();
                $data->status = 'No Conciliado';
                $data->save();
                DB::commit();
                return $this->json($request, $data, 200);
            }

            if($data->status == 'No Conciliado' && $request->get('movement_status_id')=='Conciliado'){

                DB::beginTransaction();
                $data->status = 'Conciliado';
            }

            $this->_method = $request->method();
            $validator = Validator::make($request->all(), $this->rules());

            if($validator->fails()){
                Log::info("message=[{$validator->errors()}]");
                $errors = ['errors' => $validator->errors()];
                return $this->json($request,$errors,200);
            }

            $data->fill($request->all());
            switch($request->get('payment_method')){
                case '0':
                    $data->payment_method = 'cash';
                break;
                case '1':
                    $data->payment_method = 'card';
                break;
                case '2':
                    $data->payment_method = 'cheque';
                break;
                case '3':
                    $data->payment_method = 'transfer';
                break;
            }
            $data->save();
            DB::commit();
            return $this->json($request, $data, 200);
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
    public function destroy(Request $request, $id)
    {
        try{
            $data = ConcilliationDoc::find($id);

            if($data === null) {
                return $this->json($request, $data, 404);
            }

            $items = File::where('object_id', $id)
                        ->where('object_type','concilliation_docs')
                        ->delete();
            
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
                    'client_id'     => 'required',
                    'payment_method'     => 'required',
                    'amount'     => 'integer',
                ];
                break;

            case 'PUT':
                return [
                    'date'            => 'date',
                ];
                break;
        }

    }
}
