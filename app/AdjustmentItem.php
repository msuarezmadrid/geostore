<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdjustmentItem extends Model
{
    protected $fillable = ["item_id", "quantity", "unit_of_measure_id", "unitary_price"];
}
