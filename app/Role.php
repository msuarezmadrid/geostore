<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
	use SoftDeletes;
    protected $table = "roles";
    protected $fillable = ["name"];
    protected $dates = ["deleted_at"];
    public function permissions(){
    	//return $this->hasMany('App\RolePermission');
    	return $this->belongsToMany('App\Permission', 'role_permissions', 'role_id', 'permission_id');
    }
}
