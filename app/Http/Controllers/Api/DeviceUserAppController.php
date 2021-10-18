<?php

namespace App\Http\Controllers\Api;

use DB;
use App\DeviceUserApp;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class DeviceUserAppController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data  = new \stdClass();
		$datas = new \stdClass();
		
		$with    = $request->get('with');
		$filters = $request->get('filters');
		
		if ($with != null)
		{
			
			$inflator = explode(',',$with);
			$data = DeviceUserApp::with($inflator)->whereNotNull('measure_id');
			
            if ($request->has('filters.user_id'))
            {
                if($request->input('filters.user_id') != 0 && $request->input('filters.user_id') != '')
                {
                    $data = $data->where('user_id',$request->input('filters.user_id'));
                }
            }
			
		}
		else
		{
			$data = DeviceUserApp::whereNotNull('measure_id');
		}
		
		if($request->user()->admin == 0)
		{
			$data = $data->where('user_id',$request->user()->id);
        }
        
        if ($request->has('filters.user_id'))
        {
            if($request->input('filters.user_id') != 0 && $request->input('filters.user_id') != '')
            {
                $data = $data->orderBy('updated_at','desc')->limit(1);
            }
        }
		
		
		$datas->device_user_apps = $data->get();
		
		return $this->json($request, $datas, 200);
		
		
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
        //
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

			$with = $request->get("with");
			
			if ($with != null)
			{
				$inflator = explode(',',$with);
				$data = DeviceUserApp::with($inflator)->where("id",$id)->get();
			}
			else
			{
				$data = DeviceUserApp::find($id);
			}
		
            
            
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
