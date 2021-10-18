<style>
@page { margin-top: 10px; margin-bottom: 0px; }

table {page-break-inside: avoid;}
</style>
<div style="font-family: Arial, Helvetica, sans-serif;width:100%">

<div style="border: solid 0 #0; border-left-width:2px; padding-left:0.5ex">
<div style="border: solid 0 #0; border-left-width:2px; padding-left:0.5ex">
<table style="width:100%;" >
    <tr>
    <td colspan="3" style="width:50%;text-align:center;border:1px" >
    <div style="border:0.3px solid 0 #0; border-left-width:0.3px; padding-left:0.5ex">
    <b style="font-size:32px;font-weight: bold;text-align:center;"> {!! config("afe.company_name") !!} </b> <br>
    <b style="font-weight: bold;text-align:center;"> R.U.T.: {!! config("afe.company_rut") !!}</b><br>
    <b style="font-weight: bold;text-align:center;"> GIRO: {!! config("afe.company_industries") !!}</b><br>
    <b style="font-weight: bold;text-align:center;"> Email: {!! config("afe.company_email") !!} </b>
    </div>
    </td>
    <td colspan="3" style="width:50%;text-align:center;">

    <b style="font-size:32px;font-weight: bold;text-align:center;"> COTIZACIÓN </b><br>
    <b style="font-size:32px;font-weight: bold;text-align:center;"> N° PQ{{$onote->id}} </b>
    </td>
    </tr>
</table>
</div>

<table style="width:100%;">
    
    <tr>
    <td td colspan="3" style="width:50%;">
    R.U.T       :   {{$onote->clientid}}<br>
    CLIENTE     :   {{$onote->clientname}}<br>
    DIRECCION   :   {{$onote->address}}
    </td>
    <td td colspan="3" style="width:50%;">
    FECHA SOLICITUD :    {{$onote->date}} - {{$onote->hour}}<br>
    <br>
    <!--NOTAS DE PEDIDO   :   ON03,ON04 -->
    </td>
    </tr>
    </table>      
<hr>
Atendiendo su solicitud, estamos enviando cotización de los productos, estamos a su orden.
<hr>
<table style="width:100%;" border="1">
<tr>
    <th style="align:center">CÓDIGO</th>
    <th style="align:center">CANT.</th>
    <th style="align:center" colspan="2">DESCRIPCIÓN</th>
    <th style="align:center">P.UNIT C/ DESC</th>
    <th style="align:center">TOTAL NETO</th>
</tr>
@foreach($onote->details as $detail)
<tr>
    <td style="align:left;font-size:12px">{{$detail->manufacturer_sku}}</td>
    <td style="align:left;font-size:12px">{{$detail->qty}}</td>
    <td style="align:left;font-size:12px" colspan="2">{{$detail->name}}</td>
    <td style="font-size:12px" align="right">$ {{ number_format($detail->item_price, 2, ',', '.') }}</td>
    <td style="font-size:12px" align="right">$ {{ number_format($detail->item_total, 0, ',', '.') }}</td>
</tr>
@endforeach
<tr>
    <td colspan="4" rowspan="3" style="font-size:14px" >OBSERVACIÓN : <br> {{$onote->price_quote_description}}
    </td>

    <td style="font-size:12px;font-weight: bold" align="left">NETO C/ DESC</td>
    <td style="font-size:12px" align="right">$ {{$onote->net}}</td>
</tr>
<tr>
    <td style="font-size:12px;font-weight: bold" align="left">I.V.A. 19%</td>
    <td style="font-size:12px" align="right">$ {{$onote->tax}}</td>
</tr>
<tr>
    <td style="font-size:12px;font-weight: bold" align="left">TOTAL</td>
    <td style="font-size:12px" align="right">$ {{$onote->total}}</td>
</tr>


</table>
</div>
@if ($onote->price_quote_expiration != 0)
    Esta cotización sólo es válida solo por {{$onote->price_quote_expiration }} dias desde su creación.
@endif
<p></p>
</div>