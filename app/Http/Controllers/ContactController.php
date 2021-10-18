<?php

namespace App\Http\Controllers;
use App\Client;
use Log;

use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function clients()
    {
        return view('contacts.index_clients');
    }
    public function suppliers()
    {
        return view('contacts.index_suppliers');
    }

    public function comunes()
    {
        return view('contacts.index_comunes');
    }

    public function showClients($id)
    {
        $client = Client::find($id);
        $data = ['client' => $client];
        return view('contacts.show_clients', $data)->with('id', $id);
    }
}
