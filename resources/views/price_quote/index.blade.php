@extends('layouts.master')

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery-editable-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-select/bootstrap-select.min.css') }}">
    <link href="{{ asset('poscss/jquery.toast.css') }}" rel="stylesheet">

    <style>
      .attribute-item{
        max-width: 400px;
        word-wrap: break-word;
      }
      .collapsed {
        height: auto !important;; 
    }
    </style>
@endsection

@section('content')
<section class="content-header">
<h1>
  <i class="fa fa-buysellads" style="ping-right: 5px;"></i> Crear Cotización
</h1>
<ol class="breadcrumb">
  <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
  <li class="active">Crear Cotización</li>
</ol>
</section>
<section class="content">
	<div class="row">
		<div class="col-md-6">
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title">Buscar número de orden</h3>
				</div>
                <div class="box-body">
                    <label for="show-order_note">Número de orden:</label>
                        <div class="form-group">
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="show-order_note"/>
                            </div>
                            <div class="col-sm-3">
                                <button id="s_get_order_note"  onClick="getOrderNote();" class="btn btn-primary">Buscar</button>
                            </div>
                        </div>
                    </div>
				<div class="box-header">
					<h3 class="box-title">Cotizaciones</h3>
				</div>
                <div class="box-body">
                    <label for="show-price_quote">Numero de cotizacion:</label>
                    <div class="form-group">
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="show-price_quote"/>
                        </div>
                        <div class="col-sm-3">
                                <button id="s_get_price_quote"  onClick="getPriceQuote();" class="btn btn-primary">Buscar</button>
                        </div>
                    </div>
                </div>
            </div>            
		</div>
		<div class="col-md-6">
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title">Cliente</h3>
				</div>
                <div class="box-body">
                    <label for="select_rut">RUT:</label>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <select id="select_rut" class="form-control form-control-sm simple-select1-sm">
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <label for="select_client">Nombre:</label>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <select id="select_client" class="form-control form-control-sm simple-select2-sm">
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <label for="show-client_ress">Dirección:</label>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <input type="text" disabled class="form-control" id="show-client_ress"/>
                        </div>
                    </div>
                </div>
  			</div>
		</div>
	</div>
	<div class="modal-body">
		<div class="panel box box-primary">
			<div class="box-header with-border">
				<h4 class="box-title">
				<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
					Agregar Productos
				</a>
				</h4>
			</div>
			<div id="collapseOne" class="panel-collapse collapse in show" role="tabpanel" aria-labelledby="headingOne">
				<div class="box-body">
					<div class="row">
						<div class="form-group col-md-2">
							<label for="select_code">Código</label>
							<input type="text" class="form-control" id="select_code" placeholder="Código">
						</div>
						<div class="form-group col-md-2">
							<label for="input_qty">Cantidad</label>
							<input type="number" class="input_qty form-control" id="input_qty" placeholder="Cantidad">
						</div>
						<div class="form-group col-md-3">
							<label for="i_name">Descripción</label>
                            <select id="select_product_description" class="form-control form-control-sm simple-select3-sm">
                                            <option></option>
                            </select>
						</div>
						<div class="form-group col-md-2">
							<label for="input_price">Valor</label>
							<input type="text" disabled class="input_price form-control" id="input_price" placeholder="Valor">
						</div>
						<div class="form-group col-md-2">
							<label for="input_total">Total</label>
							<input type="text" disabled class="input_total form-control" id="input_total" placeholder="Total">
						</div>
						<div class="form-group col-md-1">
							<p></p>
							<button type="button" class="btn" id="Item" onClick="SelectedItemToCart()"><i class="fa fa-plus" style="color:green"></i></button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="panel box box-primary">
			<div class="box-header with-border">
				<h4 class="box-title">
				<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
				  Productos agregados
				</a>
				</h4>
			</div>
			<div id="collapseTwo" class="panel-collapse collapse in show">
				<div class="box-body">
					<table id="dataItems" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>#</th>
                                <th>Codigo</th>
								<!-- <th>N° Pedido</th> -->
                                <th>Stock</th>
								<th>Descripcion</th>
								<th>Cantidad</th>
								<th class="text-right">Precio</th>
								<th>Descuento</th>
								<th class="text-right">Precio-Descuento</th>
								<th class="text-right">Total</th>
								<th>Acciones</th>
                            </tr>
						</thead>
						<tbody class="body-table">
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<div class="box box-primary">
                <div class="box-body">
                    <label for="show-associated_order_note">N° Pedidos asociados:</label>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <select type="text" class="form-control" disabled id="show-associated_order_note" size=5/>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
		</div>
        <div class="col-md-5">
			<div class="box box-primary">
                <div class="box-body">
                    
                    <div class="form-group">
                        <div class="col-sm-6">
                        <label for="show-apply_dsc">Descuento:</label>
                            <input type="text" class="form-control" id="show-apply_dsc"/>
                        </div>
                        <div class="col-sm-6">
                            <button id="btn_apply_dsc"  onClick="applyDsc();" class="btn btn-primary">Aplicar</button>
                        </div>
                        <div class="col-sm-12">
                            <label for="s_description">Observacion:</label>
                            <input type="textarea" class="form-control" id="s_description"/>
                        </div>
                        <div class="col-sm-12">
                            <label for="s_expiration">Dias de duración:</label>
                            <input type="number" class="form-control" id="s_expiration"/>
                        </div>
                    </div>
                </div>
            </div>            
		</div>
        <div class="col-md-4">
			<div class="box box-primary">
                <div class="box-body">
                    <label for="show-apply_dsc">Descuento:</label>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <div class="float-right">
                                NETO: 
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="float-right show-net" style="float: right">
                                $0
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="float-right">
                                I.V.A: 
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="float-right show-tax" style="float: right">
                                $0
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="float-right">
                                TOTAL: 
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="float-right show-total" style="float: right">
                                $0
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="float-right">
                                <button onclick="cleanedItems()" class="btn btn-sm tool_button float-right" style="margin-right:10px">
                                    <i class="fa fa-eraser"></i> 
                                        LIMPIAR
                                </button>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="float-right">
                                <button class="btn btn-sm tool_button float-right" onclick="printFile()" style="margin-right:10px">
                                <i class="fa fa-print"></i>
                                    IMPRIMIR
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>            
		</div>
	</div>
