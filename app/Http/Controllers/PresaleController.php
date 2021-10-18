<?php

namespace App\Http\Controllers;

use App\Item;
use App\Location;
use Illuminate\Http\Request;

class PresaleController extends Controller
{
    public function index() {
        $defaultWithdraw = Location::first();
        if($defaultWithdraw != null) {
            $defaultWithdraw = $defaultWithdraw->code != null ? $defaultWithdraw->code : 'TS-01';
        } else {
            $defaultWithdraw = 'TS-01';
        }
        return view('presale.index', [
            'defaultWithdraw' => $defaultWithdraw
        ]);
    }
}
