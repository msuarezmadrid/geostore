<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Input;
use App\File;
use App\User;
use Illuminate\Validation\Rule;
use Validator;
use Log;
use Storage;
use Image;

class FileController extends ApiController
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
        $filters = $request->input('filters');
        $data->recordsFiltered = 0;

        $datas = new File();

        if($start !== null && $length !== null) {

            if($filters !== null) {

                if($filters['doc_id'] !== null) {
                    $datas = $datas->where('object_id',$filters['doc_id']);
                }
            }



            $data->draw = $draw;
            $data->recordsFiltered = $datas->count();
        }

        $data->files = $datas->get();
        $data->recordsFiltered = $datas->count();
        if($data->recordsFiltered == 0) $data->recordsFiltered = $datas->count();

        $data->recordsTotal = File::count();

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
        try
        {
            $this->_method = $request->method();

            $validator = Validator::make($request->all(), $this->rules(), $this->messages());
                
            if($validator->fails()){
                Log::info("message=[{$validator->errors()}]");
                $errors = ['errors' => $validator->errors()];
                return $this->json($request,$errors,200);
            }

            $data = new File();
            $data->fill($request->all());

            $file = $request->file('file');

            $fileRoute = str_replace(' ','_', $file->getClientOriginalName());

            $filename = time(). "_" . $fileRoute;

            if ($request->get('object_type')=='concilliation_docs'){
                $folder = public_path('storage/uploads/concilliation/');
                if (!file_exists($folder)) {
                    mkdir($folder, 0777, true);
                }
                if(strpos($file->getClientMimeType(),'image')!== false){
                    Image::make($file)->save( public_path('storage/uploads/concilliation/' . $filename ));
                    $data->description = "concilliation_image";
                    $data->type = "IMG";
                }else{
                    Storage::disk('public')->put('uploads/concilliation/'.$filename, file_get_contents($file) );
                    $data->description = "concilliation_file";
                    $data->type = "DOC";
                }
                $data->route = 'storage/uploads/concilliation';
                $data->name = $filename;
            }else{
                Image::make($file)->save( public_path('uploads/items/' . $filename ));
                $data->description = 'concilliation_image';
                $data->route = 'uploads/items';
            }

            //Storage::disk('public')->putFileAs($data->object_type.'/'.$data->object_id, $file, $filename);

            //$storagePath  = Storage::disk('uploads')->getDriver()->getAdapter()->getPathPrefix();

            
            

            $data->filename = $filename;
            $user = User::find($request->user()->id);
            $data->enterprise_id            = $user->enterprise->id;
            $data->created_by               = $request->user()->id;
            $data->updated_by               = $request->user()->id;
            $data->save();
            
            return $this->json($request, $data, 201);
        }
        catch(\Exception $e)
        {
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
        try
        {   
            $filter = $request->get('filters');
            $data = File::find($id);
            if ($filter['doc_type'] == 'concilliation'){
                
                $storagePath = Storage::url(env('APP_URL').$data->route."/".$data->filename);
            }else{
                $storagePath = Storage::get($data->object_type."/".$data->object_id."/".$data->filename);
            }
            
            return $this->json($request, $storagePath, 201);
        }
        catch(\Exception $e)
        {
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
        //
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
            $data = File::find($id);

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
                    'description'           => 'max:150|string',
                    'object_id'             => 'required|integer',
                    'object_type'           => 'required|string',
                    'name'                  => 'required|string',
                    'type'                  => 'required|string',
                    'file'                  => 'required|file',
                    'file'                  => 'required_if:type,IMG|image|mimes:jpg,jpeg,png',
                    'imageFileExtension'    => ['required_if:type,IMG', Rule::NotIn(['jfif'])]
                ];
            break;
        }
    }

    private function messages() 
    {
        switch ($this->_method)
        {
            case 'POST':
                return [
                    'file.uploaded' => 'El archivo no se pudo subir, es posible que el archivo sea demasiado grande',
                    'imageFileExtension.required_if' => 'El archivo debe tener una extensión',
                    'imageFileExtension.not_in' => 'La extensión del archivo debe ser no puede ser jfif. Formatos validos: [jpg, jpeg, png]',
                ];
            break;
        }
    }
}
