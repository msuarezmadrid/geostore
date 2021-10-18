<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    protected $fillable = ["code", "date", "work_station_id", "location_id", "movement_status_id"];
}
