<?php

namespace App\Http\Controllers;

use App\Config;
use Illuminate\Http\Request;

class POSController extends Controller
{
    public function index()
    {
        $tipoCaja = Config::where('param', 'TIPO_CAJA')->first();
        if($tipoCaja ==  null) {
            $tipoCaja = new Config();
            $tipoCaja->value = 0;
        }
    	return view('pointofsale.index', [
            'tipoCaja' => $tipoCaja
        ]);
    }
}
