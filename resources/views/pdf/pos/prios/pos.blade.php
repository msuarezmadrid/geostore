<style>
@page { margin-top: 10px; margin-bottom: 0px; }
</style>
<div style="font-family: Arial, Helvetica, sans-serif;width:100%">
@php
$count = 0;
@endphp
@foreach($details as $detail)
@php
$count ++;
@endphp
<table style="width:100%;" border="1">
	<tr><td colspan="3" style="text-align:center">PLASTICOS LOS RIOS</td></tr>
    <tr><td colspan="3">
    <b style="font-size:22px;font-weight: bold;">Guia de Entrega {{ $detail['id']  }}</b> 
    <p style="text-align:center;margin-top:0px">Retira en : {{ $detail['location_name'] }}</p>
      @if($detail['type'] == 1)
    <span>Según Boleta Nro: {{ $detail['doc_id'] }}</span>
     @else
     <span>Según Factura Nro: {{ $detail['doc_id'] }}</span>

      @endif
    </td></tr>
    <tr><td colspan="3">
    <b>RUT :</b> {{ $detail['client_id'] }} <br>
    <b style="margin-top:0px">CLIENTE : {{ $detail['client_name'] }}</b> <br>
    <b style="margin-top:0px">DIRECCION : {{ $detail['client_address'] }}</b>
    </td></tr>
    <tr>
    <td colspan="3">
    	<span style="font-size:9px">SIRVA(N)SE RECIBIR LO SIGUIENTE EN BUENAS CONDICIONES DANDO SU CONFORMIDAD AL PIE:</span>
    </td>
    </tr>
    <tr style="font-weight: bold;">
    	<td>Código</td>
      <td>Cant.</td>
      <td>Descripción</td>
    </tr>
    @foreach($detail['details'] as $det) 
    <tr style="font-weight: bold;font-size:10px">
      <td>{{ $det['code'] }}</td>
      <td>{{ $det['qty'] }}</td>
      <td>{{ $det['item_name'] }}</td>
    </tr>  
    @endforeach
</table>
<table>
	<tr>
    	<td style="text-align:left">
        	<b>Nota</b> : <br>
           <span style="font-size:10px"> Este documento no es válido como Boleta o Factura</span>
        </td>
        <td>
        	<div style="text-align:center">Vendedor : {{ $detail['seller_name'] }}</div>
        </td>
    </tr>
</table>
<br>
<br>
<br>
<table style="width:100%">
<tr>
<td style="width:50%;font-size:10px"><hr>NOMBRE DEL DESPACHADOR</td>
<td style="width:50%;font-size:10px;text-align:center"><hr>RECIBI CONFORME</td>
</tr>
</table>
<b>Observación</b> : <br><br>
<hr>
<br>
<hr>
@if ($count != count($details))
<div style="page-break-after:always;"></div>
@endif
@endforeach
</div>