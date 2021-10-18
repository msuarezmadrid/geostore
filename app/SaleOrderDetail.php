<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SaleOrderDetail extends Model
{
    protected $fillable = ["total_discount", "total_net", "total_tax", "total", "sale_order_id"];
}
