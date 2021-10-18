<?php

namespace App\Libraries;

use Log;
use GuzzleHttp\Client;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Libraries\Utils;
use App\Config;

class Afe {
    
    const TICKET     = 39;
    const INVOICE    = 33; 
    const CREDITNOTE = 61;
    const WAYBILL    = 52;
    const IVA        = 0.19;
    
    public function checkClient($rut) {
        $response = [
            'success' => true
        ];
        
        $client = new Client();
        $url    = config('afe.url').'/client/checkClient?api_token='.config('afe.afe_token');
        $request = $client->post($url, ['form_params' => [
            'rut_client'  => $rut,
            'rut_user'    => config('afe.user_rut'),
            'rut_company' => config('afe.company_rut') 
        ]]);
        if ($request->getStatusCode() != 200) {
            $response['success'] = false;
        } 
        return $response;
    }
    public function makeTicket($params) {
        $response = [
            'success' => true
        ];
        $utils      = new Utils();
        $client = new Client();
        $url = config('afe.url')."/makeTicket/saveRest?api_token=".config('afe.afe_token');
        $data['RUTRecep']    = $params['rutRecep'];
        $data['razon_social'] = "NO INFORMADO";
        $data['address']    = "NO INFORMADO";
        $data['industries'] = "NO INFORMADO";
        $data['comune']   = "NO INFORMADO";
        $data['TipoDTE']     = self::TICKET;

        if(config('afe.use_params')) {
            if(config('afe.company_industries') !== '') {
                $data['GiroEmis'] = config('afe.company_industries');
            }
            if(config('afe.company_address') !== '') {
                $data['DirOrigen'] = config('afe.company_address');
            }
        }

        $data['IndServicio'] = 3;
        $data['FchEmis'] = $utils->UTCToLocal(date('Y-m-d'),'Y-m-d');  
        $data['rut_company'] = config('afe.company_rut');
        $data['rut_user'] = config('afe.user_rut');
        $data['QtyItem'] = [] ;
        $data['NmbItem'] = [];
        $data['PrcItem'] = [];
        $data['DescuentoPct'] = [];
        $data['exempItems'] = [];
        foreach ($params['details'] as $detail) {
            $data['QtyItem'][]      = $detail['qty'];
            $data['NmbItem'][]      = $detail['itemName'];
            $data['PrcItem'][]      = $detail['price'];
            $data['DescuentoPct'][] = $detail['discount'];
            $data['exempItems'][]   = false;
        }

        $data['letter'] = $params['letter'];

        $request = $client->post($url, ['form_params' => [
            'form' => json_encode($data)
        ]]);
        if ($request->getStatusCode() != 200) {
            $response['success'] = false;
            $response['msg']     = '';
        } else {
            Log::info(($request->getBody()));
            $res = json_decode($request->getBody());
            if ($res->success == 'warning') {
                $response['success'] = false;
                $code  = '';
                $find1 = strpos($res->msg, 'no registrado');
                if ($find1) {
                    $code  = 1;
                }
                $find2 = strpos($res->msg, 'folios disponibles');
                if ($find2) {
                    $code  = 2;
                }
                $response['msg']     = $code;
            }
            if ($res->success == 'success') {
                $response['folio'] = $res->folio;
                $response['ruta']  = $res->ruta;
            }
        }  
        return $response;
    }
    public function makeInvoice($params) {
        $response = [
            'success' => true
        ];
        $utils = new Utils();        
        $client = new Client();
        $url = config('afe.url')."/makeSale/save?api_token=".config('afe.afe_token');
        $data['rut_company']  = config('afe.company_rut');
        $data['rut_user']     = config('afe.user_rut');
        $data['RUTRecep']     = $params['rutRecep'];
        $data['razon_social'] = $params['razon_social'];
        $data['address']      = $params['address'];
        $data['industries']   = $params['industries'];
        $data['comune']       = $params['comune'];
        $data['TipoDTE']      = self::INVOICE;
        
        if(config('afe.use_params')) {
            if(config('afe.company_industries') !== '') {
                $data['GiroEmis'] = config('afe.company_industries');
            }
            if(config('afe.company_address') !== '') {
                $data['DirOrigen'] = config('afe.company_address');
            }
            if(config('afe.company_acteco') !== '') {
                $data['Acteco'] = config('afe.company_acteco');
            }
        }

        $data['TipoDespacho'] = 1;
        $data['FmaPago']      = $params['payMode'];
        $data['FchEmis']      = $utils->UTCToLocal(date('Y-m-d'),'Y-m-d');  
        $data['Daystopay']    = null;
        $data['QtyItem']      = [];
        $data['NmbItem']      = [];
        $data['exempItems']   = [];
        $data['UnmdItem']     = [];
        $data['TpoCodigo']    = [];
        $data['VlrCodigo']    = [];
        $data['observations'] = $params['observations'];
        $data['CdgItem']      = [];
        $data['FchVenc']      = $params['FhcVenc'] != "false" ? $utils->UTCToLocal($params['FhcVenc'],'Y-m-d') : null;
        if (count($params['FolioRef']) > 0){
            $data['FolioRef'] = $params['FolioRef'];
            $data['RazonRef'] = $params['RazonRef'];
            $data['TpoDocRef'] = $params['TpoDocRef'];
            $data['FchRef'] = $params['FchRef'];
        }
        $net_total = 0;

        foreach ($params['details'] as $detail) {
            $data['QtyItem'][]      = $detail['qty'];
            $data['NmbItem'][]      = $detail['itemName'];
            $data['PrcItem'][]      = $detail['pricei'];
            $data['DescuentoPct'][] = $detail['discount'];
            $data['exempItems'][]   = false;
            $data['UnmdItem'][]     = null;
            $net_total += round($detail['qty']*$detail['pricei']);
            $net_total -= round(($detail['qty']*$detail['pricei'])* ( ($detail['discount'])/100));

        }
        $iva = round($net_total*self::IVA);
        $data['MntNeto']  = $net_total;
        $data['MntExe']   = 0;
        $data['IVA']      = $iva;
        $data['MntTotal'] = $net_total + $iva;

        $data['letter'] = $params['letter'];
        $data['cedible'] = Config::where('param', 'CEDIBLE')->first()->value;

        $request = $client->post($url, ['form_params' => [
            'form' => json_encode($data)
        ]]);
        if ($request->getStatusCode() != 200) {
            $response['success'] = false;
            $response['msg']     = '';
        } else {
            Log::info($request->getBody());
            $res = json_decode($request->getBody());
            if ($res->success == 'warning') {
                $response['success'] = false;
                $code  = '';
                $find1 = strpos($res->msg, 'no registrado');
                if ($find1) {
                    $code  = 1;
                }
                $find2 = strpos($res->msg, 'disponibles');
                if ($find2 != 0) {
                    $code  = 2;
                }
                $response['msg']     = $code;
            }
            if ($res->success == 'success') {
                $response['folio'] = $res->folio;
                $response['ruta']  = $res->ruta;
            }
        } 
        return $response;
    }

