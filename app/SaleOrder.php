<?php

namespace App;

use DB;
use Log;
use App\Enum\TransactDetails;
use Illuminate\Database\Eloquent\Model;

class SaleOrder extends Model
{
	 protected $fillable = ["code", "date", "location_id", "client_id", "movement_status_id"];
	 
	 
}
