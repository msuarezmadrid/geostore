<?php

namespace App\Http\Controllers\Api;

use Log;
use App\Device;
use App\Command;
use Illuminate\Http\Request;
use App\Jobs\ProcessCommand;
use App\Http\Controllers\ApiController;

class CommandController extends ApiController
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
     * {
     * 	"appId": 1, 
     * 	"battery": 50, 
     * 	"deviceType": 1, 
     * 	"location": { "lat": -33.23629833333334, "lon": -77.29999833333333, "timestamp": 1473953937},
     * 	"timestamp": 1473953948, 
     * 	"type": "app1", 
     * 	"uuid": "fs5daozISherJY3aAZcpXg"
     * 	} 
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   
   
	public function store(Request $request){
		try {

			if($request->isJson()) {

				$first  = true;
				$response = array();
				$data = $request->getContent();

				$jsonData = json_decode($request->getContent());

				//TODO: encolar esta petición REDIS/MEMCACHED,NFQ
				$command              = new Command();
				$command->raw         = $data;
                $command->ip          = $request->ip();
				$command->status      = 1;
				$command->io          = 0;
				$command->created_by  = $request->user()->id;
				$command->updated_by  = $request->user()->id;
				$command->save();
				
				Log::info("send to queue!!"); 
				//Log::info(print_r($command)); 
				dispatch(new ProcessCommand($command));

				
				$response = array( 'code' => -1);				
				
				if($command != null) {

					$settings = array();

					//TODO: pensar como resolver esto y dejarlo asincronico
					Log::info("uuid=[".json_encode($jsonData)."]");
					
					$uuid = 0;
					
					if (is_object($jsonData))
					{
						$uuid = $jsonData->uuid;
					}
					if (is_array($jsonData))
					{
						$uuid = $jsonData[0]->uuid;
					}
					
					$device = Device::where('uuid', $uuid)->first();

					if($device != null) {

						$settings['status'] = (int)$device->status;

						//Se revisa si existe algún comando para enviarle al dispositivo.
						$commandOuts = Command::where('device_id', $device->id)
							->where("io",1)
							->where("status",0)->get();

						if($commandOuts != null) {
							foreach($commandOuts as $commandOut){
								$cmd = explode(',', $commandOut->raw);
								switch($cmd[0]){
									case 'tracking':
										$settings['interval'] = (int)$cmd[1];
										break;
								}

								$commandOut->status = 4;
								$commandOut->save();
							}
						}

						if(count($settings) > 0){
							$response['settings'] = $settings;
						}
					}

					$response = array( 'code' => 0); 
				}
				
                return $this->json($request, $response, 200);
			}

			return $this->json($request, null, 400);

		} catch(\Exception $e) {
            Log::critical(
                "code=[{$e->getCode()}] 
                file=[{$e->getFile()}] 
                line=[{$e->getLine()}] 
                message=[{$e->getMessage()}]"
            );
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
