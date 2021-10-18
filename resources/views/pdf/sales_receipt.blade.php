<style>
@page { size: landscape; margin-top: 20px; margin-bottom: 20px;  }

table {page-break-inside: avoid;}
</style>
<div style="font-family: Arial, Helvetica, sans-serif;width:100%;">
@php
$tittle = 0;
@endphp
@foreach($report as $rep)

<table style="width:100%;" border="0">
	<tr><td colspan="3" style="text-align:center"><b style="font-size:22px;font-weight: bold;">Comprobante de ventas</b> <b style="text-align:right;margin-top:0px"> {{ $rep['date'] }} </b></td></tr>
    <tr><td colspan="3" style="text-align:center"> Periodo: {{ $rep['start_date'] }} - {{ $rep['end_date'] }}
    <hr>
    @foreach($rep['ids'] as $key => $cli)
        @if($key==0)
        <p style="text-align:center;margin-top:0px">Cliente: {{ $rep[$cli]['clients'][0]['client_name'] }} {{ $rep[$cli]['clients'][0]['client_rut'] }}-{{ $rep[$cli]['clients'][0]['client_rut_dv'] }}</p>
        @else
            @php
                $ver = $key-1;
            @endphp
            @if ( $rep[$rep['ids'][$ver]]['clients'][0]['client_id'] == $rep[$rep['ids'][$key]]['clients'][0]['client_id'] )
            @else
                <hr>
                <p style="text-align:center;margin-top:0px">Cliente: {{ $rep[$cli]['clients'][0]['client_name'] }} {{ $rep[$cli]['clients'][0]['client_rut'] }}-{{ $rep[$cli]['clients'][0]['client_rut_dv'] }}</p>
            @endif
        @endif
            
    
    </td></tr>
</table>

<table style="width:100%">
    <tr style="font-size:12px;">
        <th align="center">FOLIO&nbsp;&nbsp;&nbsp;&nbsp;</th>
        <th align="left">FECHA&nbsp;&nbsp;&nbsp;&nbsp;</th>
        <th align="left">TIPO DE DOCUMENTO&nbsp;&nbsp;&nbsp;&nbsp;</th>
        <th align="right">NETO</th>
        <th align="right">EXENTO</th>
        <th align="right">IMPTOS</th>
        <th align="right">IVA</th>
        <th align="right">TOTAL</th>
    </tr>
    <tr style="font-size:12px">
        <td align="center">{{ $rep[$cli]['folio'] }}</td>
        <td align="left">{{ $rep[$cli]['date_sale'] }}</td>
        <td align="left">{{ $rep[$cli]['type'] }}</td>
        <td align="right">$ {{ $rep[$cli]['net'] }}</td>
        <td align="right">$ {{ $rep[$cli]['exento'] }}</td>
        <td align="right">$ {{ $rep[$cli]['impts'] }}</td>
        <td align="right">$ {{ $rep[$cli]['tax'] }}</td>
        <td align="right">$ {{ $rep[$cli]['total'] }}</td>
    </tr> 
    @if(empty($rep[$cli]['creditNotes']) != 1)
        @foreach ($rep[$cli]['creditNotes'] as $cN) 
            <tr style="font-size:12px" >
                <td align="center">{{ $cN['creditNote_folio'] }}</td>
                <td align="left">{{ $rep[$cli]['date_sale'] }}</td>
                <td align="left">Nota de crédito</td>
                <td align="right">$ {{ $cN['creditNote_net'] }}</td>
                <td align="right">$ 0</td>
                <td align="right">$ 0</td>
                <td align="right">$ {{ $cN['creditNote_tax'] }}</td>
                <td align="right">$ {{ $cN['creditNote_total'] }}</td>
            </tr> 
        @endforeach
    @endif 
</table>
<br>
@endforeach

<hr>  
<table style="width:100%">

@foreach($rep['ids'] as $key => $cli)
    @if(empty($rep[$cli]['references']) != 1)
   
        @if( $tittle == 0)
     
<tr><td><u>REFERENCIA(S):</u></td></tr>
    <tr style="font-weight: bold; font-size:12px">
        <td align="left">VENTA&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td align="left">FOLIO&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td align="left">RAZON&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td align="left">DOCUMENTO&nbsp;&nbsp;&nbsp;&nbsp;</td>
        <td align="left">FECHA</td>
    </tr>
        @php
            $tittle ++;
        @endphp
    @endif
    @foreach($rep[$cli]['references'] as $ref) 
        <tr style="font-size:10px">
        <td align="left">{{ $rep[$cli]['folio'] }}</td>
        <td align="left">{{ $ref['folio_reference'] }}</td>
        <td align="left">{{ $ref['reason_reference'] }}</td>
        <td align="left">{{ $ref['doc_type_reference'] }}</td>
        <td align="left">{{ $ref['date_reference'] }}</td>
        </tr>  
    @endforeach

@endif
@endforeach
</table>

<br>

<hr>
<table style="width:40%">
<tr>
<td style="font-size:12px;font-weight: bold ;width:55%;">RESUMEN DE TOTALES</td>
</tr>


<tr>
<td style="width:50%;font-size:10px">NETO:</td>              <td align="right">$ {{ $rep['suma_net'] }}</td>
</tr>
 
<tr>
<td style="width:50%;font-size:10px">EXENTO:</td>            <td align="right">$ {{ $rep['suma_exento'] }}</td>
</tr>

<tr>
<td style="width:50%;font-size:10px">IMPTO. ADICIONAL:</td>  <td align="right">$ {{ $rep['suma_impto'] }}</td>
</tr>

<tr>
<td style="width:50%;font-size:10px">I.V.A.:</td>            <td align="right">$ {{ $rep['suma_tax'] }}</td>
</tr>

<tr>
<td style="width:50%;font-size:10px">TOTAL:</td>             <td align="right">$ {{ $rep['suma_total'] }}</td>
</tr>

</table>


<div style=";"></div>

@endforeach
</div>