<?php

namespace App\Http\Controllers;

use App\Config;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    public function index() {
        $config_rounding = Config::where('param', 'ROUNDING')->first();
        $config_theme = Config::where('param', 'LIGHT_THEME')->first();
        $config_print = Config::where('param', 'DIRECT_PRINT')->first();
        $config_pos_item_qty = Config::where('param', 'PRESALE_POS_ITEM_QUANTITY')->first();
        $cedible = Config::where('param', 'CEDIBLE')->first();
        $caja = Config::where('param', 'TIPO_CAJA')->first();
        $multiprint = Config::where('param', 'VOUCHER_MULTIPRINT')->first();
        $submitTicket = Config::where('param', 'CREDIT_GENERATE_TICKET')->first();
        if($caja == null) {
            $caja = new Config();
            $caja->id = -1;
            $caja->value = 0;
        }
        if($multiprint == null) {
            $multiprint = new Config();
            $multiprint->id = -1;
            $multiprint->value = 'DEFAULT';
        }
        if($submitTicket == null) {
            $submitTicket = new Config();
            $submitTicket->id = -1;
            $submitTicket->value = '1';
        }
        return view('configs.index', [
            'rounding' => $config_rounding,
            'light_theme' => $config_theme,
            'print_direct' => $config_print,
            'pos_item_qty' => $config_pos_item_qty,
            'cedible' => $cedible,
            'caja' => $caja,
            'multiprint' => $multiprint,
            'submitTicket' => $submitTicket
        ]);
    }
}
