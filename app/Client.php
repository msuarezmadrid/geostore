<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ["name", "rut", "rut_dv", "address", "email, phone"];
}