</section>

@endsection
@section('js')
<script src="{{ asset('js/api.js') }}"></script>
<script src="{{ asset('js/api.js') }}"></script>
<script src="{{ asset('js/utils.js') }}"></script>
<script src="{{ asset('js/validator.js') }}"></script>
<script src="{{ asset('js/modal.js') }}"></script>
<script src="{{ asset('js/awsidebar.js') }}"></script>
<script src="{{ asset('js/jquery-editable-select.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-select/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('jspos/jquery.toast.js') }}"></script>



<!-- DataTables -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
<!-- bootstrap datepicker -->
<script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script>
    
    contItems = 1;

    var price_quote_info = {
        client: null,
        selected_item : null,
        cart_items : [],
    };
    var testing = 0 ;


    $(document).ready(function() {


        $('.simple-select3-sm').selectpicker({
            liveSearch:true,
            noneSelectedText: 'Seleccione un item',
            noneResultsText: ''
        });
        $('.simple-select1-sm').selectpicker({
            liveSearch:true,
            noneSelectedText: 'Seleccione un rut',
            noneResultsText: ''
        });
        $('.simple-select2-sm').selectpicker({
            liveSearch:true,
            noneSelectedText: 'Seleccione un cliente',
            noneResultsText: ''
        });


        $('.simple-select3-sm').find('.bs-searchbox').find('input').on('keyup', function(event) {
        //get code 
        if(event.keyCode != 38 && event.keyCode != 40) {
            $('.simple-select3-sm').find('option').remove();
            $('.simple-select3-sm').selectpicker('refresh');
            AWApi.get('{{ url('/api/items/search') }}?type=desc&value='+$(this).val(), function(response) {
                if(response.code == 200) {
                    src = [{
                        id: null, txt:""
                    }];
                    response.data.map( (item) => {
                        src.push({
                            id:item.id,
                            txt:item.name
                        });
                    });
                    var options = [];

                    src.forEach(function (item) {
                        var option = "<option value="+item.id+" >" + item.txt + "</option>"
                        options.push(option);
                    });
                    $('#select_product_description').html(options);
                    $('.simple-select3-sm').selectpicker('refresh');
                }
            });
        }
    });

    $('.simple-select1-sm').find('.bs-searchbox').find('input').on('keyup', function(event) {
        //get code 
        if(event.keyCode != 38 && event.keyCode != 40) {
            $('.simple-select1-sm').find('option').remove();
            $('.simple-select1-sm').selectpicker('refresh');
            AWApi.get('{{ url('/api/clients/search/') }}/rut/'+$(this).val(), function(response) {
                if(response.code == 200) {
                    src1 = [{
                        id: null, txt:""
                    }];

                    src2 = [];
           
                    response.data.rut.map( (client) => {
                        

                        src1.push({
                            id:client.id,
                            txt: (client.rut + '-' + client.rut_dv)
                        });
                    });

                    response.data.codigo_interno.map( (client) => {
                        

                        src2.push({
                            id:client.id,
                            txt: client.internal_code
                        });
                    });
                    var options = [];
                    var option = '<optgroup label="Rut">';
                    src1.forEach(function (item) {
                        
                        option += "<option value="+item.id+" >" + item.txt + "</option>"
                        
                    });
                    option += '</optgroup>';

                    option += '<optgroup label="Rut chico">';
                    src2.forEach(function (item) {
                        
                        option += "<option value="+item.id+" >" + item.txt + "</option>"
                        
                    });
                    option += '</optgroup>';


                    options.push(option);
                    $('#select_rut').html(options);
                    $('.simple-select1-sm').selectpicker('refresh');
                }
            });
        }
    });
    $('.simple-select2-sm').find('.bs-searchbox').find('input').on('keyup', function(event) {
        //get code 
        if(event.keyCode != 38 && event.keyCode != 40) {
            $('.simple-select2-sm').find('option').remove();
            $('.simple-select2-sm').selectpicker('refresh');
            AWApi.get('{{ url('/api/clients/search/') }}/name/'+$(this).val(), function(response) {
                if(response.code == 200) {
                    src = [{
                        id: null, txt:""
                    }];
                    response.data.map( (client) => {
                        src.push({
                            id:client.id,
                            txt: client.name
                        });
                    });
                    var options = [];
                    src.forEach(function (item) {
                        var option = "<option value="+item.id+" >" + item.txt + "</option>"
                        options.push(option);
                    });
                    $('#select_client').html(options);
                    $('.simple-select2-sm').selectpicker('refresh');
                }
            });
        }
    });

    $('#select_product_description').on('change', function() {
        if($(this).val() != '' && $(this).val() != 'null' && $(this).val() != 0) {
            AWApi.get('{{ url('/api/items/') }}/'+$(this).val(), function(response) {
                if (response.code == 200) {
                    price_quote_info.selected_item ={
                        item_id : response.data.item.id,
                        name : response.data.item.name,
                        price : 0,
                        qty : 1,
                        total : 0,
                        discount: 0,
                        code : response.data.item.manufacturer_sku,
                        block_discount : response.data.item.block_discount
                    };
                    response.data.prices.map(function(price) {
                        console.log(price);
                        if(price.item_active == "1") {
                            price_quote_info.selected_item.price_original = price.price;
                            price_quote_info.selected_item.price = roundToTwo(price.price/1.19);
                            price_quote_info.selected_item.total = Math.round(price.price/1.19);
                        }
                    });
                    $('#select_code').val(price_quote_info.selected_item.code);
                    updatePrices();
                }
            });
        } else if ($(this).val() == 0) {
            //ITEM QUE NO PERTENECE A BODEGA
            let pname = $(this).find('option:selected').text()
            let oname = pname.replace('NO STOCK $0','');

            price_quote_info.selected_item ={
                        item_id : 0,
                        name : oname,
                        price : 0,
                        qty : 1,
                        total : 0,
                        discount: 0,
                        code : ''
            };
            $('#select_code').val('');
            updatePrices();
        }else {
            $('#select_code').val('');
            price_quote_info.selected_item = null;
            updatePrices();
        }
    });

    $('#select_rut').on('change', function() {
        if($(this).val() != '' && $(this).val() != 'null') {
            AWApi.get('{{ url('/api/clients/') }}/'+$(this).val(), function(response) {
                 if(response.code == 200) {
                    $('.simple-select2-sm').find('option').remove();
                    price_quote_info.client = response.data.id;
                    src = [
                        { id: null, txt:"" }, 
                        { id: response.data.id, 
                          txt: response.data.name}];
                    var options = [];
                    src.forEach(function (item) {
                        var option = "<option value="+item.id+" >" + item.txt + "</option>"
                        options.push(option);
                    });
                    $('#select_client').html(options);
                    $('.simple-select2-sm').selectpicker('refresh');
                    $('.simple-select2-sm').selectpicker('val', response.data.id);
                    $('#show-client_ress').val(response.data.address);
                 }
            });
        } else {
            $('.simple-select2-sm').selectpicker('val', '');
            price_quote_info.client = null;
            $('#show-client_ress').val('');

        }
    });
    $("#select_client").on('change', function() {
        if($(this).val() != '' && $(this).val() != 'null') {
            AWApi.get('{{ url('/api/clients/') }}/'+$(this).val(), function(response) {
                if(response.code == 200) {
                    $('.simple-select1-sm').find('option').remove();
                    price_quote_info.client = response.data.id;
                    src = [
                        { id: null, txt:"" }, 
                        { id: response.data.id, 
                          txt: (response.data.rut + '-' + response.data.rut_dv)}];
                    var options = [];
                    src.forEach(function (item) {
                        var option = "<option value="+item.id+" >" + item.txt + "</option>"
                        options.push(option);
                    });
                    $('#select_rut').html(options);
                    $('.simple-select1-sm').selectpicker('refresh');
                    $('.simple-select1-sm').selectpicker('val', response.data.id);
                    $('#show-client_ress').val(response.data.ress);
    
                }
            });
        } else {
            $('.simple-select1-sm').selectpicker('val', '');
            price_quote_info.client = null;
            $('#show-client_ress').val('');

        }
    });

    $('.simple-select3-sm').on('keyup', function(event) {
        if(event.keyCode == 13) {
            if(validate_enter_desc) {
                //SelectedItemToCart();
                validate_enter_desc = false;
            }
        }
    });

    $('.input_price').change(function(e) {
        var code = e.which;
        if(code!=13) {
            if(price_quote_info.selected_item != null) {
            
                price_quote_info.selected_item.price = roundToTwo($(this).val()/1.19);
                price_quote_info.selected_item.total = Math.round($(this).val()*price_quote_info.selected_item.qty);
            }
            updatePrices();
        } else {
            SelectedItemToCart();
        }
    });

    $('.input_qty').change(function(e) {
        var code = e.which;
        if(code!=13) {
            if(price_quote_info.selected_item != null) {
                price_quote_info.selected_item.qty   = $(this).val();
                price_quote_info.selected_item.total = Math.round($(this).val()*(price_quote_info.selected_item.price/1.19));
            }
            updatePrices();
        } else {
            SelectedItemToCart();
        }
    });

        
    });

    function SelectedItemToCart() {
    if(price_quote_info.selected_item != null) {
        let rownum = price_quote_info.cart_items.length +1;
        let search = false;
        for (x in price_quote_info.cart_items) {
            if (price_quote_info.cart_items[x].item_id == price_quote_info.selected_item.item_id) {
                search = true;
                price_quote_info.cart_items[x].qty = parseInt(price_quote_info.cart_items[x].qty) + parseInt(price_quote_info.selected_item.qty);
                price_quote_info.cart_items[x].total = Math.round(parseFloat(price_quote_info.cart_items[x].qty * price_quote_info.cart_items[x].price_discount));

                break; 
            }
        }
        if(!search) {
            price_quote_info.cart_items.push({
                code    : price_quote_info.selected_item.code, 
                qty     : price_quote_info.selected_item.qty,
                name    : price_quote_info.selected_item.name,
                price   : price_quote_info.selected_item.price,
                price_original : price_quote_info.selected_item.price_original,
                total   : Math.round(price_quote_info.selected_item.total),
                item_id : price_quote_info.selected_item.item_id, 
                discount : 0,
                withdraw : 'A',
                price_discount : price_quote_info.selected_item.price,
                stock : 0,
                block_discount: price_quote_info.selected_item.block_discount,
                row : rownum
            });
        }
        renderCart();
    }
    cleanSelected();
}


