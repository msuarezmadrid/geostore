<?php

namespace App\Http\Controllers;

use App\SaleBox;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    //
    public function index()
    {
         return view('reports.sales');
    }

    public function movements() {
        $boxes = SaleBox::all();
        return view('reports.movements', [
            'boxes' => $boxes
        ]);
    }

    public function sales() {
        return view('reports.seller_sales');
    }

    public function salesCategory() {
        return view('reports.category_sales');
    }

    public function salesWithoutStock() {
        return view('reports.sales_without_stock');
    }
    

}
