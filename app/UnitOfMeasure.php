<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnitOfMeasure extends Model
{
    protected $fillable = ["name", "longname", "date", "code", "movement_status_id"];
}
