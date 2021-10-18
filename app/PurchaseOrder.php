<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
 	protected $fillable = ["code", "date", "location_id", "supplier_id", "movement_status_id"];
}
