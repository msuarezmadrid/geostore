<?php

namespace App;

use Log;
use App\Sale;
use App\CreditNote;
use App\Client;
use App\InvoiceReference;
use Carbon\Carbon;
use App\Libraries\Utils;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Report extends Model
{
    public function getPdfInfo(array $ids, string $start_date, string $end_date, string $timezone, string $type) {

        $utils                  = new Utils();

        $sale                   = Sale::whereIn('id', $ids)
                                        ->orderByRaw("ABS($type) asc")
                                        ->select([
                                            '*',
                                            DB::raw('IFNULL(sales.folio, \'NO EMITIDO EN SISTEMA\') as folio_fallback')
                                        ])
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
                $values = Client::find($client->id);
                $detail[$sal->id]['clients'][]=[
                    'client_id'                 => $values->id,
                    'client_name'               => $values->name,
                    'client_rut'                => $values->rut,
                    'client_rut_dv'             => $values->rut_dv,
                    
                ];
            }
            foreach ($invoiceReferences as $ref) {
                $values = InvoiceReference::find($ref->id);
                $detail[$sal->id]['references'][] = [
                    'folio_reference'           => $values->folio,
                    'reason_reference'          => $values->reason_reference,
                    'doc_type_reference'        => $values->doc_type,
                    'date_reference'            => $utils->UTCToLocal($values->date)
                ];
            }
            foreach ($creditNotesData as $creditNote){
                $values = CreditNote::find($creditNote->id);

                $detail[$sal->id]['creditNotes'][]=[
                    'creditNote_id'              => $values->id,
                    'creditNote_folio'           => $values->folio,
                    'creditNote_type'            => $values->type,
                    'creditNote_net'             => number_format($values->net),
                    'creditNote_tax'             => number_format($values->tax),
                    'creditNote_total'           => number_format($values->total),
                    'creditNote_observations'    => $values->observations,
                    // 'creditNote_date'            => $utils->UTCToLocal(CreditNote::find($creditNote->id)->created_at)
                    
                ];
                $detail['creditNote_net']       += $values->net;
                $detail['creditNote_tax']       += $values->tax;
                $detail['creditNote_total']     += $values->total;
            }
           
            
            $detail[$sal->id]['folio']          = $sal->folio_fallback;
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