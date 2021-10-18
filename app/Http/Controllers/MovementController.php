<?php

namespace App\Http\Controllers;

use App\SaleOrder;
use Illuminate\Http\Request;

class MovementController extends Controller
{
    public function movements()
    {
        return view('movements.movements');
    }

    public function sales()
    {
        return view('movements.sales');
    }
    public function showSales($id)
    {
        $sale_order = SaleOrder::find($id);
        $data = ['sale_order' => $sale_order];
        return view('movements.show_sales', $data)->with('id', $id);
    }

    public function purchases()
    {
        return view('movements.purchases');
    }
    public function showPurchases($id)
    {
        return view('movements.show_purchases')->with('id', $id);
    }

    public function transfers()
    {
        return view('movements.transfers');
    }
    public function showTransfers($id)
    {
        return view('movements.show_transfers')->with('id', $id);
    }

    public function adjustments()
    {
        return view('movements.adjustments');
    }
    public function showAdjustments($id)
    {
        return view('movements.show_adjustments')->with('id', $id);
    }

    public function workOrders()
    {
        return view('movements.work_orders');
    }
    public function showWorkOrders($id)
    {
        return view('movements.show_work_orders')->with('id', $id);
    }

}
