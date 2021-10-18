<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Enterprise extends Model
{
    protected $table = "enterprises";
    protected $fillable = ["name"];

    public function users()
    {
        return $this->hasMany('App\User');
    }
}
