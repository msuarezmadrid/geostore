<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" href="{{ asset("img/brand/favicon.png") }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>POS |</title>

    <!-- Styles -->
    <link href="{{ asset('poscss/bootstrap-glyphicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('poscss/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/font-awesome.min.css') }}" >
    <link rel="stylesheet" href="{{ asset('css/sweetalert.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/printjs/print.min.css') }}">
    <style>
    .modal-lg {
        max-width: 80% !important;
    }
    .collapsed {
        height: auto !important;; 
    }
    </style>
    @stack("extra-css")
    <link href="{{ asset('poscss/jquery.toast.css') }}" rel="stylesheet">
    <link href="{{ asset('poscss/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('plugins/datatables4/dataTables.bootstrap4.min.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet">
    <link hred="{{ asset('css/select2-bootstrap4.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-select/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/AdminLTE.min.css') }}">

    <!-- Scripts -->
    <script src="{{ asset('js/awconfig.js') }}"></script>
    <script src="{{ asset('js/autoNumeric.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables4/jquery.dataTables.min.js') }}" defer></script>
    <script src="{{ asset('plugins/datatables4/dataTables.bootstrap4.min.js') }}" defer></script>
    <script src="{{ asset('plugins/jQuery/jquery-3.2.1.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script src="{{ asset('plugins/printjs/print.min.js') }}"></script>
    <script src="{{ asset('plugins/printer/websocket-printer.js') }}"></script>

    
    <script type="text/javascript">
        AWConfig.setAccessToken('{{ Session::get('access_token')}}');
        window.Laravel = {!! json_encode(['csrfToken' => csrf_token(),]) !!};
    </script>
    <script src="{{ asset('js/api.js') }}"></script>
    <script src="{{ asset('js/utils.js') }}"></script>
    <style>
        body{
            background-color:#231F20
        }
        .modal-content{
            background-color:#231F20
        }
        .modal-title{
            color:#43ACB8
        }
        .seller_title {
            color:#A13840
        }
        .seller_title_1 { 
            color:white
        }
        .presale{
            padding-top:10px;
            padding-left:10px;
            width:98%;
        }
        .presale_input_1 {
            background-color:#214FAA;
            color:black;
            font-weight: bold;
        }
        .presale_input_1:focus {
            background-color:#214FAA;
            color:black;
            font-weight: bold;            
        }
        .presale_input_1:disabled {
            background-color:#214FAA;
            color:black;
            font-weight: bold;            
        }
        .label-presale {
            color:#43ACB8
        }
        .label_separator {
            float:left;
            margin-left:5px;
            margin-right:5px;
            margin-top:5px;
            color:white;
        }
        .client_button {
            background-color:#1DAFB0;
            font-weight: bold;
        }
        .document_button {
            background-color:#FF8B02;
            font-weight: bold;
        }
        .action_buttons {
            margin-top:10px;
            right:0;
            position: fixed;
            margin-right:10px;
        }
        .tool_button {
            background-color:#88CBF9;
        }
        .head-table {
            background-color:#E4E4F9;
            font-weight: bold;
        }
        .body-table {
            background-color:white;
            font-weight: bold;
        }
        .body-table>tr:hover {
            background-color:#FF8B02;
        }
        .show-total {
            background-color:#4581B3;
            width:200px;
            height:31px;
            color:white;
            font-weight: bold;
            text-align:center;
            font-size:20px
        }
        .content-header {
            color:white;
        }
        .box-solid{
            background-color:#231F20;
            color:white;
        }
        .box-footer{
            background-color:#231F20;
            color:white;
        }
        #dataDocument{
            color:white;
        }
    </style>
</head>
<section class="content-header">
    <h1>
      <i class="fa fa-cart-plus" style="padding-right: 5px;"></i> Preventa
    </h1>
  </section>
