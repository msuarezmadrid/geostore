<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
	 protected $fillable = [
	 	'name',
    	'description', 
    	'object_id',
    	'object_type',
    	'type'
	];
    protected $hidden = [
        'route',
    ];
}