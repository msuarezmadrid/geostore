<style>

    .left {
        text-align: left;
    }

    .center {
        text-align: center;
    }

    .center-element {
        margin: auto;
        width: 50%;
    }

    .right {
        text-align: right;
    }

    .fullwidth {
        width: 100%;
    }

    .halfwidth {
        width: 50%;
    }

    .inline {
        display:inline;
    }

    .strong {
        font-size: 20px;
        font-weight: bold;
    }

</style>

<div style="font-family: Arial, Helvetica, sans-serif;">

    <table class="fullwidth" border="0">
        <tr>
            <td class="center">
                <b>INICIO</b>
            </td>
            <td class="center">
                <b>FIN</b>
            </td>
        </tr>
        <tr>
            <td class="center">
                {{$info->fechaInicio}} : {{$info->horaInicio}}
            </td>
            <td class="center">
                {{$info->fechaTermino}} : {{$info->horaTermino}}
            </td>
        </tr>
        <tr>
            <td class="center" colspan="2" >
                {{$info->boxName}}                    
            </td>
        </tr>
    </table>
    
    </hr>
    
    @if($info->tipoCaja == "0")
    <div class="center-element inline">
    @elseif($info->tipoCaja == "1")
    <div class="fullwidth inline">
    @endif
    
        <table class="fullwidth" id="final_fund">
            <tr>
                <th colspan="2" class="center">
                    Total por tipo de documento:
                </th>
            </tr>
            <tr>
                <th class="halfwidth center">Tipo</th>
                <th class="halfwidth center">Importe</th>
            </tr>
            <tr>
                <td class="halfwidth" id="t_total_ticket">Total Ventas con Boletas</td>
                <td class="right halfwidth">
                    {{$info->box['total_ticket']}}
                </td>
            </tr>
            <tr>
                <td class="halfwidth" id="t_total_invoice">Total Ventas con Facturas</td>
                <td class="right halfwidth">
                    {{$info->box['total_invoice']}}
                </td>
            </tr>
        </table>
        
    </hr>
        <table id="final_fund" class="fullwidth">
            <tr>
                <th colspan="2" class="center">
                    Total Por Forma de Pago: 
                </th>
            </tr>
            <tr>
                <th class="halfwidth center">Tipo</th>
                <th class="halfwidth center">Importe</th>
            </tr>
            <tr>
                <td class="halfwidth" id="t_total_cheque">Total Ventas con Cheques</td>
                <td class="right halfwidth">
                    {{$info->box['total_cheque']}}                        
                </td>
            </tr>
            <tr>
                <td class="halfwidth" id="t_total_cash">Total Ventas con Efectivo</td>
                <td class="right halfwidth">
                    {{$info->box['total_cash']}}
                </td>
            </tr>
            <tr>
                <td class="halfwidth" id="t_total_card">Total Ventas con Tarjetas</td>
                <td class="right halfwidth">
                    {{$info->box['total_card']}}
                </td>
            </tr>
            <tr>
                <td class="halfwidth" id="t_total_intern">Total Ventas con Crédito Interno</td>
                <td class="right halfwidth">
                    {{$info->box['total_intern']}}
                </td>
            </tr>
            <tr>
                <td class="halfwidth" id="t_total_app">Total Ventas con Aplicaciones</td>
                <td class="right halfwidth">
                    {{$info->box['total_app']}}
                </td>
            </tr>
            <tr>
                <td class="halfwidth" id="t_total_transfer">Total Ventas con Transferencias</td
                <td class="right halfwidth">
                    {{$info->box['total_transfer']}}
                </td>
            </tr>
            <tr>
                <td class="halfwidth" id="t_total_credit_note">Notas de crédito</td>
                <td class="right halfwidth">
                    {{$info->box['total_credit_note']}}
                </td>
            </tr>
            <tr>
                <td class="halfwidth" id="t_total_rounding">Total de redondeo</td>
                <td class="right halfwidth">
                    {{$info->box['total_rounding']}}
                </td>
            </tr>
            <tr>
                <th class="strong left halfwidth" id="t_total_sales">Total de ventas</th>
                <th class="strong right halfwidth">
                    {{$info->box['sales_total']}}
                </th>
            </tr>
        </table>
        
    </hr>
        <table id="final_fund" class="fullwidth">
            <tr>
                <th colspan="2" class="center">
                    Subtotales
                </th>
            </tr>
            <tr>
                <th class="halfwidth center">Tipo</th>
                <th class="halfwidth center">Importe</th>
            </tr>
            <tr>
                <td class="halfwidth" id="t_total_income">Total de Ingresos Caja</td>
                <td class="right halfwidth">
                    {{$info->box['total_income']}}
                </td>
            </tr>
            <tr>
                <td class="halfwidth" id="t_total_expenses">Total de Egresos Caja</td>
                <td class="right halfwidth">
                    {{$info->box['total_expenses']}}
                </td>
            </tr>
            <tr>
                <td class="halfwidth" id="t_smallbox">Caja Chica</td>
                <td class="right halfwidth">
                    {{$info->box['smallbox']}}
                </td>
            </tr>
            <tr>
                <th class="strong left halfwidth" id="t_total_calculated">Total en Caja Calculado</th>
                <th class="strong right halfwidth">
                    {{$info->box['total_calculated']}}
                </th>
            </tr>
        </table>
        
    </hr>
    </div>
    
    @if($info->tipoCaja == "1")
    <div class="fullwidth center inline">
        <table id="final_fund" class="fullwidth">
            <tr>
                <th class="center" colspan="3">
                    Desglose
                </th>
            </tr>
            <tr>
                <th class="halfwidth center">Tipo</th>
                <th class="halfwidth center">Valor</th>
                <th class="halfwidth center">Cantidad</th>
            </tr>
            <tr>
                <td class="halfwidth" id="t_total_20k">
                    $20.000
                </td>
                <td class="right halfwidth" id="total_20k">
                    {{ $info->boxCoins->val20k }}
                </td>
                <td class="right halfwidth" id="total_20k">
                    {{ $info->boxCoins->qty20k }}
                </td>
            </tr>
            <tr>
                <td class="halfwidth" id="t_total_10k">
                    $10.000
                </td>
                <td class="right halfwidth" id="total_10k">
                    {{ $info->boxCoins->val10k }}                        
                </td>
                <td class="right halfwidth" id="total_10k">
                    {{ $info->boxCoins->qty10k }}
                </td>
            </tr>
            <tr>
                <td class="halfwidth" id="t_total_5k">
                    $5.000
                </td>
               <td class="right halfwidth" id="total_5k">
                    {{ $info->boxCoins->val5k }}                        
                </td>
                <td class="right halfwidth" id="total_5k">
                    {{ $info->boxCoins->qty5k }}
                </td>
            </tr>
            <tr>
                <td class="halfwidth" id="t_total_2k">
                    $2.000
                </td>
                <td class="right halfwidth" id="total_2k">
                    {{ $info->boxCoins->val2k }}                        
                </td>
                <td class="right halfwidth" id="total_2k">
                    {{ $info->boxCoins->qty2k }}
                </td>
            </tr>
            <tr>
                <td class="halfwidth" id="t_total_1k">
                    $1.000
                </td>
                <td class="right halfwidth" id="total_1k">
                    {{ $info->boxCoins->val1k }}                        
                </td>
                <td class="right halfwidth" id="total_1k">
                    {{ $info->boxCoins->qty1k }}
                </td>
            </tr>
            <tr>
                <td class="halfwidth" id="t_total_500">
                    $500
                </td>
                <td class="right halfwidth" id="total_500">
                    {{ $info->boxCoins->val500 }}                        
                </td>
                <td class="right halfwidth" id="total_500">
                    {{ $info->boxCoins->qty500 }}
                </td>
            </tr>
            <tr>
                <td class="halfwidth" id="t_total_100">
                    $100
                </td>
                <td class="right halfwidth" id="total_100">
                    {{ $info->boxCoins->val100 }}                        
                </td>
                <td class="right halfwidth" id="total_100">
                    {{ $info->boxCoins->qty100 }}
                </td>
            </tr>
            <tr>
                <td class="halfwidth" id="t_total_50">
                    $50
                </td>
                <td class="right halfwidth" id="total_50">
                    {{ $info->boxCoins->val50 }}                        
                </td>
                <td class="right halfwidth" id="total_50">
                    {{ $info->boxCoins->qty50 }}
                </td>
            </tr>
            <tr>
                <td class="halfwidth" id="t_total_10">
                    $10
                </td>
                <td class="right halfwidth" id="total_10">
                    {{ $info->boxCoins->val10 }}                        
                </td>
                <td class="right halfwidth" id="total_10">
                    {{ $info->boxCoins->qty10 }}
                </td>
            </tr>
            <tr>
                <td class="halfwidth" id="t_total_5">
                    $5
                </td>
                <td class="right halfwidth" id="total_5">
                    {{ $info->boxCoins->val5 }}                        
                </td>
                <td class="right halfwidth" id="total_5">
                    {{ $info->boxCoins->qty5 }}
                </td>
            </tr>
            <tr>
                <td class="halfwidth" id="t_total_1">
                    $1
                </td>
                <td class="right halfwidth" id="total_1">
                    {{ $info->boxCoins->val1 }}                        
                </td>
                <td class="right halfwidth" id="total_1">
                    {{ $info->boxCoins->qty1 }}
                </td>
            </tr>
            <tr>
                <th class="left halfwidth strong" id="t_total_real_box">Total Efectivo</th>
                <th class="strong right halfwidth" id="total_real">
                    {{ $info->realCash }}
                </th>
            </tr>
            <tr>
                <th class="left halfwidth strong" id="t_contra_box">En Contra</th>
                <th class="strong right halfwidth" id="total_contra">
                    {{ $info->boxCoins->contra}}
                </th>
            </tr>
        </table>
        @endif
        @if($info->tipoCaja == "0")
        <table class="fullwidth">
            <tr>
                <th class="strong left halfwidth">
                    <label for="real_cash"> Saldo real efectivo caja: </label>
                </th>
                <th class="strong right halfwidth">
                    {{$info->realCash}}
                </th>
            </tr>
        </table>
    </div>
    @endif

</div>