<div class="presale">

    <div clas="row">
        <div class="box box-primary box-xs flat">
            
        </div>
        <h5 class="seller_title">N/D</h5>  
    </div>
    <div class="row">
        <div class="col-md-3">
            <h5 class="seller_title_1">Vendedor: </h5>
            <input tabindex="1" type="number" style="width:80px" class="form-control sellerinput form-control-sm presale_input_1" onKeyUp="tabSeller(event, this, true, 0)" onChange="selectSeller(event, this)"; />
            <h5 class="seller_title_1">Producto: </h5>
            <h5 class="seller_title_1">Items: </h5>
        </div>
        <div class="col-md-9">
            <h5 class="seller_title_1">Cliente: </h5>
            <form>
                <div class="form-group row">
                    <label class="label-presale col-sm-1 col-form-label">R U T</label>
                    <div class="col-sm-3">
                        <select tabindex="-1" id="select_rut" class="form-control form-control-sm simple-select1-sm">
                            <option></option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <button tabindex="-1" type="button" class="btn btn-sm document_button" style="width:100%">
                        <i class="fa fa-file"></i>
                        BOLETA</button>
                    </div>
                    <div class="col-sm-4">
                    <button tabindex="-1" type="button" data-toggle="modal" data-target="#docs_modal" class="btn btn-sm client_button" style="width:100%">
                        <i class="fa fa-file"></i>
                        ORDENES ANTERIORES</button>
                    </div>
                    
                </div>
                <div class="form-group row">
                    <label class="label-presale col-sm-1 col-form-label">Cliente</label>
                    <div class="col-sm-11">
                        <select tabindex="-1" id="select_client" class="form-control form-control-sm simple-select2-sm">
                            <option></option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2">
            <label for="inputEmail4" class="label-presale">Codigo</label>
            <input tabindex="2" id="select_code" class="form-control form-control-sm" />
        </div>
        <div class="col-md-1">
            <label for="inputEmail4" class="label-presale">Cantidad</label>
            <input tabindex="3" type="text" class="input_qty form-control form-control-sm presale_input_1" id="inputEmail4" placeholder="">
        </div>
        <div class="col-md-6">
            <label for="inputEmail4" class="label-presale">Descripción</label>
            <select id="select_product_description" class="form-control form-control-sm simple-select3-sm">
                            <option></option>
            </select>
        </div>
        <div class="col-md-1">
            <label for="inputEmail4" class="label-presale">Valor</label>
            <input type="number" disabled class="input_price form-control form-control-sm presale_input_1" id="inputEmail4" placeholder="">
        </div>
        <div class="col-md-2">
            <label for="inputEmail4" class="label-presale">Total</label>
            <input type="text" disabled class="input_total form-control form-control-sm presale_input_1" id="inputEmail4" placeholder="">
        </div>
    </div>
    <div class="row" style="margin-top:10px">
            <div class="col-md-12">
                <button class="btn btn-sm tool_button float-right" onclick="addSelectedItemToCart()" id="add_item" >
                <i class="fa fa-plus-circle"></i> 
                AGREGAR</button>
                <button data-toggle="modal" data-target="#seeModal" class="btn btn-sm tool_button float-right" style="margin-right:10px"  >
                <i class="fa fa-edit"></i> 
                MODIFICAR</button>
                <button onclick="cleanSelected()" class="btn btn-sm tool_button float-right" style="margin-right:10px" >
                <i class="fa fa-eraser"></i>
                LIMPIAR</button>
            </div>
            
    </div>
    <div class="row" style="margin-top:10px">
        <table class="table table-responsive" style="margin-left:10px;margin-right:10px">
            <thead class="head-table">
                <tr>
                    <th>Codigo</th>
                    <th>Stock</th>
                    <th>Cantidad</th>
                    <th>Articulo</th>
                    <th>Precio</th>
                    <th>Total</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody class="body-table">
            </tbody>
        </table>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="float-right show-total">
                $0
            </div>
            <button class="btn btn-sm tool_button float-right" onclick="printFile()" style="margin-right:10px"  >
            <i class="fa fa-print"></i>
            IMPRIMIR</button>
            <button onclick="cleanCart()" class="btn btn-sm tool_button float-right" style="margin-right:10px"  >
            <i class="fa fa-eraser"></i> 
            LIMPIAR</button>
            <button onclick="event.preventDefault();sessionStorage.clear();document.getElementById('logout-form').submit();" class="btn btn-sm tool_button float-right" style="margin-right:10px"  >
            <i class="fa fa-close"></i> 
            CERRAR
            </button>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                      {{ csrf_field() }}
            </form>
        </div>
    </div>
</div>

<div id="seeModal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modificar Preventa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="label-presale col-sm-2 col-form-label">ID :</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control form-control-sm" id="searchValue" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-search btn-sm btn-primary">BUSCAR</button>
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">CERRAR</button>
            </div>
        </div>
    </div>    
</div>

<div id="myModal" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Cliente</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <div class="form-group row">
                <label class="label-presale col-sm-2 col-form-label">R.U.T :</label>
                <div class="col-sm-10">
                    <select id="select_rut_modal" class="form-control form-control-sm simple-select5-sm">
                        <option></option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="label-presale col-sm-2 col-form-label">Cliente:</label>
                <div class="col-sm-10">
                    <select id="select_pdesc_modal" class="form-control form-control-sm simple-select6-sm">
                        <option></option>
                    </select>
                </div>
            </div>
            <!--<div class="form-group row">
                <label class="label-presale col-sm-2 col-form-label">Giro:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control form-control-sm" />
                </div>
            </div>-->
            <div class="form-group row">
                <label class="label-presale col-sm-2 col-form-label">Dirección:</label>
                <div class="col-sm-10">
                    <input type="text" id="client_address" class="form-control form-control-sm" />
                </div>
            </div>
            <div class="form-group row">
                <label class="label-presale col-sm-2 col-form-label">Fono:</label>
                <div class="col-sm-4">
                    <input type="text" id="client_phone" class="form-control form-control-sm" />
                </div>
                <label class="label-presale col-sm-2 col-form-label">Correo:</label>
                <div class="col-sm-4">
                    <input type="text" id="client_mail" class="form-control form-control-sm" />
                </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-primary">MODIFICAR</button>
        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">CERRAR</button>
      </div>
    </div>
  </div>
