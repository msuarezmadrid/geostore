<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $table = "role_permissions";

    public function permissions(){
    	return $this->hasMany('App\Permission');
    }
}