    public function makeCreditNote($params) {
        $response = [
            'success' => true
        ];
        $utils = new Utils();
        $client = new Client();
        $url = config('afe.url')."/makeNotes/save?api_token=".config('afe.afe_token');
        $data['rut_company']  = config('afe.company_rut');
        $data['rut_user']     = config('afe.user_rut');
        $data['RUTRecep']     = $params['rutRecep'];
        if (array_key_exists('razon_social', $params)) {
            $data['razon_social'] = $params['razon_social'];
        }
        if (array_key_exists('address', $params)) {
            $data['address'] = $params['address'];
        }
        if (array_key_exists('industries', $params)) {
            $data['industries'] = $params['industries'];
        }
        if (array_key_exists('comune', $params)) {
            $data['comune'] = $params['comune'];
        }

        $data['CodRef']       = $params['CodRef'];
        $data['TpoDocRef']    = $params['TpoDocRef'];
        $data['FolioRef']     = $params['FolioRef'];
        $data['RazonRef']     = $params['RazonRef'];
        $data['FchRef']       = $params['FchRef'];
        $data['TipoDTE']      = self::CREDITNOTE;

        if(config('afe.use_params')) {
            if(config('afe.company_industries') !== '') {
                $data['GiroEmis'] = config('afe.company_industries');
            }
            if(config('afe.company_address') !== '') {
                $data['DirOrigen'] = config('afe.company_address');
            }
        }

        $data['TipoDespacho'] = 1;
        $data['FmaPago']      = 1;
        $data['FchEmis'] = $utils->UTCToLocal(date('Y-m-d'),'Y-m-d');  
        
        $data['Daystopay']    = null;
        $data['QtyItem']      = [];
        $data['NmbItem']      = [];
        $data['exempItems']   = [];
        $data['UnmdItem']     = [];
        $data['TpoCodigo']    = [];
        $data['VlrCodigo']    = [];
        $data['observations'] = $params['observations'];
        $data['CdgItem']      = [];
        $net_total = 0;
        foreach ($params['details'] as $detail) {
            $data['QtyItem'][]      = $detail['qty'];
            $data['NmbItem'][]      = $detail['itemName'];
            $data['DescuentoPct'][] = $detail['discount'];
            $data['exempItems'][]   = false;
            $data['UnmdItem'][]     = null;
            $data['PrcItem'][]      = $detail['price'];
            $net_total += round($detail['qty']*$detail['price']);
            $net_total -= round(($detail['qty']*$detail['price'])* ($detail['discount']/100));
            
        }
        if ($data['TpoDocRef'][0] == self::TICKET) {
            $net_total  = round($net_total/(1+self::IVA)); 
        }
        if (array_key_exists('NmbItem', $params)) {
            $data['NmbItem'] = $params['NmbItem'];
        }
        

        $iva = round($net_total*self::IVA);
        $data['MntNeto']  = $net_total;
        $data['MntExe']   = 0;
        $data['IVA']      = $iva;
        $data['MntTotal'] = $net_total + $iva;

        $data['letter'] = 0;

        $request = $client->post($url, ['form_params' => [
            'form' => json_encode($data)
        ]]);
        Log::info($data);
        if ($request->getStatusCode() != 200) {
            $response['success'] = false;
            $response['msg']     = '';
        } else {
            Log::info($request->getBody());
            $res = json_decode($request->getBody());
            if ($res->success == 'warning') {
                $response['success'] = false;
                $code  = '';
                $find1 = strpos($res->msg, 'no registrado');
                if ($find1) {
                    $code  = 1;
                }
                $find2 = strpos($res->msg, 'folios disponibles');
                if ($find2) {
                    $code  = 2;
                }
                $find2 = strpos($res->msg, 'si no cuenta con los datos del comprador');
                if ($find2) {
                    $code  = 3;
                }
                $response['msg']     = $code;
            }
            if ($res->success == 'success') {
                $response['folio'] = $res->folio;
                $response['ruta']  = $res->ruta;
                $response['net']   = $net_total;
                $response['tax']   = $iva;
                $response['total'] = ($net_total+$iva);
            }
        }
        Log::info($response);
        return $response;

    }