</div>
<div id="docs_modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Documentos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-header">
            <div class="col-md-6 col-sm-6">
            <p></p>
            <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseFilter">
                <i class="fa fa-search"><br></i>
            </button>
            </div>
           </div>
            
            <div class="collapse" id="collapseFilter">
            <p></p>
            <div class="col-md-12">
            <div class="box box-primary box-solid">

              <div class="box-body" id="form">
                <div class="row">
                  <div class="col-md-6 col-sm-6">
                      <div class="form-group">
                          <label for="f_seller">Vendedor</label>
                          <select class="form-control" id="f_seller">
                          </select>
                      </div>
                  </div>

                  <div class="col-md-6 col-sm-6">
                      <div class="form-group">
                          <label for="f_folio">Numero de orden</label>
                          <input type="text" class="form-control" id="f_folio" placeholder="Folio">
                      </div>
                  </div>
                </div>
              
					<button type="button" id="clean" class="btn btn-default pull-right margin"> Limpiar </button>
					<button type="button" id="filter" class="btn btn-primary pull-right margin"> Filtrar </button>
              </div>    
            </div>
          </div>
            </div>

            <div class="modal-body">
                    <table id="dataDocument" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                <th>#</th>
                                <th>N° de orden</th>
                                <th>Vendedor</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                                </tr>
                            </thead>
                    </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('jspos/popper.min.js') }}"></script>
<script src="{{ asset('jspos/bootstrap.min.js') }}"></script>
<script src="{{ asset('jspos/jquery.slimscroll.js') }}"></script>
<script src="{{ asset('jspos/waves.js') }}"></script>
<script src="{{ asset('jspos/sticky-kit.min.js') }}"></script>
<script src="{{ asset('jspos/moment.min.js') }}"></script>
<script src="{{ asset('jspos/HelperFunctions.js') }}"></script>
<script src="{{ asset('jspos/jquery.toast.js') }}"></script>
<script src="{{ asset('jspos/jquery.printArea.js') }}"></script>
<script src="{{ asset('jspos/lodash.min.js') }}"></script>
<script src="//rawgithub.com/indrimuska/jquery-editable-select/master/dist/jquery-editable-select.min.js"></script>
<link href="//rawgithub.com/indrimuska/jquery-editable-select/master/dist/jquery-editable-select.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<!-- bootstrap datepicker -->
<script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-select/bootstrap-select.min.js') }}"></script>

<script>

var validate_enter      = false;
var validate_enter_desc = false;
var direct_print = 0;
var printService = new WebSocketPrinter({
            onUpdate: function (message) {
                swal.close();
                clearInterval(stopButton);
            },
        });

var presale_info = {
    seller: null,
    client: null,
    selected_item : null,
    cart_items : [],
    mode : 'ticket'
};

let defaultWithdraw = '{{ $defaultWithdraw }}';    
let stopButton;
let printCopies = 1;

setInterval('checkStock()', 10000);

function cleanCart() {
    presale_info.cart_items =[];
    $('.body-table').empty();
    renderPrice();
}

function disableProducts() {
    disablePrice();
    $('#select_code').attr('disabled', true);
    $(".sellerinput").attr("hasResponse", false);
    $('#select_product_description').attr('disabled', true);
    $('.simple-select3-sm').selectpicker('val', '');
    $('.simple-select3-sm').selectpicker('refresh');
    $('.input_qty').val('');
    $('.input_qty').attr('disabled', true);
    $('.input_price').val('');
    presale_info.selected_item = null;
    presale_info.cart_items    = [];
    presale_info.mode          = 'ticket';
    updatePrices();
    renderType();
    renderCart();
}

function enableProducts() {
    $('#select_code').attr('disabled', false);
    $('.simple-select4-sm').selectpicker('refresh');
    $('#select_product_description').attr('disabled', false);
    $('.simple-select3-sm').selectpicker('refresh');
    $('.input_qty').attr('disabled', false);
}

function disablePrice() {
    $('.input_price').attr('disabled', true);
}

function enablePrice() {
    $('.input_price').attr('disabled', false);

}

function checkStock() {
    if(presale_info.cart_items.length > 0) {
        let fdata = new FormData();
        fdata.append('cart', JSON.stringify(presale_info.cart_items));

        AWApi.post('{{ url('/api/cart/stock') }}', fdata, function(response) {
            if(response.code == 200) {
                response.data.map(function(item) {
                    for (x in presale_info.cart_items) {
                        if(presale_info.cart_items[x].row == item.row) {
                            presale_info.cart_items[x].stock = item.stock;
                            $('.'+item.row+'-stock').text(Math.round(item.stock*100)/100);
                            break;
                        }
                    }
                });
            }
        });
    }
}

function cleanSelected() {
    presale_info.selected_item = null;
    $('#select_code').val('');
    $('.simple-select3-sm').selectpicker('val', '');
    updatePrices();
}

