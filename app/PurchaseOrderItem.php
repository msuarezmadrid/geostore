<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    protected $fillable = ["item_id", "purchase_order_id", "quantity", "unit_of_measure_id", "price"];
}
