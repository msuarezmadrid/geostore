<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WorkStationController extends Controller
{
    public function workStations()
    {
        return view('work_stations.index');
    }
}
