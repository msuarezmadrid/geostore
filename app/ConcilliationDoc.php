<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConcilliationDoc extends Model
{
    protected $fillable = ["amount", "client_id", "payment_method","doc_number", "status", "description"];

    public function getNoConciliatedDocuments($client) {
        return ConcilliationDoc::where('client_id', $client)
                               ->where('status', 'No Conciliado')
                               ->get();
    }

    public function getConciliatedDocuments($client, $id) {
        
        return ConcilliationDoc::where('client_id', $client)
                               ->join('conciliation_details', 'concilliation_docs.id', '=', 'conciliation_details.doc_id')
                               ->where('conciliation_details.conciliation_id', $id)
                               ->where('status', 'Conciliado')
                               ->select([
                                'concilliation_docs.id',
                                'concilliation_docs.amount',
                                'concilliation_docs.payment_method',
                                'concilliation_docs.description',
                                'concilliation_docs.status',
                                'concilliation_docs.created_at'
                                ])
                                ->get();
    }

}