function printFile() {
    if(presale_info.cart_items.length == 0) { 
        swal({
            title : 'Error',
            type  : 'error',
            text  : 'Debe agregar uno o más productos al carro'
        });
        return;
    }
    if(presale_info.seller == null) {
        swal({
            title : 'Error',
            type  : 'error',
            text  : 'Debe seleccionar un vendedor'
        });
        return;
    }
    let fd = new FormData();
    fd.append('seller', presale_info.seller.id);
    if(presale_info.client != null) {
        fd.append('client_id', presale_info.client);
    }
    fd.append('type', presale_info.mode);
    fd.append('cart', JSON.stringify(presale_info.cart_items));

    AWApi.post('{{ url('/api/ordernotes') }}', fd, function(response) {
        if(response.code == 200) {
            for(let i = 0; i < printCopies; i++) {
                AWApi.get('{{ url('/api/presale/print') }}/'+response.data.id+'/'+i, function(response) {
                    if(response.code == 200) {
                        if(direct_print == 0){
                            printJS(response.data.url);
                        }else if (direct_print == 1) {
                            clearInterval(stopButton);
                            try{
                                printService.submit({
                                        'type': 'INVOICE',
                                        'url': response.data.url
                                });
                                swal({
                                    title: "Impresión en curso",
                                    text: "Por favor espere. Si este proceso toma demasiado tiempo, Por favor, intentelo manualmente.",
                                    icon: "{{asset('img/pos/loading_spinner.gif')}}",
                                    showConfirmButton: false
                                });
                                stopButton = setTimeout(function(){
                                    swal.close();
                                }, 5000);
                            }catch(err) {
                                printJS(response.data.url);
                                swal('Problema','Hay un problema con el sistema de impresión automatica, se inició la impresión manual. \n Si el problema persiste, contacte al administrador','warning');
                            }
                        }
                        presale_info.mode == 'ticket';
                        renderType();
                        disableProducts();
                        $('.sellerinput').val('');
                        $('.seller_title').text('N/D');
                        $('.simple-select1-sm').selectpicker('val', '');
                        $('.simple-select2-sm').selectpicker('val', '');
                    }
                });
            }
        } else {
            swal({
                title : 'Error',
                type  : 'error',
                text  : response.data.error
            });
        }
    });
}

function updatePrices() {
    if(presale_info.selected_item != null) {
        $('.input_qty').val(presale_info.selected_item.qty);
        $('.input_price').val(presale_info.selected_item.price);
        $('.input_total').val('$'+formatMoney(presale_info.selected_item.total, 'CL'));
    } else {
        $('.input_qty').val('');
        $('.input_price').val('');
        $('.input_total').val('');
    }
}

function addSelectedItemToCart() {
    disablePrice();
    if(presale_info.selected_item != null) {
        let rownum = presale_info.cart_items.length +1;
        let search = false;
        for (x in presale_info.cart_items) {
            if (presale_info.cart_items[x].item_id == presale_info.selected_item.item_id && presale_info.selected_item.item_id != 0) {
                search = true;
                let cartItemQty = presale_info.cart_items[x].qty;
                let selectedQty = presale_info.selected_item.qty;
                if(presale_info.selected_item.unit_of_measure_id == 1) {
                    presale_info.cart_items[x].qty = Math.round(parseFloat(cartItemQty)) + Math.round(parseFloat(selectedQty));
                } else {
                    presale_info.cart_items[x].qty = parseFloat(cartItemQty) + parseFloat(selectedQty);
                }
                if(cartItemQty <= 0 || isNaN(parseFloat(cartItemQty)) || isNaN(parseFloat(selectedQty))) {
                    presale_info.cart_items[x].qty = 1;
                }
                presale_info.cart_items[x].total = cartItemQty * presale_info.cart_items[x].price
                break; 
            }
        }
        if(!search) {
            if(presale_info.selected_item.unit_of_measure_id == 1) {
                presale_info.selected_item.qty = Math.round(parseFloat(presale_info.selected_item.qty));
            } else {
                presale_info.selected_item.qty = parseFloat(presale_info.selected_item.qty);
            }
            if(presale_info.selected_item.qty <= 0 || isNaN(parseFloat(presale_info.selected_item.qty))) {
                presale_info.selected_item.qty = 1;
            }
            presale_info.cart_items.push({
                code    : presale_info.selected_item.code, 
                qty     : presale_info.selected_item.qty,
                name    : presale_info.selected_item.name,
                price   : presale_info.selected_item.price,
                total   : presale_info.selected_item.qty * presale_info.selected_item.price,
                item_id : presale_info.selected_item.item_id, 
                unit_of_measure_id: presale_info.selected_item.unit_of_measure_id,
                withdraw : defaultWithdraw,
                stock : 0,
                row : rownum
            });
        }
        renderCart();
    }
    cleanSelected();
}

function changeWd(row) {
    let storage = $('.'+row+'-wd').text();
    if (storage === "A") {
        storage = 'F';
    } else {
        storage = defaultWithdraw;
    }
    for (x in presale_info.cart_items) {
        if(presale_info.cart_items[x].row == row) {
            presale_info.cart_items[x].withdraw = storage;
            $('.'+row+'-wd').text(storage);
            checkStock();
        }
    }

}

