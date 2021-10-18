<?php

namespace App\Http\Controllers;

use App\Client;
use App\Conciliation;
use App\ConcilliationDoc;
use App\ConcilliationDocFile;
use Log;
use Illuminate\Http\Request;

class ConcillationController extends Controller
{
    public function docs() {
        return view('concilliation.concilliation_doc');
    }
    public function index() {
        return view('concilliation.index');
    }
    public function add() {
        return view('concilliation.add');
    }
    public function showDoc($id) {
        $concilliationDocs = ConcilliationDoc::find($id);
        $data = ['concilliation' => $concilliationDocs];
        return view('concilliation.show_concilliation_doc', $data)->with('id', $id);
        // return view('concilliation.show_concilliation_doc');
    }
    public function show($id) {
        $conciliation = Conciliation::find($id);
        $client       = Client::find($conciliation->client_id);
        $documents    = $conciliation->getDocuments(1);
        Log::info($documents);
        $cdocuments   = null; //$conciliation->getDocuments(2);
        return view ('concilliation.show', [
            'client'     => $client,
            'documents'  => $documents,
        ])->with('id', $id); 
    }
}
