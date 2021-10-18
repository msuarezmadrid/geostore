<?php

namespace App\Http\Controllers\Api;

use Log;
use Validator;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Location;
use App\SaleOrder;
use App\PurchaseOrder;
use App\Transfer;
use App\Adjustment;
use App\MovementHistorical;
class DashboardController extends ApiController
{
    
    public function get(Request $request)
    {
        $type = $request->get('type');

        switch ($type) {
            case 'total_orders_by_location':
                $datas = new \stdClass();

                $id = $request->get("id");
                $user = $request->user();
                $enterprise_id  = $user->enterprise->id;

                $pending = 0;
                $approved = 0;

                if($id == "0"){
                    $pendSales = SaleOrder::where('enterprise_id', $enterprise_id)->where('movement_status_id', 2)->count();
                    $pendPurchases = PurchaseOrder::where('enterprise_id', $enterprise_id)->where('movement_status_id', 2)->count();
                    $pendTransfers = Transfer::where('enterprise_id', $enterprise_id)->where('movement_status_id', 2)->count();
                    $pendAdjustments = Adjustment::where('enterprise_id', $enterprise_id)->where('movement_status_id', 2)->count();

                    $approvedSales = SaleOrder::where('enterprise_id', $enterprise_id)->where('movement_status_id', 3)->count();
                    $approvedPurchases = PurchaseOrder::where('enterprise_id', $enterprise_id)->where('movement_status_id', 3)->count();
                    $approvedTransfers = Transfer::where('enterprise_id', $enterprise_id)->where('movement_status_id', 3)->count();
                    $approvedAdjustments = Adjustment::where('enterprise_id', $enterprise_id)->where('movement_status_id', 3)->count();  
                }else{

                    $pendSales = SaleOrder::where('enterprise_id', $enterprise_id)->where('movement_status_id', 2)->where('location_id', $id)->count();
                    $pendPurchases = PurchaseOrder::where('enterprise_id', $enterprise_id)->where('movement_status_id', 2)->where('location_id', $id)->count();
                    $pendTransfers = Transfer::where('enterprise_id', $enterprise_id)->where('movement_status_id', 2)->where('location_from_id', $id)->count();
                    $pendAdjustments = Adjustment::where('enterprise_id', $enterprise_id)->where('movement_status_id', 2)->where('location_id', $id)->count();

                    $approvedSales = SaleOrder::where('enterprise_id', $enterprise_id)->where('movement_status_id', 3)->where('location_id', $id)->count();
                    $approvedPurchases = PurchaseOrder::where('enterprise_id', $enterprise_id)->where('movement_status_id', 3)->where('location_id', $id)->count();
                    $approvedTransfers = Transfer::where('enterprise_id', $enterprise_id)->where('movement_status_id', 3)->where('location_from_id', $id)->count();
                    $approvedAdjustments = Adjustment::where('enterprise_id', $enterprise_id)->where('movement_status_id', 3)->where('location_id', $id)->count();  
                }

                $pending += $pendSales;
                $pending += $pendPurchases;
                $pending += $pendTransfers;
                $pending += $pendAdjustments;

                $approved += $approvedSales;
                $approved += $approvedPurchases;
                $approved += $approvedTransfers;
                $approved += $approvedAdjustments;
                $datas->id = $request->get('id');
                $datas->approved = $approved;
                $datas->pending = $pending;
                return $this->json($request, $datas, 200);

                break;
            

            case 'total_orders_by_type':

                $datas = new \stdClass();

                $id = $request->get("id");
                $user = $request->user();
                $enterprise_id  = $user->enterprise->id;

                $pending = 0;
                $approved = 0;

                $pendSales = 0;
                $pendPurchases = 0;
                $pendTransfers = 0;
                $pendAdjustments = 0;
                $approvedSales = 0;
                $approvedPurchases = 0;
                $approvedTransfers = 0;
                $approvedAdjustments = 0;

                if($id == "all"){
                    $pendSales = SaleOrder::where('enterprise_id', $enterprise_id)->where('movement_status_id', 2)->count();
                    $pendPurchases = PurchaseOrder::where('enterprise_id', $enterprise_id)->where('movement_status_id', 2)->count();
                    $pendTransfers = Transfer::where('enterprise_id', $enterprise_id)->where('movement_status_id', 2)->count();
                    $pendAdjustments = Adjustment::where('enterprise_id', $enterprise_id)->where('movement_status_id', 2)->count();

                    $approvedSales = SaleOrder::where('enterprise_id', $enterprise_id)->where('movement_status_id', 3)->count();
                    $approvedPurchases = PurchaseOrder::where('enterprise_id', $enterprise_id)->where('movement_status_id', 3)->count();
                    $approvedTransfers = Transfer::where('enterprise_id', $enterprise_id)->where('movement_status_id', 3)->count();
                    $approvedAdjustments = Adjustment::where('enterprise_id', $enterprise_id)->where('movement_status_id', 3)->count();  
                }else if($id == "sales"){
                    $pendSales = SaleOrder::where('enterprise_id', $enterprise_id)->where('movement_status_id', 2)->count();
                    $approvedSales = SaleOrder::where('enterprise_id', $enterprise_id)->where('movement_status_id', 3)->count();
                    
                }else if($id == "purchases"){

                    $pendPurchases = PurchaseOrder::where('enterprise_id', $enterprise_id)->where('movement_status_id', 2)->count();
                    $approvedPurchases = PurchaseOrder::where('enterprise_id', $enterprise_id)->where('movement_status_id', 3)->count();
                   
                }else if($id == "transfers"){

                    $pendTransfers = Transfer::where('enterprise_id', $enterprise_id)->where('movement_status_id', 2)->count();
                    $approvedTransfers = Transfer::where('enterprise_id', $enterprise_id)->where('movement_status_id', 3)->count();
                   
                }else if($id == "adjustments"){

                    $pendAdjustments = Adjustment::where('enterprise_id', $enterprise_id)->where('movement_status_id', 2)->count();
                    $approvedAdjustments = Adjustment::where('enterprise_id', $enterprise_id)->where('movement_status_id', 3)->count();  
                }

                $pending += $pendSales;
                $pending += $pendPurchases;
                $pending += $pendTransfers;
                $pending += $pendAdjustments;

                $approved += $approvedSales;
                $approved += $approvedPurchases;
                $approved += $approvedTransfers;
                $approved += $approvedAdjustments;
                $datas->id = $request->get('id');
                $datas->approved = $approved;
                $datas->pending = $pending;
                return $this->json($request, $datas, 200);
                break;
            
            case 'last_orders_by_type':

                $datas = new \stdClass();
                $id = $request->get("id");
                $user = $request->user();
                $enterprise_id  = $user->enterprise->id;
                
                if($id == "all"){

                    $historical_approved = MovementHistorical::where('movement_status_id', 3)->where('enterprise_id', $enterprise_id)->orderBy('created_at', 'desc')->limit(5)->get();
                    
                    $notPublished = [];
                    $approveds = MovementHistorical::where('movement_status_id', 3)->where('enterprise_id', $enterprise_id)->where('movement_type', 'sales')->orderBy('created_at', 'desc')->pluck('order_id');
                    
                    $h_pending = MovementHistorical::where('movement_status_id', 2)->where('enterprise_id', $enterprise_id)->where('movement_type', 'sales')->orderBy('created_at', 'desc')->whereNotIn('order_id', $approveds)->limit(5)->get();

                    foreach ($h_pending as $key => $value) {
                        array_push($notPublished, $value->id);
                    }

                    $approveds = MovementHistorical::where('movement_status_id', 3)->where('enterprise_id', $enterprise_id)->where('movement_type', 'purchases')->orderBy('created_at', 'desc')->pluck('order_id');
                    
                    $h_pending = MovementHistorical::where('movement_status_id', 2)->where('enterprise_id', $enterprise_id)->where('movement_type', 'purchases')->orderBy('created_at', 'desc')->whereNotIn('order_id', $approveds)->limit(5)->get();

                    foreach ($h_pending as $key => $value) {
                        array_push($notPublished, $value->id);
                    }

                    $approveds = MovementHistorical::where('movement_status_id', 3)->where('enterprise_id', $enterprise_id)->where('movement_type', 'transfers')->orderBy('created_at', 'desc')->pluck('order_id');
                    
                    $h_pending = MovementHistorical::where('movement_status_id', 2)->where('enterprise_id', $enterprise_id)->where('movement_type', 'transfers')->orderBy('created_at', 'desc')->whereNotIn('order_id', $approveds)->limit(5)->get();

                    foreach ($h_pending as $key => $value) {
                        array_push($notPublished, $value->id);
                    }

                    $approveds = MovementHistorical::where('movement_status_id', 3)->where('enterprise_id', $enterprise_id)->where('movement_type', 'transfers')->orderBy('created_at', 'desc')->pluck('order_id');
                    
                    $h_pending = MovementHistorical::where('movement_status_id', 2)->where('enterprise_id', $enterprise_id)->where('movement_type', 'transfers')->orderBy('created_at', 'desc')->whereNotIn('order_id', $approveds)->limit(5)->get();

                    foreach ($h_pending as $key => $value) {
                        array_push($notPublished, $value->id);
                    }

                    //return $this->json($request, $notPublished, 200);

                    $historical_pending = MovementHistorical::whereIn('id', $notPublished)->get(); 


                    $histApproved = [];
                    $histPending = [];

                   
                }else{
                    $historical_approved = MovementHistorical::where('movement_status_id', 3)->where('movement_type', $id)->where('enterprise_id', $enterprise_id)->orderBy('created_at', 'desc')->limit(5)->get();
                    $approveds = MovementHistorical::where('movement_status_id', 3)->where('movement_type', $id)->where('enterprise_id', $enterprise_id)->orderBy('created_at', 'desc')->pluck('order_id');
                    $histApproved = [];

                    $historical_pending = MovementHistorical::where('movement_status_id', 2)->where('movement_type', $id)->whereNotIn('order_id', $approveds)->where('enterprise_id', $enterprise_id)->orderBy('created_at', 'desc')->limit(5)->get();
                    $histPending = [];
                }
                 foreach ($historical_pending as $key => $value) {
                        $order = [];
                        
                        if($value->movement_type == "sales"){
                            $order = SaleOrder::find($value->order_id);
                        } elseif($value->movement_type == "purchases"){
                            $order = PurchaseOrder::find($value->order_id);
                        } elseif($value->movement_type == "transfers"){
                            $order = Transfer::find($value->order_id);
                        } elseif($value->movement_type == "adjustments"){
                            $order = Adjustment::find($value->order_id);
                        }

                        if($value->created_at->day<10){
                            $date_day =  "0".$value->created_at->day;
                        }else{
                            $date_day =  $value->created_at->day;
                        }

                        if($value->created_at->month<10){
                            $date_month =  "0".$value->created_at->month;
                        }else{
                            $date_month =  $value->created_at->month;
                        }

                        $date = $date_day."/".$date_month."/".$value->created_at->year;

                       
                        $pend = new \stdClass();
                        $pend->date = $date;
                        $pend->order_id = $value->order_id;
                        $pend->movement_status_id = $value->movement_status_id;
                        $pend->movement_type = $value->movement_type;
                        $pend->order_code = $order->code;
                        array_push($histPending, $pend);
                    }
                    
                    foreach ($historical_approved as $key => $value) {
                        
                        
                        if($value->movement_type == "sales"){
                            $order = SaleOrder::find($value->order_id);
                        } elseif($value->movement_type == "purchases"){
                            $order = PurchaseOrder::find($value->order_id);
                        } elseif($value->movement_type == "transfers"){
                            $order = Transfer::find($value->order_id);
                        } elseif($value->movement_type == "adjustments"){
                            $order = Adjustment::find($value->order_id);
                        }

                        if($value->created_at->day<10){
                            $date_day =  "0".$value->created_at->day;
                        }else{
                            $date_day =  $value->created_at->day;
                        }

                        if($value->created_at->month<10){
                            $date_month =  "0".$value->created_at->month;
                        }else{
                            $date_month =  $value->created_at->month;
                        }

                        $date = $date_day."/".$date_month."/".$value->created_at->year;

                        
                        $appr = new \stdClass();
                        $appr->date = $date;
                        $appr->order_id = $value->order_id;
                        $appr->movement_status_id = $value->movement_status_id;
                        $appr->movement_type = $value->movement_type;
                        $appr->order_code = $order->code;

                        array_push($histApproved, $appr);
                    }
                    $datas->id = $request->get('id');
                    $datas->pending = $histPending;
                    $datas->approved = $histApproved;
                    return $this->json($request, $datas, 200);
            break;

            case 'last_orders_by_location':

                $datas = new \stdClass();
                $id = $request->get("id");
                $user = $request->user();
                $enterprise_id  = $user->enterprise->id;
                
                if($id == "0"){
                    

                    

                    $historical_approved = MovementHistorical::where('movement_status_id', 3)->where('enterprise_id', $enterprise_id)->orderBy('created_at', 'desc')->limit(5)->get();
                    $notPublished = [];
                    $approveds = MovementHistorical::where('movement_status_id', 3)->where('enterprise_id', $enterprise_id)->orderBy('created_at', 'desc')->where('movement_type', 'sales')->pluck('order_id');
                    
                    $h_pending = MovementHistorical::where('movement_status_id', 2)->where('enterprise_id', $enterprise_id)->orderBy('created_at', 'desc')->where('movement_type', 'sales')->whereNotIn('order_id', $approveds)->limit(5)->get();

                    foreach ($h_pending as $key => $value) {
                        array_push($notPublished, $value->id);
                    }

                    $approveds = MovementHistorical::where('movement_status_id', 3)->where('enterprise_id', $enterprise_id)->orderBy('created_at', 'desc')->where('movement_type', 'purchases')->pluck('order_id');
                    
                    $h_pending = MovementHistorical::where('movement_status_id', 2)->where('enterprise_id', $enterprise_id)->orderBy('created_at', 'desc')->where('movement_type', 'purchases')->whereNotIn('order_id', $approveds)->limit(5)->get();

                    foreach ($h_pending as $key => $value) {
                        array_push($notPublished, $value->id);
                    }

                    $approveds = MovementHistorical::where('movement_status_id', 3)->where('enterprise_id', $enterprise_id)->orderBy('created_at', 'desc')->where('movement_type', 'transfers')->pluck('order_id');
                    
                    $h_pending = MovementHistorical::where('movement_status_id', 2)->where('enterprise_id', $enterprise_id)->orderBy('created_at', 'desc')->where('movement_type', 'transfers')->whereNotIn('order_id', $approveds)->limit(5)->get();

                    foreach ($h_pending as $key => $value) {
                        array_push($notPublished, $value->id);
                    }

                    $approveds = MovementHistorical::where('movement_status_id', 3)->where('enterprise_id', $enterprise_id)->orderBy('created_at', 'desc')->where('movement_type', 'adjustments')->pluck('order_id');
                    
                    $h_pending = MovementHistorical::where('movement_status_id', 2)->where('enterprise_id', $enterprise_id)->orderBy('created_at', 'desc')->where('movement_type', 'adjustments')->whereNotIn('order_id', $approveds)->limit(5)->get();

                    foreach ($h_pending as $key => $value) {
                        array_push($notPublished, $value->id);
                    }

                    $historical_pending = MovementHistorical::whereIn('id', $notPublished)->get(); 


                    $histApproved = [];
                    $histPending = [];

                   
                }else{
                    

                    $historical_approved = MovementHistorical::where('movement_status_id', 3)->where('location_id', $id)->where('enterprise_id', $enterprise_id)->orderBy('created_at', 'desc')->limit(5)->get();

                    $notPublished = [];
                    $approveds = MovementHistorical::where('movement_status_id', 3)->where('location_id', $id)->where('enterprise_id', $enterprise_id)->orderBy('created_at', 'desc')->where('movement_type', 'sales')->pluck('order_id');
                    
                    $h_pending = MovementHistorical::where('movement_status_id', 2)->where('location_id', $id)->where('enterprise_id', $enterprise_id)->orderBy('created_at', 'desc')->where('movement_type', 'sales')->whereNotIn('order_id', $approveds)->limit(5)->get();

                    foreach ($h_pending as $key => $value) {
                        array_push($notPublished, $value->id);
                    }

                    $approveds = MovementHistorical::where('movement_status_id', 3)->where('location_id', $id)->where('enterprise_id', $enterprise_id)->orderBy('created_at', 'desc')->where('movement_type', 'purchases')->pluck('order_id');
                    
                    $h_pending = MovementHistorical::where('movement_status_id', 2)->where('location_id', $id)->where('enterprise_id', $enterprise_id)->orderBy('created_at', 'desc')->where('movement_type', 'purchases')->whereNotIn('order_id', $approveds)->limit(5)->get();

                    foreach ($h_pending as $key => $value) {
                        array_push($notPublished, $value->id);
                    }

                    $approveds = MovementHistorical::where('movement_status_id', 3)->where('location_id', $id)->where('enterprise_id', $enterprise_id)->orderBy('created_at', 'desc')->where('movement_type', 'transfers')->pluck('order_id');
                    
                    $h_pending = MovementHistorical::where('movement_status_id', 2)->where('location_id', $id)->where('enterprise_id', $enterprise_id)->orderBy('created_at', 'desc')->where('movement_type', 'transfers')->whereNotIn('order_id', $approveds)->limit(5)->get();

                    foreach ($h_pending as $key => $value) {
                        array_push($notPublished, $value->id);
                    }

                    $approveds = MovementHistorical::where('movement_status_id', 3)->where('location_id', $id)->where('enterprise_id', $enterprise_id)->orderBy('created_at', 'desc')->where('movement_type', 'adjustments')->pluck('order_id');
                    
                    $h_pending = MovementHistorical::where('movement_status_id', 2)->where('location_id', $id)->where('enterprise_id', $enterprise_id)->orderBy('created_at', 'desc')->where('movement_type', 'adjustments')->whereNotIn('order_id', $approveds)->limit(5)->get();

                    foreach ($h_pending as $key => $value) {
                        array_push($notPublished, $value->id);
                    }

                    $historical_pending = MovementHistorical::whereIn('id', $notPublished)->get(); 


                    $histApproved = [];
                    $histPending = [];
                }

                
                 foreach ($historical_pending as $key => $value) {
                        $order = [];
                        
                        if($value->movement_type == "sales"){
                            $order = SaleOrder::find($value->order_id);
                        } elseif($value->movement_type == "purchases"){
                            $order = PurchaseOrder::find($value->order_id);
                        } elseif($value->movement_type == "transfers"){
                            $order = Transfer::find($value->order_id);
                        } elseif($value->movement_type == "adjustments"){
                            $order = Adjustment::find($value->order_id);
                        }

                        if($value->created_at->day<10){
                            $date_day =  "0".$value->created_at->day;
                        }else{
                            $date_day =  $value->created_at->day;
                        }

                        if($value->created_at->month<10){
                            $date_month =  "0".$value->created_at->month;
                        }else{
                            $date_month =  $value->created_at->month;
                        }

                        $date = $date_day."/".$date_month."/".$value->created_at->year;

                       
                        $pend = new \stdClass();
                        $pend->date = $date;
                        $pend->order_id = $value->order_id;
                        $pend->movement_status_id = $value->movement_status_id;
                        $pend->movement_type = $value->movement_type;
                        $pend->order_code = $order->code;
                        array_push($histPending, $pend);
                    }
                    
                    foreach ($historical_approved as $key => $value) {
                        
                        
                        if($value->movement_type == "sales"){
                            $order = SaleOrder::find($value->order_id);
                        } elseif($value->movement_type == "purchases"){
                            $order = PurchaseOrder::find($value->order_id);
                        } elseif($value->movement_type == "transfers"){
                            $order = Transfer::find($value->order_id);
                        } elseif($value->movement_type == "adjustments"){
                            $order = Adjustment::find($value->order_id);
                        }

                        if($value->created_at->day<10){
                            $date_day =  "0".$value->created_at->day;
                        }else{
                            $date_day =  $value->created_at->day;
                        }

                        if($value->created_at->month<10){
                            $date_month =  "0".$value->created_at->month;
                        }else{
                            $date_month =  $value->created_at->month;
                        }

                        $date = $date_day."/".$date_month."/".$value->created_at->year;

                        
                        $appr = new \stdClass();
                        $appr->date = $date;
                        $appr->order_id = $value->order_id;
                        $appr->movement_status_id = $value->movement_status_id;
                        $appr->movement_type = $value->movement_type;
                        $appr->order_code = $order->code;

                        array_push($histApproved, $appr);
                    }
                    $datas->id = $request->get('id');
                    $datas->pending = $histPending;
                    $datas->approved = $histApproved;
                    return $this->json($request, $datas, 200);
            break;
            default:
                # code...
                break;
        }
    }
	
}
