<?php

namespace App;


use App\Sale;
use App\CreditNote;
use App\Client;
use App\InvoiceReference;
use Carbon\Carbon;
use App\Libraries\Utils;
use Illuminate\Database\Eloquent\Model;


class PriceType extends Model
{
    public function getPdfInfo() {

        $utils                  = new Utils();

        $sale                   = Sale::whereIn('id', '1')
                                        ->orderBy('client_id')
                                        ->get();

        $detail['suma_net']                 = 0;
        $detail['suma_exento']              = 0;
        $detail['suma_impto']               = 0;
        $detail['suma_tax']                 = 0;
        $detail['suma_total']               = 0;
        
        $detail['creditNote_net']                 = 0;
        $detail['creditNote_tax']                 = 0;
        $detail['creditNote_total']               = 0;

        $detail['date']                     = $utils->UTCToLocal(date("Y-m-d"));
      

        

        if ($start_date == ""){
            $detail['start_date']           = $utils->UTCToLocal(date("Y-m-d"));            
        }else{
            $detail['start_date']           = $utils->UTCToLocal($start_date,'d-m-Y','Y-m-d H:i',$timezone);
        }
        if ($end_date == ""){
            $detail['end_date']              = $utils->UTCToLocal(date("Y-m-d"));             
        }else{
            $detail['end_date']              = $utils->UTCToLocal($end_date,'d-m-Y','Y-m-d H:i',$timezone);
        }
         

        foreach ($sale as  $sal) {

            $detail['ids'][]                    = $sal->id;
            $invoiceReferences                  = InvoiceReference::where('invoice_references.sale_id', '=', $sal->id)->get();
            $clientData                         = Client::where('clients.id', '=', $sal->client_id)->get();
            $creditNotesData                    = CreditNote::where('credit_notes.sale_id', '=', $sal->id)->get();

            foreach ($clientData as $client){
                $detail[$sal->id]['clients'][]=[
                    'client_id'                 => Client::find($client->id)->id,
                    'client_name'               => Client::find($client->id)->name,
                    'client_rut'                => Client::find($client->id)->rut,
                    'client_rut_dv'             => Client::find($client->id)->rut_dv,
                    
                ];
            }
            foreach ($invoiceReferences as $ref) {
                $detail[$sal->id]['references'][] = [
                    'folio_reference'           => InvoiceReference::find($ref->id)->folio,
                    'reason_reference'          => InvoiceReference::find($ref->id)->reason_reference,
                    'doc_type_reference'        => InvoiceReference::find($ref->id)->doc_type,
                    'date_reference'            => $utils->UTCToLocal(InvoiceReference::find($ref->id)->date)
                ];
            }
            foreach ($creditNotesData as $creditNote){
                $detail[$sal->id]['creditNotes'][]=[
                    'creditNote_id'              => CreditNote::find($creditNote->id)->id,
                    'creditNote_folio'           => CreditNote::find($creditNote->id)->folio,
                    'creditNote_type'            => CreditNote::find($creditNote->id)->type,
                    'creditNote_net'             => number_format(CreditNote::find($creditNote->id)->net),
                    'creditNote_tax'             => number_format(CreditNote::find($creditNote->id)->tax),
                    'creditNote_total'           => number_format(CreditNote::find($creditNote->id)->total),
                    'creditNote_observations'    => CreditNote::find($creditNote->id)->observations,
                    // 'creditNote_date'            => $utils->UTCToLocal(CreditNote::find($creditNote->id)->created_at)
                    
                ];
                $detail['creditNote_net']       += CreditNote::find($creditNote->id)->net;
                $detail['creditNote_tax']       += CreditNote::find($creditNote->id)->tax;
                $detail['creditNote_total']     += CreditNote::find($creditNote->id)->total;
            }
           
            
            $detail[$sal->id]['folio']          = $sal->folio !== null ? $sal->folio : 'NO EMITIDO EN SISTEMA';
            $detail[$sal->id]['date_sale']      = $utils->UTCToLocal($sal->date);
            if ($sal->type == 1){
                $detail[$sal->id]['type']       = 'Boleta';
            }else{
                $detail[$sal->id]['type']       = 'Factura';                
            }

            $detail[$sal->id]['exento']         = number_format(0, 0, ',', '.');
            $detail[$sal->id]['impts']          = number_format(0, 0, ',', '.');
            $detail[$sal->id]['net']            = number_format($sal->net, 0, ',', '.');
            $detail[$sal->id]['tax']            = number_format($sal->tax, 0, ',', '.');
            $detail[$sal->id]['total']          = number_format($sal->total, 0, ',', '.');

            

            $detail['suma_net']                 += $sal->net;
            $detail['suma_exento']              += 0;
            $detail['suma_impto']               += 0;
            $detail['suma_tax']                 += $sal->tax;
            $detail['suma_total']               += $sal->total;

           
            
            
            
        }
        $detail['suma_net']                 -= $detail['creditNote_net'];
        $detail['suma_tax']                 -= $detail['creditNote_tax'];
        $detail['suma_total']               -= $detail['creditNote_total'];

        $detail['suma_net']                 = number_format($detail['suma_net']  , 0, ',', '.');
        $detail['suma_exento']              = number_format($detail['suma_exento'] , 0, ',', '.');
        $detail['suma_impto']               = number_format($detail['suma_impto'] , 0, ',', '.');
        $detail['suma_tax']                 = number_format($detail['suma_tax'] , 0, ',', '.');
        $detail['suma_total']               = number_format($detail['suma_total'] , 0, ',', '.');


        $pdfInfo[] = $detail;
        
    
        return $pdfInfo;
    }
}