function getOrderNote() {

        var barcode = $("#show-order_note").val();
        let rownum = price_quote_info.cart_items.length +1;
        var options = [];
        var validator = true;
        let search = false;

        AWApi.get('{{ url('api/ordernotes/')}}/'+barcode, function(response){
            if (response.code == 200) {
                $("#show-associated_order_note > option").each(function() {
                    if (response.data.id == this.value){
                        validator = false;
                }
                });
                if (validator == true ){
                $.each( response.data.details, function( key, value ) {
                    if (value.discount_percent == null){
                        value.discount_percent = 0;
                    }
                    for (x in price_quote_info.cart_items) {
                        if (price_quote_info.cart_items[x].item_id == value.item_id) {
                            search = true;
                            price_quote_info.cart_items[x].qty = parseInt(price_quote_info.cart_items[x].qty) + parseInt(value.qty);
                            price_quote_info.cart_items[x].total = Math.round(parseFloat(price_quote_info.cart_items[x].qty * price_quote_info.cart_items[x].price_discount));
                            renderCart();
                            break; 
                        }else{
                            search = false;
                        }
                    }
                    if(!search) {

                        let discount_percent = value.discount_percent;
                        let total_discount = 0;
                        total_discount += Math.round(parseFloat(value.qty) * parseFloat(value.price/1.19));
                        total_discount -= Math.round((parseFloat(value.qty) * parseFloat(value.price/1.19))*((value.discount_percent)/100));
                        let price_discount = roundToTwo((parseFloat(value.price/1.19)) - roundToTwo(roundToTwo(parseFloat(value.price/1.19))*((value.discount_percent)/100)));
                        
                price_quote_info.cart_items.push({
                    order_note : response.data.id,
                    code    : value.manufacturer_sku, 
                    qty     : value.qty,
                    name    : value.name,
                    price_original : value.price,
                    price   : roundToTwo(value.price/1.19),
                    discount : value.discount_percent,
                    price_discount : price_discount,
                    total   : total_discount,
                    item_id : value.item_id, 
                    withdraw : 'A',
                    block_discount: value.block_discount,
                    stock : 0,
                    row : rownum
                });
            }
                renderCart();
                cleanSelected();
                rownum++;

            });     
                    var option ='';
                    if ($('#show-associated_order_note option').length != 0) {
                        option =  $('#show-associated_order_note').html();
                    }
                    option += "<option value="+response.data.id+" >ON0" + response.data.id + "</option>"
                    options.push(option);
                    $('#show-associated_order_note').html(options);
                }
            } else {
                $.toast({
                    heading: "Error buscando numero de pedido",
                    text: 'No existe numero de pedido solicitado',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: "info",
                    hideAfter: 6000,
                    stack: 6
                });
            }

            
        });
    }

    function getPriceQuote() {

        var barcode = $("#show-price_quote").val();
        let rownum = price_quote_info.cart_items.length +1;
        var options = [];
        var validator = true;
        let search = false;

        AWApi.get('{{ url('api/price_quote/')}}/'+barcode, function(response){
            if (response.code == 200) {
                $("#show-associated_order_note > option").each(function() {
                });
                
                $.each( response.data.details, function( key, value ) {
                    if (value.discount_percent == null){
                        value.discount_percent = 0;
                    }
                    for (x in price_quote_info.cart_items) {
                        if (price_quote_info.cart_items[x].item_id == value.item_id) {
                            search = true;
                            price_quote_info.cart_items[x].qty = parseInt(price_quote_info.cart_items[x].qty) + parseInt(value.qty);
                            price_quote_info.cart_items[x].total = Math.round(parseFloat(price_quote_info.cart_items[x].qty * price_quote_info.cart_items[x].price_discount));
                            renderCart();
                            break; 
                        }else{
                            search = false;
                        }
                    }
                    if(!search) {
                        let discount_percent = value.discount_percent;
                        let total_discount = 0;
                        total_discount += Math.round(parseFloat(value.qty) * parseFloat(value.price/1.19));
                        total_discount -= Math.round((parseFloat(value.qty) * parseFloat(value.price/1.19))*((value.discount_percent)/100));
                        let price_discount = roundToTwo((parseFloat(value.price/1.19)) - roundToTwo(roundToTwo(parseFloat(value.price/1.19))*((value.discount_percent)/100)));
                price_quote_info.cart_items.push({
                    order_note : response.data.id,
                    code    : value.manufacturer_sku, 
                    qty     : value.qty,
                    name    : value.name,
                    price_original : value.price,
                    price   : roundToTwo(value.price/1.19),
                    discount : value.discount_percent,
                    price_discount : price_discount,
                    total   : total_discount,
                    item_id : value.item_id, 
                    withdraw : 'A',
                    stock : 0,
                    block_discount: value.block_discount,
                    row : rownum
                });
            }
                renderCart();
                cleanSelected();
                rownum++;
            });     
                
            } else {
                $.toast({
                    heading: "Error buscando cotización",
                    text: 'No existe cotización solicitada',
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: "info",
                    hideAfter: 6000,
                    stack: 6
                });
            }

            

        });
    }
        

    function changeWd(row) {
        let storage = $('.'+row+'-wd').text();
        if (storage === "A") {
            storage = 'F';
        } else {
            storage = 'A';
        }
        for (x in price_quote_info.cart_items) {
            if(price_quote_info.cart_items[x].row == row) {
                price_quote_info.cart_items[x].withdraw = storage;
                $('.'+row+'-wd').text(storage);
                checkStock();
            }
        }

    }

    function deleteCartItem(row) {
        const cart_items = [];
        price_quote_info.cart_items.map(function(item) {
            if(item.row !== row) cart_items.push(item);
        });
        price_quote_info.cart_items = cart_items;
        renderCart();
    }

    function editRow(e, id, ths, type) {
        if (type == 'qty'){
        let code = e.code;
            for (x in price_quote_info.cart_items) {
                if(price_quote_info.cart_items[x].row == id) {
                    price_quote_info.cart_items[x].qty   = $(ths).val();
                    price_quote_info.cart_items[x].total = Math.round(price_quote_info.cart_items[x].qty*price_quote_info.cart_items[x].price_discount);
                    renderCart();
                    break;
                }
            }
        }

        if (type == 'discount'){
        let code = e.code;
            for (x in price_quote_info.cart_items) {
                if(price_quote_info.cart_items[x].row == id) {
                    price_quote_info.cart_items[x].discount   = $(ths).val();
                    price_quote_info.cart_items[x].price_discount = roundToTwo(price_quote_info.cart_items[x].price - (price_quote_info.cart_items[x].price*(price_quote_info.cart_items[x].discount/100)));
                    price_quote_info.cart_items[x].total = Math.round(price_quote_info.cart_items[x].qty*price_quote_info.cart_items[x].price_discount);
                    renderCart();
                    break;
                }
            }
        }
    }

    function renderCart() {
        $('.body-table').empty();
        let rows = '';
        // <td>${item.order_note}</td>   || agregar con orden de compra valido
        price_quote_info.cart_items.map(function(item) {
            if(item.block_discount == 1){
            rows += `<tr>
            <td>${item.row}</td>
            <td class='${item.item_id}-id'>${item.code}</td>
            
            <td class='${item.row}-stock'>${item.stock}</td>
            <td>${item.name}</td>
            <td><input onChange="editRow(event,${item.row}, this, 'qty')" type="number" class="form-control form-control-sm" style="width:100px" value="${item.qty}"/></td>
            <td style="text-align:right">$${formatMoney(item.price, 'CL')}</td>
            <td style="text-align:left">NO APLICA</td>
            <td style="text-align:right">$${formatMoney(item.price_discount, 'CL')}</td>
            <td style="text-align:right">$${formatMoney(item.total, 'CL')}</td>
            <td style="text-align:left"><button onclick="deleteCartItem(${item.row})" class="btn btn-sm btn-danger">Eliminar</button></td>
            </tr>`;
            }else{
            rows += `<tr>
            <td>${item.row}</td>
            <td class='${item.item_id}-id'>${item.code}</td>
            <td class='${item.row}-stock'>${item.stock}</td>
            <td>${item.name}</td>
            <td><input onChange="editRow(event,${item.row}, this, 'qty')" type="number" class="form-control form-control-sm" style="width:100px" value="${item.qty}"/></td>
            <td style="text-align:right">$${formatMoney(item.price, 'CL')}</td>
            <td style="text-align:left"><input onChange="editRow(event,${item.row}, this, 'discount')" type="number" class="form-control form-control-sm" style="width:100px" value="${item.discount}"/></td>
            <td style="text-align:right">$${formatMoney(item.price_discount, 'CL')}</td>
            <td style="text-align:right">$${formatMoney(item.total, 'CL')}</td>
            <td style="text-align:left"><button onclick="deleteCartItem(${item.row})" class="btn btn-sm btn-danger">Eliminar</button></td>
            </tr>`;
            }
        });
        $('.body-table').append(rows);
        renderPrice();
        checkStock();
    }

    function updatePrices() {
        if(price_quote_info.selected_item != null) {
            $('.input_qty').val(price_quote_info.selected_item.qty);
            $('.input_price').val('$ '+formatMoney(price_quote_info.selected_item.price, 'CL'));
            $('.input_total').val('$ '+formatMoney(price_quote_info.selected_item.total, 'CL'));
        } else {
            $('.input_qty').val('');
            $('.input_price').val('');
            $('.input_total').val('');
        }
    }

    function cleanSelected() {
        price_quote_info.selected_item = null;
        $('#select_code').val('');
        $('.simple-select3-sm').selectpicker('val', '');

        updatePrices();
    }

    function renderPrice() {
    let net = 0;
    price_quote_info.cart_items.map( function(item) {
        net += Math.round(item.total);
    });
    $('.show-net').empty();
    $('.show-net').html('$ '+formatMoney(Math.round(net), 'CL'));
    $('.show-tax').empty();
    $('.show-tax').html('$ '+formatMoney(Math.round(parseFloat(net*0.19)), 'CL'));
    $('.show-total').empty();
    $('.show-total').html('$ '+formatMoney(Math.round(parseFloat(net*1.19)), 'CL'));
    }

    function checkStock() {
        if(price_quote_info.cart_items.length > 0) {
            let fdata = new FormData();
            fdata.append('cart', JSON.stringify(price_quote_info.cart_items));
            AWApi.post('{{ url('/api/cart/stock') }}', fdata, function(response) {
                if(response.code == 200) {
                    response.data.map(function(item) {
                        for (x in price_quote_info.cart_items) {
                            if(price_quote_info.cart_items[x].row == item.row) {
                                price_quote_info.cart_items[x].stock = item.stock;
                                $('.'+item.row+'-stock').text(item.stock);
                                break;
                            }
                        }
                    });
                }
            });
        }
    }
    
    function applyDsc(){
        if(price_quote_info.cart_items.length > 0) {
            for (x in price_quote_info.cart_items) { 
                if (price_quote_info.cart_items[x].block_discount != 1){
                    price_quote_info.cart_items[x].discount   = $('#show-apply_dsc').val();
                    price_quote_info.cart_items[x].price_discount = roundToTwo(price_quote_info.cart_items[x].price - (price_quote_info.cart_items[x].price * (price_quote_info.cart_items[x].discount/100)));
                    price_quote_info.cart_items[x].total = Math.round(price_quote_info.cart_items[x].qty*price_quote_info.cart_items[x].price_discount);
                    renderCart();
                    
                }
            }
            $('#show-apply_dsc').val('');
        }
    }

    function cleanedItems(){
        price_quote_info.cart_items =[];
        $('.body-table').empty();
        $('#select_code').val('');
        $('.simple-select3-sm').selectpicker('val', '');
        $('.simple-select1-sm').selectpicker('val', '');
        $('.simple-select2-sm').selectpicker('val', '');
        $('#show-client_ress').val('');
        $('#show-order_note').val('');
        $('#show-apply_dsc').val('');
        $('#s_description').val('');
        $('#show-price_quote').val('');
        $('#show-associated_order_note').html('')

        renderPrice();
    }

    function printFile() {
        
        if (testing == 1){
        AWApi.get('{{ url('api/price_quote/print')}}/'+25+'/', function (response) {
                if(response.code == 200) {
                    window.open(response.data.url,"mywindow1");
                }
            });
        }else{
    if(price_quote_info.cart_items.length == 0) { 
        swal({
            title : 'Error',
            type  : 'error',
            text  : 'Debe agregar uno o más productos al carro'
        });
        return;
    }

    if(price_quote_info.client == null) { 
        swal({
            title : 'Error',
            type  : 'error',
            text  : 'Debe asignar un cliente'
        });
        return;
    }
    let fd = new FormData();
    if(price_quote_info.client != null) {
        fd.append('client_id', price_quote_info.client);
    }
    if($('#s_description').val() != '') {
        fd.append('price_quote_description', $('#s_description').val());
    }

    if($('#s_expiration').val() != '') {
        fd.append('price_quote_expiration', $('#s_expiration').val());
    }
    fd.append('cart', JSON.stringify(price_quote_info.cart_items));

    AWApi.post('{{ url('/api/price_quote') }}', fd, function(response) {
        if(response.code == 200) {


            AWApi.get('{{ url('api/price_quote/print')}}/'+response.data.id+'/', function (response) {
                if(response.code == 200) {
                    window.open(response.data.url,"mywindow1");
                }
            });
            cleanedItems();

            // AWApi.get('{{ url('/api/presale/print') }}/'+response.data.id, function(response) {
            //     if(response.code == 200) {
            //         printJS(response.data.url);
            //         presale_info.mode == 'ticket';
            //         $('.simple-select1-sm').selectpicker('val', '');
            //         $('.simple-select2-sm').selectpicker('val', '');
            //     }
            // });
        } else {
            swal({
                title : 'Error',
                type  : 'error',
                text  : response.data.error
            });
        }
    });
    }
}

function roundToTwo(num) {    
    return +(Math.round(num + "e+2")  + "e-2");
}
</script>
@endsection