<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    
	protected $fillable = ['status','responsible','phone'];
	
	public function lastuser()
	{
		return $this->hasMany('App\DeviceUserApp')->orderBy('created_by','desc');
	}
}
