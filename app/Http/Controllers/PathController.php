<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PathController extends Controller
{
    public function index()
    {
        return view('paths.index');
    }
	
	public function show($id)
	{
		return view('paths.show')->with('id', $id);
	}
}