<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleOrderItem extends Model
{
    protected $fillable = ["item_id", "sale_order_id", "quantity", "unit_of_measure_id", "price"];
}
