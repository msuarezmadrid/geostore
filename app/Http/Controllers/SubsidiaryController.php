<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubsidiaryController extends Controller
{
    public function index()
    {
        return view('subsidiaries.index');
    }
	
	public function show($id)
	{
		return view('subsidiaries.show')->with('id', $id);
	}
}