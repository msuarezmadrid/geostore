<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkOrderItem extends Model
{
    protected $fillable = [ "work_order_id", "item_id", "quantity", "unit_of_measure_id"];

}