function deleteCartItem(row) {
    const cart_items = [];
    presale_info.cart_items.map(function(item) {
        if(item.row !== row) cart_items.push(item);
    });
    presale_info.cart_items = cart_items;
    renderCart();
}

function editRow(e, id, ths) {
    let code = e.keyCode;
    if(code == 13) {
        for (x in presale_info.cart_items) {
            if(presale_info.cart_items[x].row == id) {
                if(presale_info.cart_items[x].unit_of_measure_id != 1) {
                    presale_info.cart_items[x].qty = parseFloat($(ths).val());
                } else {
                    presale_info.cart_items[x].qty = Math.round(parseFloat($(ths).val()));
                }
                if(presale_info.cart_items[x].qty <= 0 || isNaN(parseFloat($(ths).val()))) {
                    presale_info.cart_items[x].qty = 1;
                }
                presale_info.cart_items[x].total = Math.round(presale_info.cart_items[x].qty*presale_info.cart_items[x].price);
                renderCart();
                break;
            }
        }
    }
}

function renderCart() {
    $('.body-table').empty();
    let rows = '';
    presale_info.cart_items.map(function(item) {
        rows += `<tr>
            <td>${item.code}</td>
            <td class='${item.row}-stock'>${item.stock}</td>
            <td><input onkeyup="editRow(event,${item.row}, this)" type="number" class="form-control form-control-sm" style="width:100px" value="${item.qty}"/></td>
            <td>${item.name}</td>
            <td style="text-align:right">$${formatMoney(item.price, 'CL')}</td>
            <td style="text-align:right">$${formatMoney(item.total, 'CL')}</td>
            <td style="text-align:center"><button onclick="deleteCartItem(${item.row})" class="btn btn-sm btn-danger">Eliminar</button></td>
        </tr>`;
    });
    $('.body-table').append(rows);
    renderPrice();
    checkStock();
}

function renderPrice() {
    let total = 0;
    presale_info.cart_items.map( function(item) {
        total += Math.round(item.price*item.qty);
    });
    $('.show-total').empty();
    $('.show-total').html('$'+formatMoney(total, 'CL'));
}

function renderType() {
    if(presale_info.mode == 'ticket') {
        $('.document_button').html('<i class="fa fa-file"></i> BOLETA');
    } else {
        $('.document_button').html('<i class="fa fa-file"></i> FACTURA');
    }
}

function getSellerInfo(seller) {
    AWApi.get('{{ url('/api/sellers') }}/'+seller, function(response) {
        if (response.code == 200) {
            presale_info.seller = response.data;
            $('.seller_title').html(response.data.name);
            $(".sellerinput").attr("hasResponse", true);
            enableProducts();
        } else {
            presale_info.seller = null;
            disableProducts();
            $(".sellerinput").attr("hasResponse", true);
            $('.seller_title').html('N/D');
        }
    });
}

function selectSeller(e, ths) {
        if($(ths).val().length >= 1) {
            disableProducts();
            getSellerInfo($(ths).val());
        } else {
            disableProducts();
            $('.seller_title').html('N/D');
        }
          
    }
function tabSeller(e, ths, first, iter) {

    //Keycode 9 = tab
    if(e.keyCode == 9) {
        if(first) {
            selectSeller(e, ths);
        }
        //2 secs, iteration override
        if(iter < 20) {
            if($('#select_code').attr("disabled") === 'disabled' && $(".sellerinput").attr("hasResponse") !== 'true') {
                setTimeout(() => {
                    tabSeller(e, ths, false, iter++);
                }, 100);
            } else {
                $("#select_code").focus();
            }
        }
    }
}


