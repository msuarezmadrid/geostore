<?php

namespace App\Http\Controllers;

use App\Config;
use Illuminate\Http\Request;

class ConfigsController extends Controller
{
    public function defaultvalues()
    {        
        $config_app = Config::where('param', 'APP')->first();
        if($config_app) {
            $config_app = $config_app->value;
        } else {
            $config_app = 0;
        }

    	return view('configs.defaultvalues',
            [
                'app' => $config_app
            ]
        );

    }
}