    public function makeWaybill($params) {
        $response = [
            'success' => true
        ];
        $utils = new Utils();
        $client = new Client();
        $url = config('afe.url')."/makeWaybill/save?api_token=".config('afe.afe_token');
        $data['rut_company']  = config('afe.company_rut');
        $data['rut_user']     = config('afe.user_rut');
        $data['RUTRecep']     = $params['rutRecep'];
        if (array_key_exists('razon_social', $params)) {
            $data['razon_social'] = $params['razon_social'];
        }
        if (array_key_exists('address', $params)) {
            $data['address'] = $params['address'];
        }
        if (array_key_exists('industries', $params)) {
            $data['industries'] = $params['industries'];
        }
        if (array_key_exists('comune', $params)) {
            $data['comune'] = $params['comune'];
        }

        $data['CodRef']       = $params['CodRef'];
        $data['TpoDocRef']    = $params['TpoDocRef'];
        $data['FolioRef']     = $params['FolioRef'];
        $data['RazonRef']     = $params['RazonRef'];
        $data['FchRef']       = $params['FchRef'];
        $data['TipoDTE']      = self::WAYBILL;
        
        if(config('afe.use_params')) {
            if(config('afe.company_industries') !== '') {
                $data['GiroEmis'] = config('afe.company_industries');
            }
            if(config('afe.company_address') !== '') {
                $data['DirOrigen'] = config('afe.company_address');
            }
        }

        $data['TipoDespacho'] = 1;
        $data['FmaPago']      = $params['payMode'];
        $data['FchEmis'] = $utils->UTCToLocal(date('Y-m-d'),'Y-m-d');  
        
        $data['Daystopay']    = null;
        $data['QtyItem']      = [];
        $data['NmbItem']      = [];
        $data['exempItems']   = [];
        $data['UnmdItem']     = [];
        $data['TpoCodigo']    = [];
        $data['VlrCodigo']    = [];
        $data['observations'] = $params['observations'];
        $data['CdgItem']      = [];
        $data['FchVenc']      = $params['FhcVenc'] != "false" ? $utils->UTCToLocal($params['FhcVenc'],'Y-m-d') : null;
        $net_total = 0;
        foreach ($params['details'] as $detail) {
            $data['QtyItem'][]      = $detail['qty'];
            $data['NmbItem'][]      = $detail['itemName'];
            $data['DescuentoPct'][] = $detail['discount'];
            $data['exempItems'][]   = false;
            $data['UnmdItem'][]     = null;
            $data['PrcItem'][]      = $detail['price'];
            $net_total += round($detail['qty']*$detail['price']);
            $net_total -= round(($detail['qty']*$detail['price'])* ($detail['discount']/100));
            
        }
        if ($data['TpoDocRef'][0] == self::TICKET) {
            $net_total  = round($net_total/(1+self::IVA)); 
        }
        if (array_key_exists('NmbItem', $params)) {
            $data['NmbItem'] = $params['NmbItem'];
        }
        

        $iva = round($net_total*self::IVA);
        $data['MntNeto']  = $net_total;
        $data['MntExe']   = 0;
        $data['IVA']      = $iva;
        $data['MntTotal'] = $net_total + $iva;
        $request = $client->post($url, ['form_params' => [
            'form' => json_encode($data)
        ]]);
        Log::info($data);
        if ($request->getStatusCode() != 200) {
            $response['success'] = false;
            $response['msg']     = '';
        } else {
            Log::info($request->getBody());
            $res = json_decode($request->getBody());
            if ($res->success == 'warning') {
                $response['success'] = false;
                $code  = '';
                $find1 = strpos($res->msg, 'no registrado');
                if ($find1) {
                    $code  = 1;
                }
                $find2 = strpos($res->msg, 'folios disponibles');
                if ($find2) {
                    $code  = 2;
                }
                $response['msg']     = $code;
            }
            if ($res->success == 'success') {
                $response['folio'] = $res->folio;
                $response['ruta']  = $res->ruta;
                $response['net']   = $net_total;
                $response['tax']   = $iva;
                $response['total'] = ($net_total+$iva);
            }
        }
        Log::info($response);
        return $response;

    }

