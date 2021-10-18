<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transact extends Model
{
    protected $fillable = [
       'description', 
       'object_id',
       'object_type',
   ];
}
