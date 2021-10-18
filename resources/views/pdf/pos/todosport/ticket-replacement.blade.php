
<style>
    @page { margin-top: 0px; margin-bottom: 0px; }
    
    .text-large {
        font-size: 18px
    }
    
    .text-medium {
        font-size: 12px
    }
    
    .text-small {
        font-size: 10px
    }
</style>
<div style="font-family: Arial, Helvetica, sans-serif;width:100%">
<div style="height:10px"></div>
    <div class="text-medium" style="width:100%;text-align:center;font-weight:bold">
        {{ $sale['rznSoc'] }}
    </div>
    <br>
    <div class="text-medium" style="width:100%;text-align:center">
        DETALLE
    </div>
    <div class="text-small" style="text-align:center">
        {{ $sale['date']  }}  {{ $sale['hour'] }}
    </div>
    <br>
    <hr />
    <table style="width:100%;font-size:9px;" >
        <tr>
            <td style="border-color:black;border:1px">DESCRIPCION</td>
            <td>CANT</td>
            <td>PRECIO</td>
        </tr>
        <tr>
            <td colspan="3">
                <hr />
            </td>
        </tr>
        @foreach ($sale['details'] as $item)
        <tr style="font-size:12px">
            <td>{{ trim(substr($item['item_name'], 0, 11)) }}</td>
            <td style="text-align: right">{{ $item['qty'] }}</td>
            <td style="text-align: right">${{ number_format($item['item_price'],0,',','.') }}</td>
        </tr>
        @endforeach
    </table>
    <hr />
    <div>
        <b style="font-size:14px;float:left">MONTO TOTAL : </b> 
        <b style="font-size:14px;float:right">{{ $sale['total'] }} </b>    
    </div>
    <div style="clear:both;"></div>
    <div class="text-medium" style="width:100%;text-align:center">
        NO VALIDO COMO BOLETA DE VENTA
    </div>
    <div style="font-size:10px">
    </div>
</div>

