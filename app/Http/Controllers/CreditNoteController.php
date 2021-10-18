<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CreditNoteController extends Controller
{
    public function index() {
        return view('creditnotes.index');
    }
    public function add() {
        return view('creditnotes.add');
    }
}
