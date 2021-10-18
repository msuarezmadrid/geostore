<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DteForwardController extends Controller
{
    //
    public function index()
    {
         return view('xml.forward');
    }
}
