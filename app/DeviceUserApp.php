<?php

namespace App;

use Log;
use App\Config;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class DeviceUserApp extends Model
{
    
	public function measure()
	{
		return $this->belongsTo('App\Measure','measure_id');
	}
	
	public function lastmeasure()
	{
		$value = 1;
		$config = Config::where('param','CONFIG.LAST_ACTIVITY')->first();
		if($config != null) $value = $config->value;
		
				
		return $this->belongsTo('App\Measure','measure_id')->select(DB::raw('id,
																			 latitude,
																			 longitude,
																			 IF(TIMESTAMPDIFF(MINUTE,measured_at,"'.Carbon::now().'") < '.$value.',"OK","NOK") as sync'));       
		//whereRaw('timestampdiff(MINUTE,measured_at,"'.Carbon::now().'") < '.$value);
	}
	
	public function lastactivemeasure()
	{
		$value = 1;
		$config = Config::where('param','CONFIG.LAST_ACTIVITY')->first();
		if($config != null) $value = $config->value;
		
				
		return $this->belongsTo('App\Measure','measure_id')->select(DB::raw('id,
																			 latitude,
																			 longitude,
																			 "OK" as sync'))->whereRaw('timestampdiff(MINUTE,measured_at,"'.Carbon::now().'") < '.$value);
	}
	
	public function user()
	{
		return $this->belongsTo('App\User','user_id');
	}
	
	public function activepaths()
	{
		return $this->hasMany('App\Path','user_id','user_id')->where('path_status_id',3);
	}
	
}
