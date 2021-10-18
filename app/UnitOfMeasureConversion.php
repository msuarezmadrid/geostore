<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnitOfMeasureConversion extends Model
{
    protected $fillable = ["uom_from_id", "uom_to_id", "factor"];
}
