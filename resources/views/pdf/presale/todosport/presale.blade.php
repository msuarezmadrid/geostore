<style>
  @page { margin-top: 0px; margin-bottom: 0px; }
  </style>
  <div style="font-family: Arial, Helvetica, sans-serif;width:100%">
  <div style="height:10px"></div>
  <div style="width:100%;margin-left:10px">{!!DNS1D::getBarcodeHTML($id, 'C39', 1, 40)!!}</div>
  <div style="text-align:center">
    @if($onote->header == 'DEFAULT')
      <b>Numero de Pedido</b> <br><br>
      <b>ORDEN</b> <br>
      <b>{{ $onote->id }}</b>
    @else
      <b>{{$onote->header}}</b> <br><br> 
    @endif
  </div>
  <br>
  <table style="width:100%">
  <tr>
    <td width="70%">Fecha:</td>
    <td>Hora:</td>   
  </tr>
  <tr>
    <td width="70%">{{ $onote->date  }}</td>
    <td>{{ $onote->hour }}</td>   
  </tr>
  </table>
  <br>
  <b>Vendedor:</b> {{ $onote->sellername }}
  <br>
  <b>Items:</b> {{ count($onote->details)  }}
  <hr />
  <b>Cliente:</b> {{ $onote->clientname }}
  <br>
  <b>Retira:</b>
  <br>
  <b>Rut:</b> {{ $onote->clientid }}
  <br>
  <b>Firma :</b>
  <hr />
  <table style="width:100%;font-size:9px" >
  <tr>
   <td>CODIGO</td>
   <td></td>
   <td></td>
    <td>VALOR TOTAL</td>
   </tr>
    <tr>
    <td>DESCRIPCIÓN</td>
    <td></td>
    <td></td>
    <td></td>
    </tr>
    <tr>
    <td>CANT</td>
     <td>X</td>
     <td>VALOR</td>
    </tr>
  </table>
  <hr />
  <table style="width:100%;font-size:12px" >
  @foreach ($onote->details as $detail)
  <tr>
  <td colspan="2">{{ $detail->manufacturer_sku }}</td>
  <td colspan="2">${{ number_format($detail->price*$detail->qty,0,',','.') }}</td>
  </tr>
  <tr>
  <td colspan="4">{{ $detail->name }}</td>
  </tr>
  <tr>
  <td>{{ $detail->qty }}</td>
  <td>X</td>
  <td>${{ number_format($detail->price,0,',','.') }}</td>
  </tr>
  @endforeach
  </table>
  <hr />
  <b style="font-size:14px">MONTO TOTAL : ${{ number_format($onote->total,0,',','.') }} </b> 
  <div style="font-size:10px">
  </div>
  </div>
  