    public function getClients() {
        $response = [
            'success' => true
        ];
        $client = new Client();
        $url  = config('afe.url')."/client/loadClientGrid/";
        $url .= config('afe.company_rut').'?start=0&limit=10000&sort=[{"property":"id_client","direction":"ASC"}]&api_token='.config('afe.afe_token');
        $request = $client->get($url);
        if ($request->getStatusCode() != 200) {
            $response['success'] = false;
        } else {
            $res = json_decode($request->getBody());
            $response['data'] = $res;
        }
        return $response;
    }

    public function getVoucher($params) {
        $response = [
            'success' => true
        ];

        $rutClient = $params['rut_user'];
        $docType = $params['type'];
        $folio = $params['folio'];

        $client = new Client();
        $url  = config('afe.url')."/download/dtePdf/?rut_company=".config('afe.company_rut')."&rut_user=".$rutClient."&type=".$docType."&folio=".$folio.'&api_token='.config('afe.afe_token');
        $request = $client->get($url);
        if ($request->getStatusCode() != 200) {
            $response['success'] = false;
        } else {
            $res = json_decode($request->getBody());
            $response['data'] = $res;
        }
        return $response;
    }

    public function getCreditNotes($params) {
        $response = [
            'success' => true
        ];

        $folio = $params['folio'];

        $client = new Client();
        $url  = config('afe.url')."/download/dtePdf/?rut_company=".config('afe.company_rut')."&rut_user=test&type=61&folio=".$folio.'&api_token='.config('afe.afe_token');
        $request = $client->get($url);
        if ($request->getStatusCode() != 200) {
            $response['success'] = false;
        } else {
            $res = json_decode($request->getBody());
            $response['data'] = $res;
        }
        return $response;
    }

    public function forwardDocument($params) {
        $response = [
            'success' => true
        ];
        $client = new Client();
        $folio = $params['folio'];
        $docType = $params['doctype'];
        $emails = $params['emails'];
        $cliente = $params['cliente'];
        $url = config('afe.url')."/dte/forward";
        $data = [
            'folio'       => $folio,
            'type'        => $docType,
            'emails'      => $emails,
            'rut_client'  => $cliente,
            'rut_user'    => config('afe.user_rut'),
            'rut_company' => config('afe.company_rut') 
        ];
        $request = $client->post($url, 
        [
            'form_params' => [
                'form' => json_encode($data)
            ]
        ]);
        if($request->getStatusCode() != 200) {
            $response['success'] = false;
        } else {
            $res = json_decode($request->getBody());
            if($res == null) {
                $response['success'] = false;
            } else {
                $response['data'] = $res;
            }
        }
        return $response;
    }

}