$(document).ready(function() {
    $(".sellerinput").val(0);

    $(".sellerinput").keydown(function(e) {
        if(e.keyCode == 9) { 
            e.preventDefault(); 
        }
    });


    AWApi.get('{{ url('/api/sellers' ) }}', function (response) {
                    $("#f_seller").empty();
                    $('<option />', {value: "", text: "Ninguno" }).appendTo($("#f_seller"));
                    for (var i = 0; i < response.data.rows.length; i++) {
                        
                        $('<option />', {value: response.data.rows[i].id, text:response.data.rows[i].name }).appendTo($("#f_seller"));
                        
                    }
             });

    AWApi.get('{{ url('/api/configs/params') }}/LIGHT_THEME', function (response) {
            if (response.code == 200){
                if(response.data.value == 1){
                    $('body').css("background-color","#ecf0f5");
                    $('.modal-content').css("background-color","#ecf0f5");
                    $('.presale_input_1').css("background-color","#e9ecef");
                    $('.tool_button').css("background-color","#3c8dbc");
                    $('.tool_button').css("color","white");
                    $('.tool_button').css("font-size","14px");
                    $('.tool_button').css("font-weight","400");
                    $('.tool_button').css("font-family","inherit");
                    $('.content-header').css("color","black")
                    $('.show-total').css("background-color","#e9ecef");
                    $('.document_button').css("background-color","#00a65a");
                    $('.document_button').css("font-size","12px");
                    $('.document_button').css("color","white");
                    $('.client_button').css("font-size","12px");
                    $('.client_button').css("color","white");
                    $('.client_button').css("background-color","#00a65a");
                    $('.label-presale').css("color","#495057");
                    $('.show-total').css("color","black");
                    $('.seller_title_1').css("color","black");
                    $('.form-control').css("font-weight","bold");
                    $('.label-presale').css("font-weight","bold");
                    $('.bootstrap-select').css("font-weight","bold");
                    $('.filter-option-inner-inner').css("font-weight","bold");
                    $('.box-solid').css("background-color","#ecf0f5");
                    $('.box-solid').css("color","black");
                    $('.box-footer').css("background-color","#ecf0f5");
                    $('.box-footer').css("color","black");
                    $('#dataDocument').css("color","black");

                }
            }
        });
    AWApi.get('{{ url('/api/configs/params') }}/DIRECT_PRINT', function (response) {
            if (response.code == 200){
                if(response.data.value == 1){
                    direct_print = 1;
                }else{
                    direct_print = 0;
                }
            }
        });
        
    AWApi.get('{{ url('/api/configs/params') }}/VOUCHER_MULTIPRINT', function (response) {
            if (response.code && response.code == 200){
                if(response.data && response.data.value && response.data.value != ""){
                    let printText = response.data.value;
                    printCopies = Math.max(1, printText.trim().split(';').length);
                }
            }
        });

        tableDocuments = $('#dataDocument').DataTable( {
                "language": {
                    "url": "{{ asset('json/Spanish.json') }}"
                },
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "lengthChange": false,
                "searching": false,
                "ajax": function (data, callback, settings) {

                    var filters = new Object();
                    
                    filters.order_id           = $('#f_folio').val();
                    filters.seller_id            = $('#f_seller').val();


                    data.filters = filters;

                    

                    AWApi.get('{{ url('/api/ordernotes' ) }}?' + $.param(data), function (response) {
                        callback({
                            recordsTotal: response.data.recordsTotal,
                            recordsFiltered: response.data.recordsFiltered,
                            data: response.data.orderNotes
                        });
                    });
                },
                "paging": true,
                "columns": [
                    { "data": "id", "visible": false,},
                    { "data": "id"},
                    { "data": "id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            return full.seller.name;
                        }
                    },
                    { "data": "created_at",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            date = moment(data).format("DD/MM/YYYY");
                            return date;
                        }
                    },                   
                    { 
                        "data": "id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {

                            var viewVoucher = "<button class='btn btn-danger btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=callVoucher("+full.id+");>";
                            viewVoucher += "<i class='fa fa-print'></i> </button>";

                            return "<div class='btn-group'>"+viewVoucher+"</div>";
                        }
                    }
                ]
            });

        

    $('.sellerinput').focus();
    renderType();

    $('.document_button').on('click', function() {
        if (presale_info.mode == 'ticket') {
            presale_info.mode = 'bill';
            renderType();
        } else {
            presale_info.mode = 'ticket';
            renderType();
        }
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
    $('.simple-select3-sm').selectpicker({
        liveSearch:true,
        noneSelectedText: 'Seleccione un item',
        noneResultsText: '',
        selectOnTab: true
    });
    $('.simple-select5-sm').selectpicker({
        liveSearch:true,
        noneSelectedText: 'Seleccione un rut',
        noneResultsText: ''
    });
    $('.simple-select6-sm').selectpicker({
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
        disablePrice();
        if($(this).val() != '' && $(this).val() != 'null' && $(this).val() != 0) {
            AWApi.get('{{ url('/api/items/') }}/'+$(this).val(), function(response) {
                if (response.code == 200) {
                    presale_info.selected_item ={
                        item_id : response.data.item.id,
                        name : response.data.item.name,
                        price : 0,
                        qty : 1,
                        total : 0,
                        code : response.data.item.manufacturer_sku,
                        unit_of_measure_id: response.data.item.unit_of_measure_id
                    };
                    response.data.prices.map(function(price) {
                        if(price.item_active == "1") {
                            presale_info.selected_item.price = price.price;
                            presale_info.selected_item.total = price.price;
                        }
                    });
                    $('#add_item').focus();
                    $('#select_code').val(presale_info.selected_item.code);
                    updatePrices();
                }
            });
        } else if ($(this).val() == 0) {
            //ITEM QUE NO PERTENECE A BODEGA
            let pname = $(this).find('option:selected').text()
            let oname = pname.replace('NO STOCK $0','');
            if(oname.trim() !== "") {
                presale_info.selected_item ={
                            item_id : 0,
                            name : oname,
                            price : 0,
                            qty : 1,
                            total : 0,
                            code : '',
                            unit_of_measure_id: 1
                };
                $('#select_code').val('');
                enablePrice();
                updatePrices();
            }
        }else {
            $('#select_code').val('');
            presale_info.selected_item = null;
            updatePrices();
        }
    });
    $('#select_rut').on('change', function() {
        if($(this).val() != '' && $(this).val() != 'null') {
            AWApi.get('{{ url('/api/clients/') }}/'+$(this).val(), function(response) {
                 if(response.code == 200) {
                    $('.simple-select2-sm').find('option').remove();
                    presale_info.client = response.data.id;
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
                 }
            });
        } else {
            $('.simple-select2-sm').selectpicker('val', '');
            presale_info.client = null;
        }
    });
    $("#select_client").on('change', function() {
        if($(this).val() != '' && $(this).val() != 'null') {
            AWApi.get('{{ url('/api/clients/') }}/'+$(this).val(), function(response) {
                if(response.code == 200) {
                    $('.simple-select1-sm').find('option').remove();
                    presale_info.client = response.data.id;
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
                }
            });
        } else {
            $('.simple-select1-sm').selectpicker('val', '');
            presale_info.client = null;
        }
    });

    $('#select_code').keyup(function(e) {
        var code = e.which;
        if(code == 13) {
            AWApi.get('{{ url('/api/items/search') }}?type=sku&value='+$(this).val(), function(response) {
                if (response.code == 200) {
                    if(response.data != null) {
                        AWApi.get('{{ url('/api/items/') }}/'+response.data.id, function(response) {
                            if (response.code == 200) {
                                presale_info.selected_item ={
                                    item_id : response.data.item.id,
                                    name : response.data.item.name,
                                    price : 0,
                                    qty : 1,
                                    total : 0,
                                    code : response.data.item.manufacturer_sku,
                                    unit_of_measure_id: response.data.item.unit_of_measure_id
                                };
                                response.data.prices.map(function(price) {
                                    if(price.item_active == "1") {
                                        presale_info.selected_item.price = price.price;
                                        presale_info.selected_item.total = price.price;
                                    }
                                });
                                $('.simple-select3-sm').find('option').remove();
                                src = [
                                    { id: null, txt:"" }, 
                                    { id: presale_info.selected_item.item_id, 
                                    txt: presale_info.selected_item.name}];
                                    var options = [];
                                var options = [];
                                src.forEach(function (item) {
                                    var option = "<option value="+item.id+" >" + item.txt + "</option>"
                                    options.push(option);
                                });
                                $('#select_product_description').html(options);
                                $('.simple-select3-sm').selectpicker('refresh');
                                $('.simple-select3-sm').selectpicker('val', presale_info.selected_item.item_id);
                                updatePrices();
                                validate_enter_desc = true;
                                $('#add_item').focus();
                            }
                            
                        }); 
                    }
                }
            });
        }
    });

    $('.simple-select3-sm').on('keyup', function(event) {
        if(event.keyCode == 13) {
            if(validate_enter_desc) {
                //addSelectedItemToCart();
                validate_enter_desc = false;
            }
        }
    });

    $('.input_price').keyup(function(e) {
        var code = e.which;
        if(code!=13) {
            if(presale_info.selected_item != null) {
                presale_info.selected_item.price = $(this).val();
                presale_info.selected_item.total = Math.round($(this).val()*presale_info.selected_item.qty);
            }
            updatePrices();
        } else {
            addSelectedItemToCart();
        }
    });

    $('.input_qty').keyup(function(e) {
        var code = e.which;
        if(code!=13) {
            if(presale_info.selected_item != null) {
                presale_info.selected_item.qty   = $(this).val();
                presale_info.selected_item.total = Math.round($(this).val()*presale_info.selected_item.price);
            }
            updatePrices();
        } else {
            addSelectedItemToCart();
        }
    });

    $('.simple-select5-sm').find('.bs-searchbox').find('input').on('keyup', function(event) {
        if(event.keyCode != 38 && event.keyCode != 40) {
            $('.simple-select5-sm').find('option').remove();
            $('.simple-select5-sm').selectpicker('refresh');
            AWApi.get('{{ url('/api/clients/search/') }}/rut/'+$(this).val(), function(response) {
                if(response.code == 200) {
                    src = [{
                        id: null, txt:""
                    }];
                    response.data.map( (client) => {
                        src.push({
                            id:client.id,
                            txt: (client.rut + '-' + client.rut_dv)
                        });
                    });
                    var options = [];
                    src.forEach(function (item) {
                        var option = "<option value="+item.id+" >" + item.txt + "</option>"
                        options.push(option);
                    });
                    $('#select_rut_modal').html(options);
                    $('.simple-select5-sm').selectpicker('refresh');
                }
            });
        }
    });

    $('.simple-select6-sm').find('.bs-searchbox').find('input').on('keyup', function(event) {
        if(event.keyCode != 38 && event.keyCode != 40) {
            $('.simple-select6-sm').find('option').remove();
            $('.simple-select6-sm').selectpicker('refresh');
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
                    $('#select_pdesc_modal').html(options);
                    $('.simple-select6-sm').selectpicker('refresh');
                }
            });
        }
    });

    $("#select_pdesc_modal").on('change', function() {
        if($(this).val() != '' && $(this).val() != 'null') {
            AWApi.get('{{ url('/api/clients/') }}/'+$(this).val(), function(response) {
                if(response.code == 200) {
                    $('.simple-select5-sm').find('option').remove();
                    src = [
                        { id: null, txt:"" }, 
                        { id: response.data.id, 
                          txt: (response.data.rut + '-' + response.data.rut_dv)}];
                    var options = [];
                    src.forEach(function (item) {
                        var option = "<option value="+item.id+" >" + item.txt + "</option>"
                        options.push(option);
                    });
                    $('#select_rut_modal').html(options);
                    $('.simple-select5-sm').selectpicker('refresh');
                    $('.simple-select5-sm').selectpicker('val', response.data.id);   
                    $('#client_address').val(response.data.address);
                    $('#client_mail').val(response.data.email);
                    $('#client_phone').val(response.data.phone);    
                }
            });
        } else {
            $('.simple-select5-sm').selectpicker('val', '');
        }
    });

    $("#select_rut_modal").on('change', function() {
        if($(this).val() != '' && $(this).val() != 'null') {
            AWApi.get('{{ url('/api/clients/') }}/'+$(this).val(), function(response) {
                if(response.code == 200) {
                    $('.simple-select6-sm').find('option').remove();
                    src = [
                        { id: null, txt:"" }, 
                        { id: response.data.id, 
                          txt: response.data.name}];
                    var options = [];
                    src.forEach(function (item) {
                        var option = "<option value="+item.id+" >" + item.txt + "</option>"
                        options.push(option);
                    });
                    $('#select_pdesc_modal').html(options);
                    $('.simple-select6-sm').selectpicker('refresh');
                    $('.simple-select6-sm').selectpicker('val', response.data.id);   
                    $('#client_address').val(response.data.address);
                    $('#client_mail').val(response.data.email);
                    $('#client_phone').val(response.data.phone);    
                }
            });
        } else {
            $('.simple-select5-sm').selectpicker('val', '');
        }
    });

    disableProducts();

    $('#myModal').on('shown.bs.modal', function () {
        $('#select_rut_modal').trigger('focus');
        $('#client_address').val('');
        $('#client_mail').val('');
        $('#client_phone').val('');
        $('.simple-select5-sm').selectpicker('val', '');
        $('.simple-select6-sm').selectpicker('val', '');
    });

    $('#seeModal').on('shown.bs.modal', function () {
        $('#searchValue').val('');
    });

    $(".btn-search").click(function() {
        AWApi.get('{{ url('/api/ordernotes/') }}/'+$('#searchValue').val(), function(response) {
            if(response.code == 200) {
                //MAGIC
                presale_info.cart_items = [];
                let details = response.data.details;
                let rownum  = 1;

                details.map(function(detail) {
                    presale_info.cart_items.push({
                        row: rownum,
                        qty: detail.qty,
                        price: detail.price,
                        total: Math.round(detail.qty*detail.price),
                        item_id: detail.item_id,
                        withdraw: detail.withdraw,
                        stock: 0,
                        name: detail.name,
                        code: detail.manufacturer_sku
                    });
                    rownum++
                });
                $('.sellerinput').val(response.data.seller_id);
                getSellerInfo(response.data.seller_id);
                renderCart();
                if (response.data.client_id != null) {
                    AWApi.get('{{ url('/api/clients/') }}/'+response.data.client_id, function(res) {
                        if(res.code == 200) {
                            $('.simple-select1-sm').find('option').remove();
                            $('.simple-select2-sm').find('option').remove();
                            presale_info.client = res.data.id;
                            src = [
                                { id: null, txt:"" }, 
                                { id: res.data.id, 
                                txt: (res.data.rut + '-' + res.data.rut_dv)}];
                            var options = [];
                            src.forEach(function (item) {
                                var option = "<option value="+item.id+" >" + item.txt + "</option>"
                                options.push(option);
                            });
                            $('#select_rut').html(options);
                            $('.simple-select1-sm').selectpicker('refresh');
                            $('.simple-select1-sm').selectpicker('val', res.data.id);  
                            src = [
                                { id: null, txt:"" }, 
                                { id: res.data.id, 
                                txt: res.data.name}];
                            options = [];
                            src.forEach(function (item) {
                                var option = "<option value="+item.id+" >" + item.txt + "</option>"
                                options.push(option);
                            });
                            $('#select_client').html(options);
                            $('.simple-select2-sm').selectpicker('refresh');
                            $('.simple-select2-sm').selectpicker('val', res.data.id);
                        }
                    });
                }
            }
        });

        $('#seeModal').modal('hide');
        
    });


});

$('#filter').click(function(){
        tableDocuments.ajax.reload();
    });

function callVoucher(folio){
       
    AWApi.get('{{ url('/api/presale/print') }}/'+folio+'/0', function(response) {
                if(response.code == 200) {
                    printJS(response.data.url);
                }
            });

   }
</script>