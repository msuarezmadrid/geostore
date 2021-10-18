<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PriceQuoteController extends Controller
{
    //
    public function index() {
        return view('price_quote.index');
    }
}
