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
    <link href="{{ asset('plugins/printjs/print.min.css') }}" rel="stylesheet">
    <style>
    .modal-lg {
        max-width: 80% !important;
    }

    .modal-md {
        max-width: 50% !important;
    }

    .collapsed {
        height: auto !important;; 
    }
    </style>
    @stack("extra-css")
    <link href="{{ asset('poscss/jquery.toast.css') }}" rel="stylesheet">
    <link href="{{ asset('poscss/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('plugins/datatables4/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-select/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <!-- Scripts -->
    <script src="{{ asset('js/awconfig.js') }}"></script>
    <script type="text/javascript">
        sessionStorage.clear();
        AWConfig.setAccessToken('{{ Session::get('access_token')}}');
        window.Laravel = {!! json_encode(['csrfToken' => csrf_token(),]) !!};
        var AFE_URL     = '{!! config("afe.url") !!}';
        var AFE_URL_API = '{!! config("afe.url_afe") !!}';
        var API_TOKEN   = '{!! config("afe.afe_token") !!}';
        var COMPANY_RUT = '{!! config("afe.company_rut") !!}';
        var USER_RUT    = '{!! config("afe.user_rut") !!}';
    </script>
    <script src="{{ asset('js/api.js') }}"></script>
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('js/autoNumeric.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables4/jquery.dataTables.min.js') }}" defer></script>
    <script src="{{ asset('plugins/printer/websocket-printer.js') }}"></script>
    <script src="{{ asset('plugins/datatables4/dataTables.bootstrap4.min.js') }}" defer></script>

</head>

<div id="myModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Agregar Cliente</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="email">RUT</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control client_id" placeholder="RUT Cliente">
                    <div class="invalid-feedback error-client-id">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="email">Razón Cliente</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control client_name" placeholder="Razón Social">
                    <div class="invalid-feedback error-client-name">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="email">Email</label>
                <div class="col-sm-9">
                    <input type="email" class="form-control client_email" placeholder="Email">
                    <div class="invalid-feedback error-client-email">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="email">Dirección</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control client_address" placeholder="Dirección">
                    <div class="invalid-feedback error-client_address">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="comune">Comuna</label>
                <div class="col-sm-9">
                <select class="form-control client_comune" placeholder="Comuna" id="s_comune">
                </select>
                <div class="invalid-feedback error-client-comune">
                    </div>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="industries">Giro</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control client_industries" placeholder="Giro">
                    <div class="invalid-feedback error-client-industries">
                    </div>
                </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger add-client">Guardar</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<!--
SHOW MODAL
-->
<div id="order_note_observations" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Observaciones nota de pedido</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                    <table class="display nowrap table table-responsive table-bordered table-hover" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Cant. Solicitada</th>
                                    <th>Stock Disponible</th>
                                </tr>
                            </thead>
                            <tbody id="datacells">
                            </tbody>
                    </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>


<!--
SHOW MODAL
-->
<div id="box_movements" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Movimientos Caja</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                    <table id="datas" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tipo</th>
                                    <th>Monto</th>
                                    <th>N.Factura/ Boleta</th>
                                    <th>Observaciones</th>
                                    <th>Fecha</th>
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


<!--
SHOW MODAL
-->

<div id="sale_box_cash_balance" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar / Retirar Efectivo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            <div class="form-group row" style="padding-bottom: 5px;">
                <div class="col-sm-6" >
                    <label for="cash_balance"> Monto para agregar o retirar </label>
                </div>
                <div class="col-sm-6" >
                    <input type="number" class="form-control" id="cash_balance">
                </div>
                <div class="col-sm-6" >
                    <label for="balance_observation"> Observacion </label>
                </div>
                <div class="col-sm-6" >
                    <input type="text" class="form-control" id="balance_observation">
                </div>
            </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="cash_balance_box_sale" data-dismiss="modal">Guardar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>            
            </div>
        </div>
    </div>
</div>


<div id="documents" class="modal fade" tabindex="-1" role="dialog">
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
                <i class="fas fa-search"><br></i>
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
                          <label for="f_type">Tipo de Documento</label>
                          <select class="form-control" id="f_type">
                          <option value="0" selected>Ambos </option>
                          <option value="1">Boleta </option>
                          <option value="2">Factura </option>
                          </select>
                      </div>
                  </div>

                  <div class="col-md-6 col-sm-6">
                      <div class="form-group">
                          <label for="f_folio">Folio</label>
                          <input type="text" class="form-control" id="f_folio" placeholder="Folio">
                      </div>
                  </div>
                </div>
              </div>  
              <div class="box-footer">
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
                                <th>Folio</th>
                                <th>Tipo de Documento</th>
                                <th>Fecha</th>
                                <th>Neto</th>
                                <th>IVA</th>
                                <th>Total</th>
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


<!--
SHOW MODAL
-->


<div id="references_modal" class="modal fade" tabindex="-1" role="dialog">
    



    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Referencias</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
           

            <div class="modal-body">
                    <div class="panel box box-primary">
                    <div class="box-header with-border">
                    <h4 class="box-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                        Agregar Referencias
                    </a>
                    </h4>
                    </div>
                <div id="collapseOne" class="panel-collapse collapse in show" role="tabpanel" aria-labelledby="headingOne">
                    <div class="box-body">
                        <div class="row">
                            <div class="form-group col-md-2">
                                <label for="i_folio">Folio</label>
                                <input type="text" class="form-control" id="i_folio" placeholder="Folio">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="i_razon_referencia">Razón Referencia</label>
                                <input type="text" class="form-control" id="i_razon_referencia" placeholder="Razón Referencia">
                            </div>
                            
                            <div class="form-group col-md-3">
                                <label for="i_doc_type">Tipo documento</label>
                                <select class="form-control" id="i_doc_type" >
                                    <option value="0">801: Orden de Compra</option>
                                    <option value="1">50: Guía de Despacho</option>
                                    <option value="2">52: Guía de Despacho Electrónica</option>
                                    <option value="3">HES: HES</option>
                                    <option value="4">802: Nota de Pedido</option>                                
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="i_date">Fecha</label>
                                <input type="text" class="form-control" id="i_date" placeholder="Fecha">
                            </div>
                            <div class="col-md-1">
                            <p></p>
                            <button type="button" class="btn" id="addReference"><i class="fas fa-plus" style="color:green"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                </div>

                <div class="panel box box-primary">
                <div class="box-header with-border">
                  <h4 class="box-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                      Referencias Agregadas
                    </a>
                  </h4>
                </div>
                <div id="collapseTwo" class="panel-collapse collapse in show">
                  <div class="box-body">

                  <table id="dataReferences" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%">
                
                            <thead>
                                <tr>
                                <th>Folio</th>
                                <th>Razón Referencial</th>
                                <th>Tipo de Documento</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                                </tr>

                            </thead>
                            <tbody>
                            </tbody>
                            
                    </table>
                 
                  
                      
                  
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onClick="saveReferences();" data-dismiss="modal">Guardar</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>

            </div>
        </div>
    </div>
</div>

<!--
SHOW MODAL
-->
<div id="box_sales" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Apertura de caja</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <div class="form-group row" style="padding-bottom: 5px;">
                <div class="col-sm-6" >
                            <label for="start_date"> Fecha : </label>
                            <input type="date" disabled class="form-control" id="start_date" placeholder="Fecha Inicio">
                </div>
                <div class="col-sm-6" >
                            <label for="start_hours"> Hora : </label>
                            <input type="time" disabled class="form-control" id="start_hours" placeholder="Hora Inicio">
                </div>
                <div class="col-sm-6" >
                            <label for="branch_office_id"> Sucursal: </label>
                            <select id="branch_office_id" class="form-control"></select> 
                </div>
                <div class="col-sm-6" >
                            <label for="box_sales_id"> Caja: </label>
                            <select id="box_sales_id" class="form-control"></select>  
                </div>
                <div class="col-sm-12" >
                            <label for="balance"> Saldo Inicial: </label>
                            <input type="text" id="balance"  class="form-control">
                </div>
                <!--<div class="col-sm-6" >
                            <label for="cashier_id"> Cajero: </label>
                            <select id="cashier_id" class="form-control"></select>  
                </div>-->
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" id ="open_box_sale" class="btn btn-success">Abrir</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>

<!--
SHOW MODAL CLOSE BOX SALES
-->

<div id="box_sales_close" class="modal fade" tabindex="-1" role="dialog">
@if($tipoCaja->value == "0")
    <div class="modal-dialog" role="document">
@elseif($tipoCaja->value == "1")
    <div class="modal-dialog modal-md" role="document">
@endif
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Cierre de caja</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <div class="form-group row" style="padding-bottom: 5px;">
                @if($tipoCaja->value == "0")
                <div class="col-sm-6" >
                @elseif($tipoCaja->value == "1")
                <div class="col-sm-4" >
                @endif
                            <label for="end_date"> Fecha : </label>
                            <input type="date" disabled class="form-control" id="end_date" placeholder="Fecha Inicio">
                </div>
                @if($tipoCaja->value == "0")
                <div class="col-sm-6" >
                @elseif($tipoCaja->value == "1")
                <div class="col-sm-4" >
                @endif
                            <label for="end_hours"> Hora : </label>
                            <input type="time" disabled class="form-control" id="end_hours" placeholder="Hora Inicio">
                </div>
                @if($tipoCaja->value == "0")
                <div class="col-sm-6" >
                @elseif($tipoCaja->value == "1")
                <div class="col-sm-4" >
                @endif
                            <label for="closed_box_name"> Caja: </label>
                            <input type="text" class="form-control" disabled id="closed_box_name">
                </div>
                @if($tipoCaja->value == "0")
                <div class="col-sm-6" >
                            <label for="real_cash"> Saldo real efectivo caja: </label>
                    <input type="number" class="form-control" id="real_cash">
                </div>
                @endif
                <!--<div class="col-sm-6" >
                            <label for="cashier_id"> Cajero: </label>
                            <select id="cashier_id" class="form-control"></select>  
                </div>-->
                @if($tipoCaja->value == "0")
                <div class="col-sm-12">
                @elseif($tipoCaja->value == "1")
                <div class="col-sm-6">
                @endif
                    <label for="final_fund"> Total por tipos de documento: </label>
                    <table id="final_fund">
                        <tr>
                            <th>Tipo</th>
                            <th>Importe</th>
                        </tr>
                        <tr>
                            <td id="t_total_box">Saldo caja</td>
                            <td id="total_box" class="text-right"></td>
                        </tr>
                        <tr>
                            <td id="t_total_ticket">Total Ventas con Boletas</td>
                            <td id="total_ticket" class="text-right"></td>
                        </tr>
                        <tr>
                            <td id="t_total_invoice">Total Ventas con Facturas</td>
                            <td id="total_invoice" class="text-right"></td>
                        </tr>
                    </table>
                    <label for="final_fund"> Total Por Forma de Pago: </label>
                    <table id="final_fund">
                        <tr>
                            <th>Tipo</th>
                            <th>Importe</th>
                        </tr>
                        <tr>
                            <td id="t_total_cheque">Total Ventas con Cheques</td>
                            <td id="total_cheque" class="text-right"></td>
                        </tr>
                        <tr>
                            <td id="t_total_cash">Total Ventas con Efectivo</td>
                            <td id="total_cash" class="text-right"></td>
                        </tr>
                        <tr>
                            <td id="t_total_card">Total Ventas con Tarjetas</td>
                            <td id="total_card" class="text-right"></td>
                        </tr>
                        <tr>
                            <td id="t_total_intern">Total Ventas con Crédito Interno</td>
                            <td id="total_intern" class="text-right"></td>
                        </tr>
                        <tr>
                            <td id="t_total_app">Total Ventas con Aplicaciones</td>
                            <td id="total_app" class="text-right"></td>
                        </tr>
                        <tr>
                            <td id="t_total_transfer">Total Ventas con Transferencias</td>
                            <td id="total_transfer" class="text-right"></td>
                        </tr>
                        <tr>
                            <td id="t_total_credit_note">Notas de crédito</td>
                            <td id="total_credit_note" class="text-right"></td>
                        </tr>
                        <tr>
                            <td id="t_total_rounding">Total de redondeo</td>
                            <td id="total_rounding" class="text-right"></td>
                        </tr>
                        <tr>
                            <td id="t_total_sales">Total de ventas</td>
                            <td id="total_sales" class="text-right"></td>
                        </tr>
                    </table>
                @if($tipoCaja->value == "1")
                    <label for="final_fund"> Subtotales: </label>
                    <table id="final_fund">
                        <tr>
                            <th>Tipo</th>
                            <th>Importe</th>
                        </tr>
                        <tr>
                            <td id="t_total_income">Total de Ingresos en efectivo</td>
                            <td id="total_income" class="text-right"></td>
                        </tr>
                        <tr>
                            <td id="t_total_expenses">Total de Egresos en efectivo</td>
                            <td id="total_expenses" class="text-right"></td>
                        </tr>
                        <tr>
                            <td id="t_total_calculated">Total de efectivo</td>
                            <td id="total_calculated" class="text-right"></td>
                        </tr>
                        <tr>
                            <td id="t_smallbox">Caja Chica</td>
                            <td id="smallbox" class="text-right"></td>
                        </tr>
                        <tr>
                            <td id="t_rendir_box">A Rendir</td>
                            <td id="total_rendir" class="text-right"></td>
                        </tr>
                    </table>
                @endif
                </div>
                @if($tipoCaja->value == "1")
                <div class="col-sm-6">
                    <label for="final_fund"> Desglose de cierre: </label>
                    <table id="final_fund">
                        <tr>
                            <th>Tipo</th>
                            <th>Valor</th>
                        </tr>
                        <tr>
                            <td id="t_total_20k">
                                $20.000
                            </td>
                            <td id="total_20k" class="text-right">
                                <input type="number" class="form-control-sm txt_moneys" id="txt_total_20k">
                            </td>
                        </tr>
                        <tr>
                            <td id="t_total_10k">
                                $10.000
                            </td>
                            <td id="total_10k" class="text-right">
                                <input type="number" class="form-control-sm txt_moneys" id="txt_total_10k">
                            </td>
                        </tr>
                        <tr>
                            <td id=t_total_5k>
                                $5.000
                            </td>
                            <td id=total_5k class="text-right">
                                <input type="number" class="form-control-sm txt_moneys" id="txt_total_5k">
                            </td>
                        </tr>
                        <tr>
                            <td id="t_total_2k">
                                $2.000
                            </td>
                            <td id="total_2k" class="text-right">
                                <input type="number" class="form-control-sm txt_moneys" id="txt_total_2k">
                            </td>
                        </tr>
                        <tr>
                            <td id="t_total_1k">
                                $1.000
                            </td>
                            <td id="total_1k" class="text-right">
                                <input type="number" class="form-control-sm txt_moneys" id="txt_total_1k">
                            </td>
                        </tr>
                        <tr>
                            <td id="t_total_500">
                                $500
                            </td>
                            <td id="total_500" class="text-right">
                                <input type="number" class="form-control-sm txt_moneys" id="txt_total_500">
                            </td>
                        </tr>
                        <tr>
                            <td id="t_total_100">
                                $100
                            </td>
                            <td id="total_100" class="text-right">
                                <input type="number" class="form-control-sm txt_moneys" id="txt_total_100">
                            </td>
                        </tr>
                        <tr>
                            <td id="t_total_50">
                                $50
                            </td>
                            <td id="total_50" class="text-right">
                                <input type="number" class="form-control-sm txt_moneys" id="txt_total_50">
                            </td>
                        </tr>
                        <tr>
                            <td id="t_total_10">
                                $10
                            </td>
                            <td id="total_10" class="text-right">
                                <input type="number" class="form-control-sm txt_moneys" id="txt_total_10">
                            </td>
                        </tr>
                        <tr>
                            <td id="t_total_5">
                                $5
                            </td>
                            <td id="total_5" class="text-right">
                                <input type="number" class="form-control-sm txt_moneys" id="txt_total_5">
                            </td>
                        </tr>
                        <tr>
                            <td id="t_total_1">
                                $1
                            </td>
                            <td id="total_1" class="text-right">
                                <input type="number" class="form-control-sm txt_moneys" id="txt_total_1">
                            </td>
                        </tr>
                        <tr>
                            <td id="t_total_real_box">Total Efectivo</td>
                            <td id="total_real" class="text-right"></td>
                        </tr>
                        <tr>
                            <td id="t_contra_box">En Contra</td>
                            <td id="total_contra" class="text-right"></td>
                        </tr>
                    </table>
                </div>
                @endif
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" id ="close_box_sale" class="btn btn-danger">PROCEDER CON CIERRE DE CAJA</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
    @if($tipoCaja->value == "1")
    <div class="" style="min-height:40px"></div>
    @endif
  </div>
</div>

<div id="add_item_no_stock" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Añadir producto sin stock</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <div class="form-group row" style="padding-bottom: 5px;">
                <div class="col-sm-6" >
                            <label for="item_no_stock_name"> Nombre: </label>
                            <input type="text" class="form-control" id="item_no_stock_name">
                </div>
                <div class="col-sm-6" >
                            <label for="item_no_stock_price"> Precio: </label>
                            <input type="number" class="form-control" id="item_no_stock_price">
                </div>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" id ="item_no_stock_add" onClick="addItemNoStock();" class="btn btn-danger">Añadir</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
      </div>
    </div>
  </div>
</div>


<body>
<div id="footer" style="z-index: 5">
    <div id="sale_box_fixed">
        <div style="float:left;margin-right:10px">
                        <select class="form-control" id="sellers">
                        </select>
        </div>
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-info btn-ticket">
                <input type="radio" name="options_document" id="option_ticket" autocomplete="off"> Boleta
            </label>
            <label class="btn btn-info btn-invoice">
                <input type="radio" name="options_document" id="option_invoice" autocomplete="off"> Factura
            </label>
        </div>
        <button id="sale_box_open_button" class="btn btn-success" data-toggle="modal" data-target="#box_sales" disabled="true">ABRIR CAJA</button>
        <button id="sale_box_close_button" class="btn btn-danger" data-toggle="modal" data-target="#box_sales_close" disabled="true">CERRAR CAJA</button>
        <button id="sale_box_movements_button" data-toggle="modal" data-target="#box_movements" disabled="true" class="btn btn-primary" disabled="true">MOVIMIENTOS CAJA</button>
        <button id="sale_documents" data-toggle="modal" data-target="#documents" class="btn btn-primary">DOCUMENTOS</button>
        <button id="sale_box_balance" data-toggle="modal" data-target="#sale_box_cash_balance" class="btn btn-primary">BALANCE</button>


    </div>
</div>

<div id="sales-receipt" class="sideNav bg-white">
    <div class="container">
        <div class="row justify-content-end">
            <div class="col-md-12 px-5">
                <div class="row">
                    <div style="margin-right:10px">
                        <button class="btn btn-info btn-md" onclick="window.location.reload();">
                            <i class="mdi mdi-arrow-left-bold pr-2"></i> Siguiente Venta
                        </button>
                    </div>
                    <div style="margin-right:10px">
                        <button id="print" class="btn btn-danger btn-md" type="button"> <span><i class="fa fa-print pr-1"></i> Imprimir</span> </button>
                    </div>
                    <div>
                        <button id="printguide" class="btn btn-danger btn-md" type="button"> <span><i class="fa fa-print pr-1"></i> Guía de Entrega</span> </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="printableArea">
        <div class="container">
            <div class="mb-3">
                <div class="row justify-content-between">
                    <div class="col-md-12 p-5 printableArea">
                        <h4>
                            <strong>RECIBO DE COMPRA</strong><br>
                            <label>Transacción N°:</label>&nbsp;<span id="trans-no"></span>
                        </h4>

                        <hr>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="pull-left">
                                    <address>
                                        <h2 class="mb-0">
                                            <strong class="text-danger">{{ env("app_name") }}</strong>
                                        </h2>
                                        <h6 class="text-muted m-1-5">
                                            <span id="org-contact"></span><br>
                                            <span id="org-address"></span>
                                        </h6>
                                    </address>
                                </div>

                                <div class="pull-right text-right">
                                    <address>
                                        <p>
                                            <strong>Fecha:&nbsp;{{ \Carbon\Carbon::now(config("timezone"))->setTimezone('America/Santiago')->format('D, M j, Y g:i:s A') }}</strong>
                                        </p>
                                    </address>
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="table-responsive m-t-40" style="clear:both">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Producto</th>
                                            <th class="text-right">Cantidad</th>
                                            <th class="text-right">Costo x Unidad (<span >$</span>)</th>
                                            <th class="text-right">Descuento</th>
                                            <th class="text-right">Total (<span>$</span>)</th>
                                        </tr>
                                        </thead>

                                        <tbody id="receipt-table"></tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="pull-right text-right">
                                    <hr>
                                    <h3 class="pl-4 mb-2"><strong>Total C/ I.V.A.:&nbsp;</strong><span >$</span><span id="sell-total"></span></h3>
                                    <h6 class="pl-4"><strong>cantidad pagada:&nbsp;</strong><span >$</span><span id="amount-paid"></span></h6>
                                    <h6 class="pl-4"><strong>Vuelto:&nbsp;</strong><span >$</span><span id="paid-change"></span></h6>
                                    <hr>
                                </div>
                                <div class="clearfix"></div>
                                <div class="text-center">
                                    <h5 class="text-uppercase">Gracias por su compra</h5>
                                    <p class="text-muted">Las devoluciones solo se aceptan dentro de las 24 horas hábiles desde el momento de la compra, en el momento de mostrar este recibo, y dado que el artículo está en buenas condiciones aceptables</p>
                                </div>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="pay-drawer" class="sideNav">
    <div class="container">
        <div class="nav-top mb-3">
            <div class="row justify-content-between">
                <div class="col-md-2">
                    <button class="btn btn-danger" onclick="closeDrawer();">
                        <i class="mdi mdi-arrow-left-bold pr-2"></i>Volver a POS
                    </button>
                </div>

                <div class="col-md-1">

                    <button id="references" class="btn btn-danger"data-toggle="modal" data-target="#references_modal">
                        <i class="mdi mdi-arrow-left-bold pr-2"></i>Referencias
                    </button>
                </div>

                <div class="col-md-6 text-center">
                    <h3 class="payment-title">Proceso de pago</h3>
                </div>

                <div class="col-md-3">
                    <button class="btn btn-success pull-right sell-button d-none" style="float:right">
                        Venta Productos<i class="mdi mdi-arrow-right-bold pl-2"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="payment mt-3">
            <div class="row">
                <!--<div class="col-md-4 document-type pr-5" id = "document-type">
                    <h4 class="pt-3">Tipo de Documento</h4>
                    <div class="">
                        <button class="btn document-btn" data-mode="ticket">Boleta</button>
                        <button class="btn document-btn" data-mode="invoice">Factura</button>
                    </div>
                </div>-->

                <div class="col-md-4 payment-method pr-5 " id = "payment-method">
                    <!--<div><div id ="go_back_document_type"><button class="btn btn-danger">Anterior</button></div><h4 class="pt-3">Selecciona el metodo de pago</h4></div>-->
                    <div class="">
                        
                        <button class="btn payment-btn" id = "payment-method_cash" data-mode="cash">Efectivo</button>
                        <button class="btn payment-btn" id = "payment-method_cheque" data-mode="cheque">Cheque</button>
                        <button class="btn payment-btn" id = "payment-method_card" data-mode="card">Tarjeta Credito/Debito</button>
                        <button class="btn payment-btn" id = "payment-method_intern" data-mode="intern">Crédito Interno</button>
                        <button class="btn payment-btn" id = "payment-method_mix" data-mode="mix">Mixto</button>
                        <button class="btn payment-btn" id = "payment-method_app" data-mode="app">App</button>
                        <button class="btn payment-btn" id = "payment-method_transfer" data-mode="transfer">Transferencia</button>
                        <div class="credit_sale" class="d-none">
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <label><input type="checkbox" id="credit_sale" name="credit_sale" /> Venta de tipo crédito</label>
                                </div>
                                <label class="label-credit_sale-date col-sm-3 col-form-label"><b>Vencimiento</b></label>
                                <div class="col-sm-9 credit_sale-date">
                                    <input type="text" class="form-control" id="FhcVenc" placeholder="Fecha Vencimiento">
                                </div>
                            </div>
                        </div>
                        <div class="letter-select" class="d-none">
                            <label><input type="checkbox" id="checkbox_letter"/> Documento tamaño carta</label>
                        </div>
                        <div class="rut-select" class="d-none">
                            <label><input type="checkbox" id="checkbox_rut"/> Ignorar rut en boleta    </label>
                        </div>
                        <div class="ticket_div d-none">
                            <label><input type="checkbox" id="submit_ticket"/> Generar Boleta    </label>
                        </div>
                        <div id = "client-select" class="d-none" >
                            <div class="form-group row">
                                <label class="label-presale col-sm-2 col-form-label"><b>RUT</b></label>
                                <div class="col-sm-10">
                                    <select id="select_rut" class="client-select-input form-control form-control-sm simple-select1-sm">
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="label-presale col-sm-2 col-form-label"><b>Cliente</b></label>
                                <div class="col-sm-10">
                                    <select id="select_client" class="client-select-input form-control form-control-sm simple-select2-sm">
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <button class="btn btn-danger" data-toggle="modal" data-target="#myModal">Nuevo Cliente</button>
                                </div>
                            </div>
                            
                            <!--<h4 class="pt-3">Seleccione Cliente</h4>
                            <div class="">
                                <select class="client-select-input">
                                </select>
                                <div class="new-client"><button class="btn btn-danger" data-toggle="modal" data-target="#myModal">Nuevo Cliente</button></div>
                            </div>-->
                        </div>
                    </div>

                </div>

                <div class="col-md-8 payment-input">
                    <div class="payment-input-scroll d-block">
                        <div class="cart-total">
                            <h5 class="px-5 mb-2">Monto Total:</h5>
                            <h1><span >$</span><span class="payment-total"></span></h1>
                        </div>

                        <div class="input-boxes my-3">
                            <!-- For cash payments -->
                            <div id="cash-payment" class="d-none">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="txtAmountTendered">Dinero en efectivo:</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
                                                <input type="number" id="txtAmountTendered"
                                                       class="form-control input-lg cash-tendered"
                                                       name="cash_amount_tendered">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="txtChange">Cambio:</label>
                                            <div class="input-group">
                                                <span class="input-group-addon">$</span>
                                                <input type="number" disabled id="txtChange"
                                                       class="form-control input-lg cash-change" name="cash_change">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="txtRemainingAmount">Cantidad restante:</label>
                                            <div class="input-group">
                                                <span class="input-group-addon ">$</span>
                                                <input type="number" id="txtRemainingAmount"
                                                       class="form-control input-lg cash-remaining-amount" value="0"
                                                       name="cash_remaining_amount">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="txtRemarks">Observaciones:</label>
                                            <textarea name="remarks" class="form-control input-lg" id="txtRemarks"
                                                      cols="30" rows="5"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="card-payment" class="d-none">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="txtAmountTendered">Número de Operación:</label>
                                            <div class="input-group">
                                                <input type="text" id="txtCardOperation"
                                                        class="form-control input-lg card-operation">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="txtRemarks">Observaciones:</label>
                                            <textarea name="remarks" class="form-control input-lg" id="txtCardObservations"
                                                      cols="30" rows="5"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="cheque-payment" class="d-none">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="txtChequeNumber">Número de Cheques:</label>
                                            <div class="input-group">
                                                <input type="number" onChange="listDocuments()" onKeyUp="listDocuments()" min="1" max="3" value="1" id="txtChequeNumber"
                                                    class="form-control input-lg cheque-number">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="txtChequeObservation">Observaciones:</label>
                                            <textarea name="remarks" class="form-control input-lg" id="txtChequeObservation"                                                      cols="30" rows="5"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th width="20%">N.Cheque</th>
                                                <th width="20%">Valor</th>
                                                <th width="15%">Fecha</th>
                                                <th width="45%">Banco</th>
                                            </tr>
                                        </thead>
                                        <tbody id="clist">
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div id="intern-payment" class="d-none">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="internObservation">Observaciones:</label>
                                            <textarea name="remarks" class="form-control input-lg" id="internObservation" cols="30" rows="5"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="mix-payment" class="d-none">
                                <div class="d-flex">
                                    <div class="w-90 p-2 flex-fill">
                                        <div class="form-group row">
                                            <label class="label-presale col-sm-3 col-form-label"><b>Tipo: </b></label>
                                            <div class="col-sm-9">
                                                <select id="select_type" class="form-control form-control-sm">
                                                    <option value="1">Efectivo</option>
                                                    <option value="2">Cheque</option>
                                                    <option value="3">Tarjeta Débito/Crédito</option>
                                                    <option value="4">Crédito Interno</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="label-presale col-sm-3 col-form-label"><b>Monto: </b></label>
                                            <div class="col-sm-9">
                                                <input  class="form-control form-control-sm input-mix" type="number"/>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="label-presale col-sm-3 col-form-label"><b>Obs: </b></label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control form-control-sm obs-mix " cols="30" rows="3"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row float-right">
                                            <div class="col-sm-12">
                                                <button class="btn btn-danger btn-add-mix">Agregar</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="w-100 p-2 flex-fill">
                                        <div class="form-group row">
                                            <label class="label-presale col-sm-12 col-form-label mix-remaining"><b>Cantidad Restante: </b></label>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <table class="table table-dark">
                                                    <thead>
                                                        <tr>
                                                            <th>Tipo</th>
                                                            <th>Monto</th>
                                                            <th>Vuelto</th>
                                                            <th>Obs.</th>
                                                            <th>Acc.</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="mixlist">
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>

                            <div id="app-payment" class="d-none">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="selectAppOperation">Aplicación Utilizada:</label>
                                            <div class="input-group">
                                                <select id="selectAppOperation"
                                                        class="form-control input-lg">
                                                        <!--Fill-->
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="txtVoucher">Voucher:</label>
                                            <input name="voucher" class="form-control input-lg" id="txtAppVoucher"></input>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="transfer-payment" class="d-none">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="txtClientNameTransfer">Cliente:</label>
                                            <input name="voucher" class="form-control input-lg" id="txtClientNameTransfer"></input>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="pos-body">
    <div class="pos">
        <div class="pos-top-header">
            <div class="pos-branding">
       
                    
                    @if(Auth::check())
                    <a id="switchUser" style="float:left" class="username dropdown-toggle waves-effect waves-dark" href="#"
                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span>{{ Auth::user()->name }}</span>
                    </a>
                    @endif
                    <div class="dropdown-menu" aria-labelledby="switchUser">
                        <ul>
                            <li ><a style="padding-top: 15px;padding-bottom: 15px;" href="{{url('/items')}}" class="dropdown-item" >Volver a Inventario</a></li>

                            <li><a style="padding-top: 15px;padding-bottom: 15px;" href="#" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit()">Cerrar sesión</a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                            
                        </ul>
                    </div>
                    
            </div>
            <div class="float-right" style="margin-top:10px;margin-right:10px">
                <span style="color:white;margin-right:10px;font-size: 16px" id="box_name">Caja : N/D</span>
            </div>
        </div>
        <div class="pos-content">
            <div class="window">
                <div class="sub-window-container">
                    <div class="sub-window-container-fix">
                        <div class="screen">
                            <div class="left-pane">
                                <div class="window">
                                    <div class="cart-window">
                                        <div class="cart-window-container">
                                            <div class="cart-window-container-fix">
                                                <div class="order-container">
                                                    <div class="order-scroll">
                                                        <div class="order">

                                                            <div class="order-list">
                                                                <div class="order-empty">
                                                                    <i class="mdi mdi-cart"></i>
                                                                    <h4>Su carrito está vacio</h4>
                                                                </div>
                                                            </div>

                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="keypad-window collapsed">
                                        <div class="keypad-window-container">
                                            <div class="keypad-window-container-fix">
                                                <div class="action-pad col-sm-5" >
                                                    <button id="btnClearCart" class="button clear-cart">
                                                        <i class="mdi mdi-delete"></i>
                                                        <span>Vaciar Carrito</span>
                                                    </button>
                                                    <button id="btnPay" class="button pay">
                                                        <div class="pay-circle">
                                                            <i class="mdi mdi-chevron-right"></i>
                                                        </div>
                                                        <span>Pagar</span>
                                                    </button>
                                                </div>

                                                <div class="num-pad col-sm-7">
                                                    <div class='summary d-none clearfix'>
                                                                <div class='line'>
                                                                    <div class='entry total'>
                                                                        <table style="width:100%">
                                                                            <tr>
                                                                              <td style="font-size:20px" id="total_label">Total Neto : </td>
                                                                              <td style="font-size:20px;text-align:right" class="value">0</td>
                                                                            </tr>
                                                                            <tr id="tax_detail">
                                                                              <td style="font-size:20px">IVA : </td>
                                                                              <td style="font-size:20px;text-align:right" class="tax_value">0</td>
                                                                            </tr>
                                                                            <tr id="total_detail">
                                                                              <td style="font-size:20px">Total : </td>
                                                                              <td style="font-size:20px;text-align:right" class="total_value">0</td>
                                                                            </tr>
                                                                        </table>
                                                                        <!--<span class='label pr-2'>Total:</span>
                                                                        <span class='pr-1'><span>$</span></span><span
                                                                                class='value'>0.00</span>
                                                                        <div class='sub-entry'>I.V.A: <span class='value'>0.00</span></div>-->
                                                                    </div>
                                                                </div>
                                                                <div class="line">
                                                                <table width="100%" border="1px" bordercolor="gray" style="margin-top:20px;border-radius: 1em" >
                                                                    <tr>
                                                                       <td>Desc : </td>
                                                                       <td><input style="width:50px" id="discount_number" type="number" /></td>
                                                                       <td><button onclick="applyDiscount()" style="width:100%">Aplicar</button></td> 
                                                                    </tr>
                                                                </table>
                                                                </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="right-pane">
                                <table class="layout-table">
                                    <tbody>
                                    <tr class="header-row">
                                        <td class="header-cell">
                                            <div>
                                                <header class="right-pane-header">
                                                    <div class="search-box1">
                                                        <label class="item_brands" for="item_brands"> Marca</label>
                                                        <select  class=" form-control-sm item_brands" id="item_brands"  autofocus></select>
                                                        <label class="item_categories" for="item_categories">Categoria</label>
                                                        <select  class=" form-control-sm item_categories" id="item_categories"  autofocus></select>
                                                        <input placeholder="Buscar Productos" id="txtSearch1" name="search1" autofocus>
                                                        <span class="search-clear"></span>
                                                        <button class="btn btn-success" onClick="loadItems();" style="
    background: #28a745;">Buscar</button>
                                                        <button id="add_item_no_stock_button" class="btn btn-danger" data-toggle="modal" data-target="#add_item_no_stock" disabled="true" style="
    background: #c50b2e;">Agregar producto sin stock</button>
                                                    </div>
                                                    <div class="search-box">
                                                        <input placeholder="Agregar producto SKU" id="txtSearch" name="search" autofocus>
                                                        <span class="search-clear"></span>
                                                    </div>
                                                </header>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="content-row">
                                        <td class="content-cell">
                                            <div class="content-container">
                                                <div class="product-list-container">
                                                    <div class="product-list-scroll touch-scroll">
                                                        <div class="product-list">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Scripts -->
<script src="{{ asset('plugins/jQuery/jquery-3.2.1.min.js') }}"></script>
<!--<script src="{{ asset('jspos/jquery.min.js') }}"></script>-->
<script src="{{ asset('jspos/popper.min.js') }}"></script>
<script src="{{ asset('jspos/bootstrap.min.js') }}"></script>
<!--<script src="{{ asset('jspos/jquery.slimscroll.js') }}"></script>-->
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
<script src="{{ asset('js/sweetalert2.min.js') }}"></script>

<script type="text/javascript">
    var clicked = 0;
    var remove = 0;
    var price_clicked = 0;
    var price_remove = 0;
    var discounts = null;
    let cartitems = [];
    let defaultRutTicket = "66666666-6";
    let file = "";
    var pos_mode     = "ticket";
    var ignore_rut   = false;
    var credit_sale   = false;
    var total_cart   = 0;
    var adjust_cart  = 0; //REDONDEO
    var order_number = null;
    var delivery_guide = null;
    var mix_list   = [];
    var mix_references = [];
    contReferences = 1;
    let refTotalReal = 0;
    let refContra = 0;
    let finalReal = 0;
    let totalBox = 0;
    let stopButton;

    let printUrl = "";
    let submitDefault = true;

    $("#txtAppVoucher").val("");
    $("#selectappOperation").val(-1);
    $("#txtClientNameTransfer").val("");

    @if($tipoCaja->value == '1')
        $('#txt_total_20k').val(0);
        $('#txt_total_10k').val(0);
        $('#txt_total_5k').val(0);
        $('#txt_total_2k').val(0);
        $('#txt_total_1k').val(0);
        $('#txt_total_500').val(0);
        $('#txt_total_100').val(0);
        $('#txt_total_50').val(0);
        $('#txt_total_10').val(0);
        $('#txt_total_5').val(0);
        $('#txt_total_1').val(0);
    @endif

    function changeItemKey(ths, cart_item, item_name, price, cart_item_id) {
        if(this.event.key == 'Enter') {
            AddProductToCart([cart_item, item_name, price, $(ths).val(),cart_item_id]);
        }
    }

    function changeItemMouse(ths, cart_item, prev, item_name, price, cart_item_id) {
        if($(ths).val() != prev && cart_item != null) {
            AddProductToCart([cart_item, item_name, price, $(ths).val(),cart_item_id]);
        }
    }

    function showPayment() {
        $('.sell-button').removeClass('d-none');
    }

    function listDocuments() {
        $('.sell-button').addClass('d-none');
        let docs         = $('#txtChequeNumber').val();
        let paymentTotal = total_cart;
        
        let banklist = [
            'Banco de Chile', 'Banco Internacional', 'Banco de Crédito e Inversiones', 'Banco Bice', 
            'HSBC Bank (Chile)', 'Banco Santander', 'Banco Itaú', 'Banco Security', 'Banco Falabella', 
            'Banco Ripley', 'Banco Consorcio', 'Banco Penta', 'Banco Paris', 'Banco BBVA', 'Banco Estado'
        ];
        $('#clist').empty();
        

        if(docs > 0 && docs <= 3) {
            let values       = calculateFee(paymentTotal,docs);
            let optionList = '';
            for (b in banklist) {
                optionList += '<option>'+banklist[b]+'</option>';
            }
            for (x=0; x<docs; x++) {
                let frmt = moment().add((x+1)*30,'days').format('Y-MM-DD');
                
                let row = `<tr>
                                <td><input onKeyUp="showPayment()" type="text" class="form-control"/></td>
                                <td><input value="${values[x]}" class="form-control" /></td>
                                <td><input type="date" value="${frmt}" style="height:42px" class="form-control"/></td>
                                <td><select class="form-control" style="height:42px">${optionList}</select>
                        </tr>`;
                $('#clist').append(row);
            }
        }
    }

    function loadClients(client_id = null) {
        AWApi.get('{{ url('api/pos/clients')}}', function (response) {
            if (response.code == 200) {
                $('.client-select-input').empty();
                $('.client-select-input').append('<option value="0">Seleccione Cliente</option>');
                $(".client-select-input").editableSelect();
                for (x in response.data.data.items) {
                    $(".client-select-input").editableSelect('destroy');
                    let options = '';
                    options += '<option '+(client_id == response.data.data.items[x].id_client ? 'selected' : '')+' value="'+response.data.data.items[x].id_client+'">';
                    options += response.data.data.items[x].id_client+' : '+response.data.data.items[x].razon_social;
                    options += '</option';
                    $('.client-select-input').append(options);
                    $(".client-select-input").editableSelect();
                }
                
            }
        });
    }

    function reloadItems() {
        AWApi.get('{{ url('/api/discount') }}', function(response){
            discounts  = response.data.discounts;
            loadItems();

            // Get cart items (if any), and display in cart
            getCartAndDisplay();
            //Get all product in stock
        });
    }

    var table;

    function switchType(type) {
        switch(type) {
            case '1' : return 'Efectivo';
            case '2' : return 'Cheque';
            case '3' : return 'Tarjeta Débito/Crédito';
            case '4' : return 'Crédito Interno';
            default : return '';
        }
    }

    function rmMixList(rownum) {
        let newmix = [];
        let count  = 1;
        mix_list.map(function(mlist) {
            if(mlist.rownum != rownum) {
                newmix.push({
                    type   : mlist.type,
                    amount : mlist.amount,
                    change : mlist.change,
                    obs    : mlist.obs,
                    rownum : count
                });
                count++;
            }
        });
        mix_list = newmix;
        recalcRemaining();
        renderMixList();
    }

    function renderMixList() {
        let remaining = 0;
        let total_mix = 0;
        let rows      = '';
        $('#mixlist').empty();
        mix_list.map(function(mlist) {
            rows += `<tr>
                        <td>${switchType(mlist.type)}</td>
                        <td>$${formatMoney(mlist.amount,'CL')}</td>`;
                        if(mlist.type == 1) {
                            rows += `<td>${mlist.change}</td>`;
                        } else {
                            rows += `<td>-</td>`;
                        }
            rows += `<td>${mlist.obs}</td>
                    <td><button onclick="rmMixList(${mlist.rownum})" class="btn btn-sm btn-danger">Eliminar</button></td>
                    </tr>`;
            total_mix += mlist.amount + mlist.change;
        });
        $('#mixlist').append(rows);
        remaining = total_cart - total_mix;
        $(".mix-remaining").html('<b>Cantidad Restante : $'+formatMoney(remaining,'CL')+'</b>');
        if(remaining == 0 && mix_list.length > 0) {
            $('.sell-button').removeClass('d-none');
        } else {
            $('.sell-button').addClass('d-none');
        }
    }

    function addCash() {
        let totalReal = parseInt(refTotalReal);
        let contra = parseInt(refContra);
        let mVal = parseCash();
        totalReal += mVal.val20k;
        totalReal += mVal.val10k;
        totalReal += mVal.val5k;
        totalReal += mVal.val2k;
        totalReal += mVal.val1k;
        totalReal += mVal.val500;
        totalReal += mVal.val100;
        totalReal += mVal.val50;
        totalReal += mVal.val10;
        totalReal += mVal.val5;
        totalReal += mVal.val1;
        finalReal = totalReal;
        $("#total_real").text('$'+formatMoney(totalReal, 'CL'));
        $("#total_contra").text('$'+formatMoney(totalReal+contra, 'CL'));
    }

    function parseCash() {
        let multipliedVals = new Object();
        
        let val20k = $('#txt_total_20k').val();
        let val10k = $('#txt_total_10k').val();
        let val5k = $('#txt_total_5k').val();
        let val2k = $('#txt_total_2k').val();
        let val1k = $('#txt_total_1k').val();
        let val500 = $('#txt_total_500').val();
        let val100 = $('#txt_total_100').val();
        let val50 = $('#txt_total_50').val();
        let val10 = $('#txt_total_10').val();
        let val5 = $('#txt_total_5').val();
        let val1 = $('#txt_total_1').val();
        multipliedVals.val20k = (val20k != "" ? parseInt(val20k) : 0) * 20000;
        multipliedVals.val10k = (val10k != "" ? parseInt(val10k) : 0) * 10000;
        multipliedVals.val5k = (val5k != "" ? parseInt(val5k) : 0) * 5000;
        multipliedVals.val2k = (val2k != "" ? parseInt(val2k) : 0) * 2000;
        multipliedVals.val1k = (val1k != "" ? parseInt(val1k) : 0) * 1000;
        multipliedVals.val500 = (val500 != "" ? parseInt(val500) : 0) * 500;
        multipliedVals.val100 = (val100 != "" ? parseInt(val100) : 0) * 100;
        multipliedVals.val50 = (val50 != "" ? parseInt(val50) : 0) * 50;
        multipliedVals.val10 = (val10 != "" ? parseInt(val10) : 0) * 10;
        multipliedVals.val5 = (val5 != "" ? parseInt(val5) : 0) * 5;
        multipliedVals.val1 = (val1 != "" ? parseInt(val1) : 0) * 1;
        multipliedVals.qty20k = (val20k != "" ? parseInt(val20k) : 0);
        multipliedVals.qty10k = (val10k != "" ? parseInt(val10k) : 0);
        multipliedVals.qty5k = (val5k != "" ? parseInt(val5k) : 0);
        multipliedVals.qty2k = (val2k != "" ? parseInt(val2k) : 0);
        multipliedVals.qty1k = (val1k != "" ? parseInt(val1k) : 0);
        multipliedVals.qty500 = (val500 != "" ? parseInt(val500) : 0);
        multipliedVals.qty100 = (val100 != "" ? parseInt(val100) : 0);
        multipliedVals.qty50 = (val50 != "" ? parseInt(val50) : 0);
        multipliedVals.qty10 = (val10 != "" ? parseInt(val10) : 0);
        multipliedVals.qty5 = (val5 != "" ? parseInt(val5) : 0);
        multipliedVals.qty1 = (val1 != "" ? parseInt(val1) : 0);
        return multipliedVals;
    }

    $(document).ready(function () { 
        $("#submit_ticket").prop('checked', true);

        $("#submit_ticket").change(function() {
            if(submitDefault && !$("#submit_ticket").is(':checked')) {
                swal({
                title: "Desactivar Boleta",
                text: "¿Esta seguro de que desea no emitir boleta?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "SI",
                closeOnConfirm: true,
                cancelButtonText: "NO",
                buttons: [
                    'No',
                    'Si'
                ]
                }).then(function(inputValue) {
                    if(inputValue === true) {
                        $("#submit_ticket").prop('checked', false);
                    } else {
                        $("#submit_ticket").prop('checked', true);
                    }
                });
            }
        });

        var printService = new WebSocketPrinter({
            onUpdate: function (message) {
                swal.close();
                clearInterval(stopButton);
            },
        });

        $('.client_comune').selectpicker({
            liveSearch:true,
            noneSelectedText: 'Seleccione una comuna',
            noneResultsText: ''
        });


        $('.client_comune').find('.bs-searchbox').find('input').on('keyup', function(event) {
            //get code 
            if(event.keyCode != 38 && event.keyCode != 40) {
                $('.client_comune').find('option').remove();
                $('.client_comune').selectpicker('refresh');
                let data = new Object();
                data.comune_detail = $(this).val();
                AWApi.get('{{ url('/api/comunes') }}?' + $.param(data), function(response) {
                    if(response.code == 200) {
                        src1 = [{
                            id: null, txt:""
                        }];

                        response.data.rows.map( (comunes) => {
                            src1.push({
                                id:comunes.id,
                                txt:comunes.comune_detail
                            });
                        });

                        var options = [];
                        var option = '<optgroup label="Comuna">';
                        src1.forEach(function (item) {
                            
                            option += "<option value="+item.id+" >" + item.txt + "</option>"
                            
                        });
                        option += '</optgroup>';


                        options.push(option);
                        $('#s_comune').html(options);
                        $('.client_comune').selectpicker('refresh');
                    }
                });
            }
        });

        $('input[type="checkbox"][name="credit_sale"]').change(function() {
            if(this.checked) {
                $('.label-credit_sale-date').show()
                $('.credit_sale-date').show()
            }else{
                $('.label-credit_sale-date').hide()
                $('.credit_sale-date').hide()
            }
        });

        $( '#credit_sale' ).on( 'click', function() {
            if( $(this).is(':checked') ){
                credit_sale = true;
                $('.label-credit_sale-date').show()
                $('.credit_sale-date').show()
                //aquí se deben retirar las clases asociadas a error o rut correcto
            } else {
                credit_sale = false;
                $('.label-credit_sale-date').hide()
                $('.credit_sale-date').hide()
            }
        });

        $('#i_date').datepicker({
                format: 'dd/mm/yyyy',
                gotoCurrent: true,
                language:'es'
            });
        $('#FhcVenc').datepicker({
                format: 'dd/mm/yyyy',
                gotoCurrent: true,
                language:'es'
            });

        $('#i_date').datepicker('setDate',moment(new Date()).format('DD/MM/YYYY'));
        $('#FhcVenc').datepicker('setDate',moment(new Date()).format('DD/MM/YYYY'));

        
        $('.btn-add-mix').on('click', function() {
            let type   = $('#select_type').val();
            let amount = $('.input-mix').val();
            let obs    = $('.obs-mix').val();
            let rownum =  mix_list.length+1;
            var change = 0;
            let remaining = 0;
            let total_mix = 0;

            mix_list.forEach(function(row) {
                total_mix += parseInt(row.amount);
            })
            remaining = total_cart - total_mix;
            let checkRemaining = remaining;
            if(total_cart < total_mix) {
                checkRemaining = 0;
            }

            if(type == 1) {
                change = (parseInt(checkRemaining, 10) - parseInt(adjust_cart, 10)) - parseInt(amount, 10);
            }
            if(change > 0) {
                change = 0;
            }
            if(amount > 0) {
                mix_list.push({
                    type   : type,
                    amount : parseInt(amount),
                    obs    : obs,
                    rownum : mix_list.length+1,
                    change : parseInt(change)
                });
                recalcRemaining();
                renderMixList();
            }


            
        });

        $('#myModal').on('show.bs.modal', function (e) {
            $('.client_id').removeClass('is-invalid');
            $('.client_name').removeClass('is-invalid');
            $('.client_email').removeClass('is-invalid');
            $('.client_industries').removeClass('is-invalid');
            $('.client_comune').removeClass('is-invalid');
            $('.error-client-id').empty();
            $('.error-client-name').empty();
            $('.error-client-email').empty();
            $('.error-client-industries').empty();
            $('.error-client-comune').empty();
            $('.client_id').val('');
            $('.client_name').val('');
            $('.client_email').val('');
            $('.client_address').val('');
            $('.client_industries').val('');
            $('#s_comune').val('');
            $('.client_comune').val('');
            $(".client_comune").selectpicker('refresh');
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

        $('#select_rut').on('change', function() {
            if($(this).val() != '' && $(this).val() != 'null') {
                AWApi.get('{{ url('/api/clients/') }}/'+$(this).val(), function(response) {
                    if(response.code == 200) {
                        $('.simple-select2-sm').find('option').remove();
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

                        if (response.data.has_discount === 1){
                                            swal({
                                                title: "Descuento de cliente",
                                                text: "¿ Desea aplicar el descuento de "+ response.data.discount_percent +"% aplicado al cliente "+ response.data.name +" ?",
                                                icon: "warning",
                                                buttons: [
                                                    'No',
                                                    'Si'
                                                ]
                                                }).then(function(isConfirm) {
                                                    if (isConfirm) {
                                                        closeDrawer();
                                                        $('#discount_number').val(response.data.discount_percent);
                                                        applyDiscount();
                                                        
                                                        swal({
                                                        title: '¡ Descuento aplicado !',
                                                        
                                                        icon: 'success'
                                                        })
                                                    }});
                                                }
                    }
                });
            } else {
                $('.simple-select2-sm').selectpicker('val', '');
            }
        });

        $("#select_client").on('change', function() {
            if($(this).val() != '' && $(this).val() != 'null') {
                AWApi.get('{{ url('/api/clients/') }}/'+$(this).val(), function(response) {
                    if(response.code == 200) {
                        $('.simple-select1-sm').find('option').remove();
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
            }
        });

        AWApi.get('{{ url('api/sellers/list')}}', function (response) {
            if (response.code == 200) {
                $('#sellers').append('<option value="0">Seleccione Vendedor ... </option>');
                for (x in response.data) {
                    $('#sellers').append('<option value="'+response.data[x].id+'">'+response.data[x].name+'</option>');
                }
                if (response.data.length == 1){
                    $('#sellers').val(response.data[0].id);
                }
            }
        });

        if (pos_mode == 'ticket') {
            $('#option_ticket').parent().addClass('active');
            $("#total_label").text("Total : ");
            $("#tax_detail").hide();
            $("#total_detail").hide();
            $("#references").hide();
            $("#checkbox_rut").prop("checked", true);
                ignore_rut = true;
            cleanAddedReferences();
        } else {
            $('#option_invoice').parent().addClass('active');
            $("#total_label").text("Total Neto : ");
            $("#tax_detail").show();
            $("#total_detail").show();
            
        }

        $('.btn-ticket').click(function() {
            pos_mode = 'ticket';
            $("#total_label").text("Total : ");
            $("#tax_detail").hide();
            $("#total_detail").hide();
            closeDrawer();
            reloadItems();
        });
        $('.btn-invoice').click(function() {
            pos_mode = 'invoice';
            $("#total_label").text("Total Neto : ");
            $("#tax_detail").show();
            $("#total_detail").show();
            closeDrawer();
            reloadItems();
        });


        table = $('#datas').DataTable( {
                "language": {
                    "url": "{{ asset('json/Spanish.json') }}"
                },
                "responsive": false,
                "processing": true,
                "serverSide": true,
                "lengthChange": false,
                "searching": false,
                "ajax": function (data, callback, settings) {
                    var filters = {};

                    data.filters = filters;

                    AWApi.get('{{ url('/api/pos/movements' ) }}?' + $.param(data), function (response) {
                        callback({
                            recordsTotal: response.data.movements.total,
                            recordsFiltered: response.data.movements.filtered,
                            data: response.data.movements.rows
                        });
                    });
                },
                "paging": true,
                "scrollY":        "300px",
                "scrollCollapse": true,
                "columns": [
                    { "data" : "id", "visible": false},
                    { "data" : "type", render: function(a, b, c, d) {
                        switch(c.type) {
                            case 1: return 'Apertura de Caja'; break;
                            case 2: return 'Agrega balance'; break;
                            case 3: return 'Cierre de Caja'; break;
                            case 4: return 'Venta efectivo con boleta'; break;
                            case 5: return 'Venta tarjeta débito/crédito con boleta'; break;
                            case 6: return 'Venta cheque con boleta '; break;
                            case 7: return 'Venta efectivo con factura'; break;
                            case 8: return 'Venta tarjeta débito/crédito con factura'; break;
                            case 9: return 'Venta cheque con factura '; break;
                            case 10: return 'Venta boleta crédito interno '; break;
                            case 11: return 'Venta factura crédito interno '; break;
                            case 12: return 'Diferencia boleta pago efectivo ley redondeo '; break;
                            case 13: return 'Diferencia factura pago efectivo ley redondeo '; break;
                            case 14: return 'Devolución por nota de crédito'; break;
                            case 15: return 'Venta boleta con APP'; break;
                            case 16: return 'Venta factura con APP'; break;
                            case 17: return 'Venta boleta con transferencia bancaria'; break;
                            case 18: return 'Venta factura con transferencia bancaria'; break;

                        }
                    }},
                    { "data" : "amount"},
                    { "data" : "doc_id"},
                    { "data" : "observations"},
                    { "data" : "created_at", render: function(a,b,c,d) {
                        return utcToLocal(c.created_at);
                    }}
                ]
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
                    
                    filters.folio           = $('#f_folio').val();
                    filters.type            = $('#f_type').val();


                    data.filters = filters;

                    

                    AWApi.get('{{ url('/api/salesGrid' ) }}?' + $.param(data), function (response) {
                        callback({
                            recordsTotal: response.data.recordsTotal,
                            recordsFiltered: response.data.recordsFiltered,
                            data: response.data.sales
                        });
                    });
                },
                "paging": true,
                "columns": [
                    { "data": "id", "visible": false,},
                    { "data": "folio_fallback"},
                    { "data": "type",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            switch(full.type) {
                                case 0: return 'Factura'; break;
                                case 1: return 'Boleta'; break;
                                case 2: return 'Factura'; break;

                            }
                        }
                    },
                    { "data": "date",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            date = moment(data).format("DD/MM/YYYY");
                            return date;
                        }
                    },
                    { "data": "net",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            return '$'+formatMoney(full.net,'CL')
                        }
                    },
                    { "data": "tax" ,
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            return '$'+formatMoney(full.tax,'CL')
                        }
                    },
                    { "data": "id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            return '$'+formatMoney(full.total,'CL')
                        }
                    },                    
                    { 
                        "data": "id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            dte_type = 0;
                            var viewReceipt = "<button class='btn btn-danger btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=llamaGuia("+full.id+");>";

                            if (full.type === 1){
                                dte_type = 39;
                                viewReceipt += "<i class='fas fa-file'></i> </button>";
                            }else{
                                dte_type = 33;
                                viewReceipt += "<i class='fas fa-receipt'></i> </button>";
                            }

                           

                            var viewVoucher = "<button class='btn btn-danger btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=callVoucher("+full.client.rut+",'"+full.client.rut_dv+"',"+dte_type+","+full.folio+");>";
                            viewVoucher += "<i class='fas fa-print'></i> </button>";

                            return "<div class='btn-group'>"+viewVoucher+"</div>";
                        }
                    }
                ]
            });




        //loadClients();
        var vbalance = new AutoNumeric('#balance', {
                                currencySymbol : ' $',
                                decimalCharacter : ',',
                                digitGroupSeparator : '.',
                                decimalPlaces:'0'
                            });

          $("#box_sales").on('show.bs.modal', function(){
                $('#start_date').val(moment().format('YYYY-MM-DD'));
                $('#FhcVenc').val(moment().format('YYYY-MM-DD'));
                $('#start_hours').val(moment().format('HH:mm'));
                vbalance.set(0);
          });
          $('#box_sales_close').on('show.bs.modal', function(){
                $('#end_date').val(moment().format('YYYY-MM-DD'));
                $('#end_hours').val(moment().format('HH:mm'));
                AWApi.get('{{ url('/api/pos/movements/resume' ) }}?', function (response) {
                    if(response.code == 200) {
                        totalBox = response.data.resume.total_box
                        $('#total_box').text('$'+formatMoney(totalBox,'CL'));
                        $('#total_card').text('$'+formatMoney(response.data.resume.total_card,'CL'));
                        $('#total_cheque').text('$'+formatMoney(response.data.resume.total_cheque,'CL'));
                        $('#total_cash').text('$'+formatMoney(response.data.resume.total_cash,'CL'));
                        $('#total_intern').text('$'+formatMoney(response.data.resume.total_intern,'CL'));
                        $("#total_app").text('$'+formatMoney(response.data.resume.total_app,'CL'));
                        $("#total_transfer").text('$'+formatMoney(response.data.resume.total_transfer,'CL'));
                        $('#total_credit_note').text('$'+formatMoney(response.data.resume.total_credit_note,'CL'));
                        $('#total_rounding').text('$'+formatMoney(response.data.resume.total_rounding,'CL'));
                        $("#closed_box_name").val(response.data.box);
                        $("#real_cash").val(totalBox);
                        $("#total_income").text('$'+formatMoney(response.data.resume.total_income,'CL'));
                        $("#total_expenses").text('$'+formatMoney(response.data.resume.total_expenses,'CL'));
                        $("#total_calculated").text('$'+formatMoney(response.data.resume.total_calculated, 'CL'));
                        $("#total_invoice").text('$'+formatMoney(response.data.resume.total_invoice,'CL'));
                        $("#total_ticket").text('$'+formatMoney(response.data.resume.total_ticket, 'CL'));
                        $("#smallbox").text('$'+formatMoney(response.data.resume.smallbox, 'CL'));
                        refTotalReal = parseInt(response.data.resume.total_rounding);
                        //Añadir si Total Efectivo necesita cheque y efectivo
                        //refTotalReal += parseInt(response.data.resume.total_cash) + parseInt(response.data.resume.total_cheque);
                        refContra = -parseInt(totalBox);
                        $("#total_rendir").text('$'+formatMoney(totalBox, 'CL'));
                        $('#total_contra').text('$'+formatMoney(refContra,'CL'));
                        $("#total_real").text('$'+formatMoney(refTotalReal, 'CL'));
                        $("#total_sales").text('$'+formatMoney(response.data.resume.sales_total, 'CL'));
                        addCash();
                    }
                });

          });
          $("#box_movements").on('show.bs.modal', function(){
                table.ajax.reload();
          });

          /*
              get box_sales
           */
          AWApi.get('{{ url('/api/sale_box' ) }}?', function (response) {
                $('#box_sales_id').empty();
                $('#end_box_sales_id').empty();
				box_ready = 0;
                for (var i = 0; i < response.data.sale_boxes.length; i++) {
                    name = "";
                    $('<option />', {value: response.data.sale_boxes[i].id, text: response.data.sale_boxes[i].name }).appendTo($("#box_sales_id")); 
                    $('<option />', {value: response.data.sale_boxes[i].id, text: response.data.sale_boxes[i].name }).appendTo($("#end_box_sales_id"));
					if (box_ready == 0){
						if(response.data.sale_boxes[i].seller == response.data.user_id  &&  response.data.sale_boxes[i].status == 1){
							$('#box_name').text('Caja : '+response.data.sale_boxes[i].name);
							/*
							   OPEN
							 */
							$('#sale_box_open_button').prop("disabled",true);
							$('#sale_box_movements_button').prop('disabled', false);
							$('#sale_box_close_button').prop("disabled",false);
							$('#add_item_no_stock_button').prop("disabled",false);
							$('#sale_box_balance').prop("disabled",false);
							box_ready = 1;
						}else{
							$('#sale_box_close_button').prop("disabled",true);
							$('#add_item_no_stock_button').prop("disabled",true);
							$('#sale_box_open_button').prop("disabled",false);
							$('#sale_box_movements_button').prop('disabled', true);
							$('#sale_box_balance').prop('disabled', true);
						}
					}
                }
            });
          /*
              get branch_offices
           */

          AWApi.get('{{ url('/api/branch_office') }}',function (response) {

                $('#branch_office_id').empty();
                $('#end_branch_office_id').empty();
                for (var i = 0; i < response.data.branch_office.length; i++) {
                    name = "";
                    $('<option />', {value: response.data.branch_office[i].id, text: response.data.branch_office[i].name }).appendTo($("#branch_office_id")); 
                    $('<option />', {value: response.data.branch_office[i].id, text: response.data.branch_office[i].name }).appendTo($("#end_branch_office_id")); 
                }
            });

        AWApi.get('{{ url('/api/brands') }}',function (response) {
            $('#item_brands').empty();
            if (response.data.brands.length == 0){
                $(".item_brands").hide()
            }else{
                $(".item_brands").show()
            }
            $('<option />', {value: 0, text: 'TODAS' }).appendTo($("#item_brands")); 
            for (var i = 0; i < response.data.brands.length; i++) {
                $('<option />', {value: response.data.brands[i].id, text: response.data.brands[i].name }).appendTo($("#item_brands")); 
            }
        });

        AWApi.get('{{ url('/api/categories') }}',function (response) {
            $('#item_categories').empty();
            if (response.data.categories.length == 0){
                $(".item_categories").hide()
            }else{
                $(".item_categories").show()
            }
            $('<option />', {value: 0, text: 'TODAS' }).appendTo($("#item_categories")); 
            for (var i = 0; i < response.data.categories.length; i++) {
                $('<option />', {value: response.data.categories[i].id, text: response.data.categories[i].full_route.toUpperCase() }).appendTo($("#item_categories")); 
            }
        });
          /*
              get all users
           */
           /*AWApi.get('{{ url('/api/users' ) }}?', function (response) {
                 $('#cashier_id').empty();
                 for (var i = 0; i < response.data.users.length; i++) {
                     name = "";
                     $('<option />', {value: response.data.users[i].id, text: response.data.users[i].name }).appendTo($("#cashier_id")); 
                }
                });*/

        closePaymentMethod();
        /*
            get discounts
         */
        AWApi.get('{{ url('/api/discount') }}', function(response){
            discounts  = response.data.discounts;
            loadItems();

            // Get cart items (if any), and display in cart
            getCartAndDisplay();
            //Get all product in stock
        });

        $( '#checkbox_rut' ).on( 'click', function() {
            if( $(this).is(':checked') ){
                ignore_rut = true;
                //aquí se deben retirar las clases asociadas a error o rut correcto
            } else {
                ignore_rut = false;
            }
        });

        $("#input_rut").keyup( function(e) {
            var charCode = (e.which) ? e.which : e.keyCode;
            if ((charCode >= 48 && charCode <= 57) || charCode == 8 || charCode == 189 || charCode == 109 || (charCode >= 96 && charCode <= 105)) {
                let r = 0;
            } else {
                return false;
            }
        });
        $("#input_rut").keyup( function(e) {
            var charCode = (e.which) ? e.which : e.keyCode;
            if ((charCode >= 48 && charCode <= 57) || charCode == 8 || charCode == 189 || charCode == 109 || (charCode >= 96 && charCode <= 105)) {
                if (rutEsValido(this.value)) {
                    $("#input_rut").addClass('input-rut-ok');
                    $("#input_rut").removeClass('input-rut-nok');
                }
                else {
                    $("#input_rut").addClass('input-rut-nok');
                    $("#input_rut").removeClass('input-rut-ok');
                }
            } else {
                return false;
            }
        });

        $( "#txtSearch" ).keypress(function(e) {
            if(e.which == 13){
                searchBarcode();
            }
        });
        $('#txtSearch1').on("keyup", function(e) {
            var code = e.which;
            if(code==13) {
                loadItems();
            }
        });

        $("#open_box_sale").click(function(){

            var data = new FormData();
            $("#open_box_sale").attr('disabled', true);

            data.append('box_sales_id', $('#box_sales_id').val());
            data.append('branch_office_id', $('#branch_office_id').val());
            data.append('box_status','open');
            //data.append('cashier_id', $('#cashier_id').val());
            data.append('start_date',$('#start_date').val());
            data.append('start_hours', $('#start_hours').val());
            data.append('balance', vbalance.getNumericString());
            AWApi.post('{{ url('api/sale_box/update_sale_box_status')}}', data, function(response){
                $("#open_box_sale").attr('disabled', false);
                if (response.code == 401) {
                    $.toast({
                        heading: 'Error',
                        text: response.data.msg,
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'info',
                        hideAfter: 4000,
                        stack: 6
                    });
                } else if(response.code == 201) {
                    if(response.data.status == 1){
                        $('#sale_box_open_button').prop("disabled",true);
                        $('#sale_box_close_button').prop("disabled",false);
                        $('#add_item_no_stock_button').prop("disabled",false);
                        $('#sale_box_movements_button').prop('disabled', false);
                        $('#sale_box_balance').prop('disabled', false);

                    }
                    $('#box_sales').modal('hide');
                    $("#box_name").text('Caja : '+ response.data.name);

                } else {
                    $.toast({
                        heading: 'Error',
                        text: 'Error General',
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'info',
                        hideAfter: 4000,
                        stack: 6
                    });
                }

            });
        });

        $("#close_box_sale").click(function(){

            var data = new FormData();
            data.append('box_status','close');
            //data.append('cashier_id', $('#cashier_id').val());
            let real_cash = $('#real_cash').val();
            let total_box = totalBox;
            let valInfo  = new Object();
            @if($tipoCaja->value == "1")
                real_cash = finalReal;
                valInfo = parseCash();
                valInfo.contra = $("#total_contra").text();
            @endif
            data.append('box_coins', JSON.stringify(valInfo));
            data.append('real_cash', real_cash);
            data.append('total_box', total_box);

            $('#close_box_sale').attr('disabled', true);
            AWApi.post('{{ url('api/sale_box/update_sale_box_status')}}', data, function(response){
                $('#close_box_sale').attr('disabled', false);
                if(response.data.status == 0){
                    $('#sale_box_open_button').prop("disabled",false);
                    $('#sale_box_movements_button').prop('disabled', true);
                    $('#sale_box_close_button').prop("disabled",true);
                    $('#add_item_no_stock_button').prop("disabled",true);
                    $('#box_name').text('Caja : N/D');
                    $("#box_sales_close").modal("hide");
                    $('#sale_box_balance').prop('disabled', true);

                    $(".order").find(".order-lines").remove();
                    $(".summary").addClass("d-none");
                    $(".summary").find(".value").first().text("0");
                    $(".summary").find(".tax_value").first().text("0");
                    $(".summary").find(".total_value").first().text("0");
                    $(".txt_moneys").val('');
                    total_cart = 0;

                    AWApi.get('{{ url('api/pos/cart/delete/all')}}', function (response) {
                            if (response.code == 200) {
                                $.toast({
                                    heading: 'Carro vaciado',
                                    text: 'Éxito',
                                    position: 'top-right',
                                    loaderBg: '#ff6849',
                                    icon: 'info',
                                    hideAfter: 4000,
                                    stack: 6
                                });
                                getCartAndDisplay();
                                loadItems();
                            }
                    });
                }
            });
        });



        $("#cash_balance_box_sale").click(function(){

            var data = new FormData();
            data.append('box_status','balance');
            //data.append('cashier_id', $('#cashier_id').val());
            data.append('cash_balance', $('#cash_balance').val());
            data.append('balance_observation', $('#balance_observation').val());

            
            AWApi.post('{{ url('api/sale_box/update_sale_box_status')}}', data, function(response){
                if (response.code == 201) {
                    $('#cash_balance').val('');
                    $('#balance_observation').val('');
                    swal('Correcto','Se modificó el balance exitosamente','success');
                    
                }if (response.code == 401) {
                    $('#cash_balance').val('');
                    $('#balance_observation').val('');

                    $.toast({
                        heading: 'Error',
                        text: response.data.msg,
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'info',
                        hideAfter: 4000,
                        stack: 6
                    });
                }
            });
        });

        $('.add-client').on('click', () => {
            let data = new FormData();

            $('.client_id').removeClass('is-invalid');
            $('.client_name').removeClass('is-invalid');
            $('.client_email').removeClass('is-invalid');
            $('.client_industries').removeClass('is-invalid');
            $('.client_comune').removeClass('is-invalid');
            $('.error-client-id').empty();
            $('.error-client-name').empty();
            $('.error-client-email').empty();
            $('.error-client-industries').empty();
            $('.error-client-comune').empty();
            $('.error-client_address').empty();
            //si actualizo va esto 
            /*data.append('form[0][name]', "id_old");
            data.append('form[0][value]', $('.client_id').val());*/
            //error-client-id
            //5050
            if(!rutEsValido( $('.client_id').val())) {
                $('.client_id').addClass('is-invalid');
                $('.error-client-id').append('Rut inválido');
            }
            if($('.client_name').val() == null || $('.client_name').val() == '') {
                $('.client_name').addClass('is-invalid');
                $('.error-client-name').append('Campo requerido');
            }
            if($('.client_industries').val() == null || $('.client_industries').val() == '') {
                $('.client_industries').addClass('is-invalid');
                $('.error-client-industries').append('Giro requerido');
            }
            if($('#s_comune').val() == null || $('#s_comune').val() == '') {
                $('.client_comune').addClass('is-invalid');
                $('.error-client-comune').append('Comuna requerida');
            }
            if($('.client_address').val() == null || $('.client_address').val() == '') {
                $('.client_address').addClass('is-invalid');
                $('.error-client_address').append('Dirección requerida');
            }
            let clientMail = $('.client_email').val();
            clientMail.replaceAll(' ', '');
            if(clientMail.match(/,/g)) {
                let splitMail = clientMail.split(',');
                if(splitMail.length > 1) {
                    let mailAmt = splitMail.length;
                    let curIndex = 0;
                    let success = true;
                    for(let i = 0; i < mailAmt; i++) {
                        if(!validateEmail(splitMail[i]) || splitMail[i].trim() == "" || (splitMail[i].match(/@/g)).length != 1) {
                            success = false;
                            break;
                        }
                        curIndex++;
                    }
                    if(!success) {
                        $('.client_email').addClass('is-invalid');
                        $('.error-client-email').append('El correo: ' + splitMail[curIndex] + ' Es invalido');
                    }
                }
            } else {
                if(!validateEmail(clientMail)) {
                    $('.client_email').addClass('is-invalid');
                    $('.error-client-email').append('Email inválido');
                }
            }
            if($('.client_id').hasClass('is-invalid') || $('.client_name').hasClass('is-invalid')
            || $('.client_email').hasClass('is-invalid') ) {
                return null;
            }   

            data.append('rut', $('.client_id').val());
            data.append('name', $('.client_name').val());
            if($('.client_email').val().trim() != '') {
                data.append('email', $('.client_email').val());
            }
            data.append('address', $('.client_address').val());
            data.append('comune_id', $('#s_comune').val());
            data.append('industries', $('.client_industries').val());

            AWApi.post('{{ url('api/client/save')}}', data, function(response){
                if(response.code == 201) {
                    $('#myModal').modal('hide');
                } else {
                    switch(response.code) {
                        case 401:
                            $.toast({
                                heading: 'Error',
                                text: 'Error de validación',
                                position: 'top-right',
                                loaderBg: '#ff6849',
                                icon: 'error',
                                hideAfter: 7000,
                                stack: 6
                            });
                        break;
                        case 402:
                            $('.client_id').addClass('is-invalid');
                            $('.error-client-id').append('Rut ya existe en el sistema');
                        break;
                        case 500:
                            $.toast({
                                heading: 'Error',
                                text: 'Error del sistema',
                                position: 'top-right',
                                loaderBg: '#ff6849',
                                icon: 'error',
                                hideAfter: 7000,
                                stack: 6
                            });
                        break;
                    }
                }
            });

        });
       

        // Begin processing customer payment
        $("#btnPay").click(function () {
      
            $(".ticket_div").addClass('d-none');
            AWApi.get('{{ url('api/pos/cart/process')}}', function (response) {
                if (response.success[0].count > 0) {
                    
                    if(pos_mode == 'ticket') {
                        //var payment_total = $('.summary').find('.value').first().text().replace(",", "").replace(",", "");
                        //payment_total.replace(/./g, '');
                        $(".payment-total").text(formatMoney(total_cart,'CL'));
                        //2020
                    } else {
                        //var payment_total = $('.summary').find('.total_value').first().text().replace(",", "").replace(",", "");
                        //payment_total.replace(/./g, '');
                        $(".payment-total").text(formatMoney(total_cart,'CL'));
                    }
                    
                    /*var payment_total = currencyFormat.format(document.getElementsByClassName("summary")[0].firstElementChild.children[0]
                        .children[2].innerText.replace(",", "").replace(".00", "").replace(",", ""));*/
                    
                    
                    //$(".payment-total").text(payment_total);

                    // On pass of process conditions, move to cash tender and change
                    // Reveal sidebar drawer
                    openDrawer();
                }

          });
        });

        // Payment options buttons - Cash, Cheque, or Card
        $(".payment-btn").click(function (e) {
            e.preventDefault();
            let mode = $(this).attr('data-mode');

            $('#txtAmountTendered').val('');
            $('#txtChange').val('');
            $('#txtRemainingAmount').val(0);
            $('#txtCardOperation').val('');
            $('#txtCardObservations').val('');
            $('#txtRemarks').val('');
            $(".ticket_div").addClass('d-none');
            

            switch(mode) {
                case 'cash':
                    $(".payment-btn").removeClass("selected");
                    $(this).addClass("selected");
                    $("#cash-payment").removeClass("d-none");
                    $("#card-payment").addClass("d-none");
                    $("#cheque-payment").addClass("d-none");
                    $("#intern-payment").addClass("d-none");
                    $("#mix-payment").addClass("d-none");
                    $("#app-payment").addClass("d-none");
                    $("#transfer-payment").addClass("d-none");
                    $('.sell-button').addClass('d-none');
                    if (pos_mode == 'invoice') {
                        $("#credit_sale").prop("checked", false);
                        credit_sale = false;
                        $(".credit_sale").hide();
                        $(".credit_sale-date").hide();
                        $(".label-credit_sale-date").hide();
                    }
                    AWApi.get('{{ url('/api/configs/params') }}/ROUNDING', function (response) {
                        if(response.code == 200) {
                            if (response.data.value == 'ROUND_DOWN') {
                                let temp_total = parseInt(total_cart) % 10;
                                if (temp_total > 0) {
                                    adjust_cart  = temp_total;
                                    $(".payment-total").text(formatMoney(total_cart-adjust_cart,'CL')); 
                                }
                            } else {
                                //DEFAULT 
                                let temp_total = parseInt(total_cart) % 10;
                                if (temp_total > 0 && temp_total <= 5) {
                                    adjust_cart  = temp_total;
                                    $(".payment-total").text(formatMoney(total_cart-adjust_cart,'CL'));
                                }
                                if (temp_total > 5 && temp_total < 10) {
                                    adjust_cart = 10 - temp_total;
                                    $(".payment-total").text(formatMoney(total_cart+adjust_cart,'CL'));
                                    adjust_cart = adjust_cart * -1; 
                                }

                            }
                        }
                    });
                break;
                case 'cheque':
                    adjust_cart = 0;
                    if (pos_mode == 'invoice') {
                        $(".credit_sale").show();
                    }
                    $(".payment-total").text(formatMoney(total_cart,'CL'));
                    $(".payment-btn").removeClass("selected");
                    $(this).addClass("selected");
                    $("#cash-payment").addClass("d-none");
                    $("#card-payment").addClass("d-none");
                    $("#cheque-payment").removeClass("d-none");
                    $("#intern-payment").addClass("d-none");
                    $("#mix-payment").addClass("d-none");
                    $("#app-payment").addClass("d-none");
                    $("#transfer-payment").addClass("d-none");
                    $('.sell-button').addClass('d-none');
                    //cheque-payment
                    $('#txtChequeNumber').val(1);
                    listDocuments();
                break;
                case 'card':
                    adjust_cart = 0;
                    $(".payment-total").text(formatMoney(total_cart,'CL'));
                    $(".payment-btn").removeClass("selected");
                    $(this).addClass("selected");
                    $("#cash-payment").addClass("d-none");
                    $("#card-payment").removeClass("d-none");
                    $("#cheque-payment").addClass("d-none");
                    $("#intern-payment").addClass("d-none");
                    $("#mix-payment").addClass("d-none");
                    $("#app-payment").addClass("d-none");
                    $("#transfer-payment").addClass("d-none");
                    $('.sell-button').addClass('d-none');
                    if (pos_mode == 'invoice') {
                        $(".credit_sale").show();
                        $(".ticket_div").addClass('d-none');
                    } else {
                        $(".ticket_div").removeClass('d-none');
                    }
                break;
                case 'intern':
                    adjust_cart = 0;
                    $(".payment-total").text(formatMoney(total_cart,'CL'));
                    $(".payment-btn").removeClass("selected");
                    $(this).addClass("selected");
                    $("#cash-payment").addClass("d-none");
                    $("#card-payment").addClass("d-none");
                    $("#cheque-payment").addClass("d-none");
                    $("#mix-payment").addClass("d-none");
                    $("#app-payment").addClass("d-none");
                    $("#transfer-payment").addClass("d-none");
                    $("#intern-payment").removeClass("d-none");
                    $('.sell-button').removeClass('d-none');
                    if (pos_mode == 'invoice') {
                        $(".credit_sale").show();
                        $(".ticket_div").addClass('d-none');
                    } else {
                        $(".ticket_div").removeClass('d-none');
                    }
                break;
                case 'mix':
                    adjust_cart = 0;
                    $(".payment-total").text(formatMoney(total_cart,'CL'));
                    $(".payment-btn").removeClass("selected");
                    $("#cash-payment").addClass("d-none");
                    $("#card-payment").addClass("d-none");
                    $("#cheque-payment").addClass("d-none");
                    $("#intern-payment").addClass("d-none");
                    $("#mix-payment").removeClass("d-none");
                    $("#app-payment").addClass("d-none");
                    $("#transfer-payment").addClass("d-none");
                    mix_list = [];
                    $('#select_type').val(1);
                    $('.input-mix').val(0);
                    $('.obs-mix').val('');
                    $(this).addClass("selected");
                    renderMixList();
                    if (pos_mode == 'invoice') {
                        $(".credit_sale").show();
                    }
                break;
                case 'app':
                    $(".payment-btn").removeClass("selected");
                    $(this).addClass("selected");
                    $("#cash-payment").addClass("d-none");
                    $("#card-payment").addClass("d-none");
                    $("#cheque-payment").addClass("d-none");
                    $("#mix-payment").addClass("d-none");
                    $("#intern-payment").addClass('d-none');
                    $("#app-payment").removeClass('d-none');
                    $("#transfer-payment").addClass("d-none");
                break;
                case 'transfer':
                    $(".payment-btn").removeClass("selected");
                    $(this).addClass("selected");
                    $("#cash-payment").addClass("d-none");
                    $("#card-payment").addClass("d-none");
                    $("#cheque-payment").addClass("d-none");
                    $("#mix-payment").addClass("d-none");
                    $("#intern-payment").addClass('d-none');
                    $("#app-payment").addClass('d-none');
                    $("#transfer-payment").removeClass('d-none');
                break;

            }
        });

        // Document options buttons - invoice or ticket
        $(".document-btn").click(function (e) {
            e.preventDefault();
            $(".document-btn").removeClass("selected");
            $(this).addClass("selected");
            if($(".document-btn.selected").data("mode") != "invoice"){
               $("#payment-method").removeClass("d-none");
               $("#document-type").addClass("d-none");
               $("#client-select").removeClass("d-none")
               $(".rut-select").removeClass('d-none');
               $(".ticket_div").addClass('d-none');
               ignore_rut = false;
               $('#checkbox_rut').prop('checked',false);
            }else{
               $("#client-select").removeClass("d-none");
               $("#payment-method").removeClass("d-none");
               $("#document-type").addClass("d-none"); 
               $(".rut-select").addClass('d-none');
               $(".ticket_div").addClass('d-none');
               ignore_rut = false;
               $('#checkbox_rut').prop('checked',false);
            }
           
            
        });
        $("#go_back_document_type").click(function(e){
            e.preventDefault();
            $("#payment-method").addClass("d-none");
            $("#document-type").removeClass("d-none");
        });


        // Handle all cash tendered and remaining balance/change values
        $(".cash-tendered").keyup(function () {
            var cash_value = $(this).val();
            /*var cart_total_payment = document.getElementsByClassName("summary")[0].firstElementChild.children[0]
                .children[2].innerText.replace(",", "").replace(".00", "").replace(",", "");*/
            if(pos_mode == 'ticket') {
                var cart_total_payment = total_cart;//$('.summary').find('.value').first().text().replace(",", "").replace(".00", "").replace(/\./g, "");
            } else {
                var cart_total_payment = total_cart;//$('.summary').find('.total_value').first().text().replace(",", "").replace(".00", "").replace(/\./g, "");
            }


            var change = (parseInt(cart_total_payment, 10) - parseInt(adjust_cart, 10)) - parseInt(cash_value, 10);
            if (change > 0) {
                $(".cash-change").val("0");
                $(".cash-remaining-amount").val(change);
            } else if (change < 0) {
                $(".cash-change").val(change.toString().replace("-", ""));
                $(".cash-remaining-amount").val("0");
            } else {
                $(".cash-change").val("0");
                $(".cash-remaining-amount").val("0");
            }

            if (parseInt($(".cash-remaining-amount").val(), 10) != 0) {
                $(".sell-button").removeClass("d-none");
            }
        });
        $(".card-operation").keyup(function() {
            $(".sell-button").removeClass("d-none");
        });

        $("#selectAppOperation").change(function() {
            let appId = $(this).val();
            if(appId == "-1" || $("#txtAppVoucher").val().trim() == "") {
                $(".sell-button").addClass("d-none");
            } else {
                $(".sell-button").removeClass("d-none");
            }
        });

        $("#txtAppVoucher").keyup(function() {
            let voucherVal = $(this).val().trim();
            if($("#selectAppOperation").val() == "-1" || voucherVal == "") {
                $(".sell-button").addClass("d-none");
            } else {
                $(".sell-button").removeClass("d-none");
            }
        });

        $("#txtClientNameTransfer").keyup(function() {
            let clientName = $(this).val().trim();
            if(clientName == "") {
                $(".sell-button").addClass("d-none");
            } else {
                $(".sell-button").removeClass("d-none");
            }
        });


        // Reduce product stock according to sold quantity
        $(".sell-button").click(function () {
            // Ensure cash tendered is not empty
            $(".sell-button").attr('disabled', true);
            var payment_method = $(".payment-btn.selected").data("mode");
            var document_type = pos_mode; //$(".document-btn.selected").data("mode");

            var data = new FormData();
            data.append('document_type', document_type);
            data.append('payment_method', payment_method);
            data.append('seller', $('#sellers').val());
            data.append('date',moment(new Date()).format('YYYY-MM-DD'));
            data.append('client_id', $('#select_rut').val() != '' ? $('#select_rut').val() : 0);
            data.append('ignore_rut', ignore_rut);
            data.append('order_note', order_number);
            data.append('letter', $('#checkbox_letter').prop("checked"));

            if (mix_references.length > 0) {
                data.append('invoice_references', JSON.stringify(mix_references));
            }
            //8080
            switch(payment_method) {
                case 'cash' :
                    var tendered = $(".cash-tendered").val();
                    if (parseInt(tendered, 10) == 0) {
                        $.toast({
                            heading: 'Invalid Amount',
                            text: "Dinero en efectivo no puede ser 0",
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'error',
                            hideAfter: 7000,
                            stack: 6
                        });
                        $(".sell-button").attr('disabled', false);
                        return;
                    }
                    // Get change and balance remaining
                    var change = $(".cash-change").val();
                    var remaining = $(".cash-remaining-amount").val();
                    if (parseInt(remaining, 10) != 0) {
                        $.toast({
                            heading: 'Invalid Amount',
                            text: "Cantidad restante no puede ser mayor a 0",
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'error',
                            hideAfter: 7000,
                            stack: 6
                        });
                        $(".sell-button").attr('disabled', false);
                        return;
                    }
                    var remarks = $("#txtRemarks").val();
                    data.append('change', change);
                    data.append('remaining', remaining);
                    data.append('observations', remarks);
                    data.append('amount_tendered',tendered);
                    data.append('diff', adjust_cart);
                break;
                case 'card' :
                    data.append('operation', $('#txtCardOperation').val());
                    data.append('observations', $('#txtCardObservations').val());
                    data.append('credit_sale', credit_sale);
                    data.append('FhcVenc', moment($("#FhcVenc").val(), "DD/MM/YYYY").format("YYYY-MM-DD") );
                    if(document_type == 'ticket') {
                        data.append('submit_ticket', $("#submit_ticket").is(':checked'));
                    } 
                break;
                case 'cheque' :
                    data.append('ndocs', $('#txtChequeNumber').val());
                    data.append('observations', $('#txtChequeObservation').val());
                    data.append('credit_sale', credit_sale);
                    data.append('FhcVenc', moment($("#FhcVenc").val(), "DD/MM/YYYY").format("YYYY-MM-DD") );
                    let info = [];
                    $("#clist tr").each(function () {
                        info.push({
                            'docnumber' :  $(this).find('td').eq(0).find('input').eq(0).val(),
                            'amount'    :  $(this).find('td').eq(1).find('input').eq(0).val(),
                            'date'      :  $(this).find('td').eq(2).find('input').eq(0).val(),
                            'bank'      :  $(this).find('td').eq(3).find('select').eq(0).val()
                        });
                    });
                    data.append('docs', JSON.stringify(info));
                break;
                case 'mix' :
                    data.append('docs', JSON.stringify(mix_list));
                break;
                case 'intern' :
                    data.append('observations', $('#internObservation').val());
                    data.append('credit_sale', credit_sale);
                    data.append('FhcVenc', moment($("#FhcVenc").val(), "DD/MM/YYYY").format("YYYY-MM-DD") );
                    if(document_type == 'ticket') {
                        data.append('submit_ticket', $("#submit_ticket").is(':checked'));
                    }
                break;
                case 'app' : 
                    if($("#txtAppVoucher").val().trim() == "" || $("#selectAppOperation").val() == -1) {
                        $.toast({
                            heading: 'Invalid Values',
                            text: "Se debe ingresar un numero de voucher y una app asociada",
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'error',
                            hideAfter: 7000,
                            stack: 6
                        });
                        return;
                    }
                    data.append('deliveryappvoucher', $("#txtAppVoucher").val());
                    data.append('deliveryapp', $("#selectAppOperation").val());
                break;
                case 'transfer':
                    if($("#txtClientNameTransfer").val().trim() == "") {
                        $.toast({
                            heading: 'Invalid Values',
                            text: "Se debe ingresar el nombre de cliente",
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'error',
                            hideAfter: 7000,
                            stack: 6
                        });
                        return;
                    }
                    data.append('transferclient', $("#txtClientNameTransfer").val());
                break;
            }
            
            //////////////////////////////////////////////
            AWApi.post('{{ url('api/pos/cart/sell')}}', data, function(response){
                $(".sell-button").attr('disabled', false);
                if (response.code == 401) {
                        $.toast({
                            heading: 'Error',
                            text: response.data.msg,
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'error',
                            hideAfter: 7000,
                            stack: 6
                        });
                    } else {
                        if(response.code == 500) {
                            $.toast({
                                heading: 'Error',
                                text: 'Error del sistema, contacte con administrador',
                                position: 'top-right',
                                loaderBg: '#ff6849',
                                icon: 'error',
                                hideAfter: 7000,
                                stack: 6
                            });
                            return;
                        }
                        if(response.hasOwnProperty('success')) {
                            $("#trans-no").text(response.afe_response.folio);
                            file = response.afe_response.ruta;
                            delivery_guide = response.id;
                            try{
                                data.append('document_type', document_type);
                                data.append('payment_method', payment_method);
                                if(!$("#submit_ticket").is(':checked') && document_type == 'ticket' && payment_method == 'card') {
                                    AWApi.get('{{ url('api/pos/replacementTicket/print/') }}' + '/' +response.id, function(innerResponse) {
                                            if(innerResponse.code == 200 && innerResponse.data && innerResponse.data.url) {
                                                try {
                                                    printUrl = innerResponse.data.url;
                                                    printService.submit({
                                                        'type': 'INVOICE',
                                                        'url': innerResponse.data.url
                                                    });
                                                } catch(err) {
                                                    swal('Problema','Hay un problema con el sistema de impresión automatica, por favor imprima de forma manual. \n Si el problema persiste, contacte al administrador','warning');
                                                }
                                            }
                                    });
                                } else {
                                    printUrl = AFE_URL_API+'/'+file+'?api_token='+API_TOKEN;
                                    printService.submit({
                                        'type': 'INVOICE',
                                        // 'url': AFE_URL_API+'/'+file+'?api_token='+API_TOKEN
                                        'url': AFE_URL_API+'/'+file
                                    });
                                }
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
                                swal('Problema','Hay un problema con el sistema de impresión automatica, por favor imprima de forma manual. \n Si el problema persiste, contacte al administrador','warning');
                            }

                            

                            
                            

                            // Append items to table
                            var items = response.items;
                            var count = 1;
                            $.each(items, function (k, v) {
                                var row = "<tr>";
                                row += "<td class=\"text-center\">" + count + "</td>";
                                row += "<td>" + v.name + "</td>";
                                row += "<td class=\"text-right\">" + v.quantity + "</td>";
                                row += "<td class=\"text-right\">" + (pos_mode == 'ticket' ? currencyFormat.format(v.price) : currencyFormat.format(Math.round(v.net_price))  )    + "</td>";
                                row += "<td class=\"text-right\">" + (pos_mode == 'ticket' ? currencyFormat.format(v.discount_tax) : currencyFormat.format(Math.round(v.discount_no_tax))  )    + "</td>";
                                row += "<td class=\"text-right\">" + (pos_mode == 'ticket' ? currencyFormat.format(v.total_tax ) : currencyFormat.format(Math.round(v.total_no_tax))  ) + "</td>";
                                $("#receipt-table").append(row);
                                count++;
                            });
                            
                            if (pos_mode == 'invoice'){
                                if (response.payment_method == 'cash'){
                                    $("#sell-total").text(currencyFormat.format(Math.round(Math.round(response.total_amount*1.19)/10)*10));
                                }else{
                                    $("#sell-total").text(currencyFormat.format(Math.round(response.total_amount*1.19)));

                                }
                                if (response.payment_method == 'intern') {
                                $("#amount-paid").text(currencyFormat.format(Math.round(response.amount_tendered*1.19)));
                                }else{
                                $("#amount-paid").text(currencyFormat.format(response.amount_tendered));
                                }

                            }else{
                            // Total value
                            $("#sell-total").text(currencyFormat.format(response.total_amount));
                            $("#amount-paid").text(currencyFormat.format(response.amount_tendered));
                            }
                            $("#paid-change").text(currencyFormat.format(response.change_amount));

                            // Organisation Address & Contact
                            var _address = '';
                            var _contact = '';
                            if(response.settings) {
                                _address = response.settings.org_address;
                                _contact = response.settings.org_contact;
                            }
                            $("#org-address").text(_address);
                            $("#org-contact").text(_contact);

                            closeDrawer();
                            showReceipt();

                        } else {
                            $.toast({
                            heading: 'Error',
                            text: 'Error del sistema, contacte con administrador',
                            position: 'top-right',
                            loaderBg: '#ff6849',
                            icon: 'error',
                            hideAfter: 7000,
                            stack: 6
                        });
                        }
                    }
             });
        });

        // Print receipt

        $("#print").click(function() {
            window.open(printUrl, "mywindow");
            

           /* var mode = 'popup' //'iframe'; //popup
            var close = true;
            var options = {
                mode: mode,
                popClose: close
            };
            $("div.printableArea").printArea(options);*/
        });

        
        $("#printguide").click(function() {
            //delivery_guide
            AWApi.get('{{ url('api/pos/sale')}}/'+delivery_guide+'/print', function (response) {
                if(response.code == 200) {
                    window.open(response.data.url,"mywindow1");
                }
            });
        });

       

        // Remove all items from cart
        $("#btnClearCart").click(function () {
            // Remove all cart items
            $(".order").find(".order-lines").remove();

            // Hide summary div and set total price to zero
            $(".summary").addClass("d-none");
            $(".summary").find(".value").first().text("0");
            $(".summary").find(".tax_value").first().text("0");
            $(".summary").find(".total_value").first().text("0");
            total_cart = 0;

            // Clear cart from session using ajax
            AWApi.get('{{ url('api/pos/cart/delete/all')}}', function (response) {
                if (response.code == 200) {
                    $.toast({
                        heading: 'Carro vaciado',
                        text: 'Éxito',
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'info',
                        hideAfter: 4000,
                        stack: 6
                    });
                    getCartAndDisplay();
                    loadItems();
                }
            });
        });

        // Customize scroll button for cart display
        /*$('.order-scroll').slimScroll({
            position: 'right',
            size: "5px",
            height: '100%'
        });*/

        

        // On clicking a mode button, toggle the .selected-mode class
        // to indicate the active mode
        $(".mode-button").click(function () {
            if ($(".mode-button").has("selected-mode")) {
                $(".mode-button").removeClass("selected-mode");
                $(this).addClass("selected-mode");
            }
        });

        // Numbers button
        $(".number-char").click(function () {
            let numberpad = $(this);
            numberpad.attr('disabled', true);
            if ($(".selected").length > 0) {
                clicked++;
                price_clicked++;
                var mode = $(".selected-mode").data("mode");
                var item_qty = document.getElementsByClassName("selected")[0].children[2].children[0].children[0].children[0];
                var item_unit_price = document.getElementsByClassName("selected")[0].children[2].children[0].children[2];
                var item_total_price = parseFloat(item_qty.innerText * item_unit_price.innerText);
                var cart_total_price = $(".summary").find(".value").first().text().replace(",", "").replace(".00", "");
                var commit = true;

                switch (mode) {
                    case "quantity":
                        if (price_clicked > 0) price_clicked = 0;

                        if ($(this).text() == ".") {
                            if (clicked == 1) {
                                clicked = 0;
                            }
                            return;
                        }

                        var num = parseInt($(this).text(), 10);
                        let qty;
                        if (clicked == 1) {
                            qty = num;
                        } else {
                            qty = item_qty.innerText + num;
                        }

                        let formData = new Object();
                        formData.item_id = $(".selected").parent().data("id");
                        formData.stock   = qty;
                        AWApi.get('{{ url('api/pos/cart/stock')}}?'+$.param(formData), function (response) {
                            numberpad.attr('disabled', false);
                            if(response.code == 200) {
                                if (response.data.success) {
                                    if (clicked == 1) {
                                        item_qty.innerText = num;
                                        clicked++;
                                        remove = 0;
                                    } else {
                                        if (item_qty.innerText == '0') {
                                            item_qty.innerText = num;
                                        } else {
                                            item_qty.innerText = item_qty.innerText + num;
                                        }

                                    }
                                    // Re-calculate item and cart total price based on current qty
                                    var updatedItemTotalPrice = parseFloat(item_qty.innerText * item_unit_price.innerText);
                                    var updatedCartTotalPrice = updatedItemTotalPrice + ((parseFloat(cart_total_price) - item_total_price));

                                    // Update item and cart total price
                                    document.getElementsByClassName("selected")[0].children[1].children[1].innerText = currencyFormat.format(updatedItemTotalPrice);
                                    $(".summary").find(".value").first().text(currencyFormat.format(updatedCartTotalPrice));
                                    var id = $(".selected").parent().data("id");
                                    var name = $(".selected").parent().data("name");
                                    UpdateProduct([id, name, item_unit_price.innerText, item_qty.innerText]);

                                } else {
                                    $.toast({
                                        heading: 'Stock insuficiente',
                                        text: 'Error',
                                        position: 'top-right',
                                        loaderBg: '#ff6849',
                                        icon: 'info',
                                        hideAfter: 2000,
                                        stack: 6
                                    });
                                    clicked = 0;
                                }
                            } else {
                                commit = false;
                                clicked = 0;
                            }
                        });
                        
                        break;
                    case "discount":
                        if ($(this).text() == ".") {
                            return;
                        }
                        if (clicked > 0) clicked = 0;
                        if (price_clicked > 0) price_clicked = 0;

                        var id = $(".selected").parent().data("id");
                        var name = $(".selected").parent().data("name");
                        UpdateProduct([id, name, item_unit_price.innerText, item_qty.innerText]);
                        break;
                    case "price":
                        if ($(this).text() == ".") {
                            if (price_clicked == 1) {
                                price_clicked = 0;
                            }
                            return;
                        }
                        if (clicked > 0) clicked = 0;
                        var price = parseFloat($(this).text());

                        if (price_clicked == 1) {
                            item_unit_price.innerText = price;
                            price_clicked++;
                        } else {
                            item_unit_price.innerText = item_unit_price.innerText + price;
                        }

                        // Re-calculate item and cart total price based on current price
                        var updatedItemTotalPrice = parseFloat(item_qty.innerText * item_unit_price.innerText);
                        var updatedCartTotalPrice = updatedItemTotalPrice + ((parseFloat(cart_total_price) - item_total_price));

                        // Update item and cart total price
                        document.getElementsByClassName("selected")[0].children[1].children[1].innerText = currencyFormat.format(updatedItemTotalPrice);
                        $(".summary").find(".value").first().text(currencyFormat.format(updatedCartTotalPrice));

                        var id = $(".selected").parent().data("id");
                        var name = $(".selected").parent().data("name");
                        UpdateProduct([id, name, item_unit_price.innerText, item_qty.innerText]);
                        break;
                }
                
            }
        });

        // Backward delete/remove button
        $(".numpad-backspace").click(function () {
            if ($(".selected").length > 0) {
                var mode = $(".selected-mode").data("mode");
                var item_unit_price = document.getElementsByClassName("selected")[0].children[2].children[0].children[2];
                var itemQty = document.getElementsByClassName("selected")[0].children[2].children[0].children[0].children[0];
                var item_total_price = parseFloat(itemQty.innerText) * parseFloat(item_unit_price.innerText);
                var cart_total_price = $(".summary").find(".value").first().text().replace(".00", "").replace(",", "").replace(",", "").replace(",", "");
                var commit = true;
                switch (mode) {
                    case "quantity":
                        if (remove == 0) {
                            if (itemQty.innerText.length == 1) {
                                itemQty.innerText = 0;
                                clicked = 0;
                                remove = 1;
                            } else {
                                itemQty.innerText = itemQty.innerText.substring(0, (itemQty.innerText.length - 1));
                            }

                            // Re-calculate item and cart total price based on current qty
                            var updatedItemTotalPrice = parseFloat(itemQty.innerText * item_unit_price.innerText);
                            var updatedCartTotalPrice = parseFloat(cart_total_price) - (item_total_price - updatedItemTotalPrice);

                            //UpdateProduct([item_id, item_name, item_unit_price.innerText, cart_item.innerText]);

                            // Update item and cart total price
                            document.getElementsByClassName("selected")[0].children[1].children[1].innerText = currencyFormat.format(updatedItemTotalPrice);
                            $(".summary").find(".value").first().text(currencyFormat.format(updatedCartTotalPrice));

                        } else {
                            remove = 0;

                            // Remove item from CartCollection
                            var item_id = $(".selected").parent().data("id");
                            //RemoveProduct(item_id);

                            // Remove from UI
                            $(".selected").parent().remove();
                            if ($(".selected").find().length == 0 && $(".order-lines").find().prevObject.length == 0) {
                                $(".summary").addClass("d-none");
                                $(".order-empty").removeClass("d-none");
                            }

                        }
                        break;
                    case "discount":
                        break;
                    case "price":
                        if (price_remove == 0) {
                            if (item_unit_price.innerText.length == 1 || item_unit_price.innerText == "0.00") {
                                item_unit_price.innerText = "0.00";
                                clicked = 0;
                                price_clicked = 0;
                                price_remove = 1;
                            } else {
                                item_unit_price.innerText = item_unit_price.innerText.substring(0, (item_unit_price.innerText.length - 1));
                            }

                            // Re-calculate item and cart total price based on current qty
                            var updatedItemSubTotal = itemQty.innerText * item_unit_price.innerText;
                            var updatedCartTotal = parseFloat(cart_total_price) - (item_total_price - updatedItemSubTotal);

                            // Update item and cart total price
                            document.getElementsByClassName("selected")[0].children[1].children[1].innerText = currencyFormat.format(updatedItemSubTotal);
                            $(".summary").find(".value").first().text(currencyFormat.format(updatedCartTotal));
                        } else {
                            price_remove = 0;
                        }
                        break;
                }

                if ($(".selected").length > 0 && commit) {
                    var id = $(".selected").parent().data("id");
                    var name = $(".selected").parent().data("name");
                    UpdateProduct([id, name, item_unit_price.innerText, itemQty.innerText]);
                }
            }
        });

        AWApi.get('{{ url('/api/configs/params') }}/CASH', function (response) {
            if (response.code == 200){
                if(response.data.value == 0){
                $('#payment-method_cash').hide()
                $('#total_cash').hide()
                $('#t_total_cash').hide()
                jQuery("#select_type option:contains('Efectivo')").remove();
                }
            }
            });

        AWApi.get('{{ url('/api/configs/params') }}/CHEQUE', function (response) {
            if (response.code == 200){
                if(response.data.value == 0){
                    $('#payment-method_cheque').hide()
                    $('#total_cheque').hide()
                    $('#t_total_cheque').hide()
                    jQuery("#select_type option:contains('Cheque')").remove();
                }
            }
            });

        AWApi.get('{{ url('/api/configs/params') }}/CARD', function (response) {
            if (response.code == 200){
                if(response.data.value == 0){
                    $('#payment-method_card').hide()
                    $('#total_card').hide()
                    $('#t_total_card').hide()
                    jQuery("#select_type option:contains('Tarjeta')").remove();
                } else {
                    $("#submit_ticket").prop('checked', true);
                    AWApi.get('{{ url('/api/configs/params') }}/CREDIT_GENERATE_TICKET', function(response) {
                        if(response.code == 200) {
                            if(response.data && response.data.value == 0) {
                                $("#submit_ticket").prop('checked', false);
                                submitDefault = false;
                            } else {
                                $("#submit_ticket").prop('checked', true);
                            }
                        }
                    });
                }
            }
            });

        AWApi.get('{{ url('/api/configs/params') }}/INTERN', function (response) {
            if (response.code == 200){
                if(response.data.value == 0){
                    $('#payment-method_intern').hide()
                    $('#total_intern').hide()
                    $('#t_total_intern').hide()
                    jQuery("#select_type option:contains('Interno')").remove();
                }
            }
        });

        AWApi.get('{{ url('/api/configs/params') }}/MIX', function (response) {
            if (response.code == 200){
                if(response.data.value == 0){
                    $('#payment-method_mix').hide()
                }
            }
            
        });

        AWApi.get('{{ url('/api/configs/params') }}/APP', function (response) {
            $('#payment-method_app').hide();
            if (response.code == 200){
                if(response.data) {
                    if (response.data.value == 1){
                        $("#payment-method_app").show();
                        AWApi.get('{{ url('/api/AppPaymentTypes') }}', function (innerResponse) {
                            console.log({innerResponse});
                            let select = $("#selectAppOperation");
                            if(innerResponse.code == 404) {
                                    let option = document.createElement("OPTION");
                                    option.value = '-1';
                                    option.innerHTML = "FALTA HABILITAR OPCIONES DE APLICACIONES";
                                    select.append(option);
                            } else if(innerResponse.data && innerResponse.data.rows) {
                                if(innerResponse.data.rows.length == 0) {
                                    let option = document.createElement("OPTION");
                                    option.value = '-1';
                                    option.innerHTML = "SIN OPCIONES";
                                    select.append(option);
                                } else {
                                    let option = document.createElement("OPTION");
                                    option.value = '-1';
                                    option.innerHTML = "Seleccionar Aplicación";
                                    select.append(option);
                                    innerResponse.data.rows.forEach(element => {
                                        let option = document.createElement("OPTION");
                                        option.value = element.name;
                                        option.innerHTML = element.name;
                                        select.append(option);
                                    });
                                }
                                //FILL SELECT
                            } else {
                                    let option = document.createElement("OPTION");
                                    option.value = '-1';
                                    option.innerHTML = "SIN OPCIONES";
                                    select.append(option);
                            }
                        });
                    } else {
                        $('#payment-method_app').hide();
                        $("#t_total_app").hide();
                        $("#total_app").hide();
                    }
                }
            }
        });

        AWApi.get('{{ url('/api/configs/params') }}/TRANSFER', function (response) {
            if (response.code == 200){
                if(response.data.value == 0){
                    $('#payment-method_transfer').hide();
                    $("#t_total_transfer").hide();
                    $("#total_transfer").hide();
                }
            } else {
                $('#payment-method_transfer').hide();
                $("#t_total_transfer").hide();
                $("#total_transfer").hide();
            }
            
        });

    });

    function recalcRemaining() {
        let total_mix = 0;
        mix_list.forEach(function(row) {
            total_mix += parseInt(row.amount);
        })
        remaining = total_cart - total_mix;
        let checkRemaining = remaining;
        if(total_cart < total_mix) {
            checkRemaining = 0;
        }
        let change = 0;
        let cashIndex = [];
        mix_list.forEach(function(row) {
            if(row.type == 1) {
                if(remaining <= 0) {
                    if(row.amount >= Math.abs(remaining)) {
                        row.change = -Math.abs(remaining);
                    } else {
                        row.change = -row.amount;
                    }
                } else {
                    if(row.amount >= Math.abs(remaining)) {
                        row.change = 0;
                    }
                }
                remaining -= row.change;
            }
        });
    }

    function renderOTitle() {
        if(order_number != null) {
            $('.payment-title').text('Proceso de pago | N. de pedido : '+order_number);
        } else {
            $('.payment-title').text('Proceso de pago | N. de pedido : S/N');
        }
        
    }

    function llamaGuia (id){

        AWApi.get('{{ url('api/pos/sale')}}/'+id+'/print', function (response) {
                if(response.code == 200) {
                    window.open(response.data.url,"mywindow1");
                }
            });

    }

    function callVoucher(rutClient,rut_dvClient,docType,folio){
       
       
       AWApi.get('{{ url('api/pos/vouchers')}}/?rut_user='+rutClient+'-'+rut_dvClient+'&type='+docType+'&folio='+folio, function (response) {
           if(response.code == 200) {

                   window.open(AFE_URL_API+'/'+response.data.data.ruta+'?api_token='+API_TOKEN,"mywindow");
               }
           });

   }

    function searchBarcode() {

        var barcode = $("#txtSearch").val();
         data = new Object();
         filters = new Object();
         filters.barcode = barcode;
         data.filters = filters;
         let iva = 0.19;
         AWApi.get('{{ url('api/pos/barcode-item')}}?'+$.param(data), function (response) {
                    switch(response.code) {
                        case 200:
                            var id = response.data[0].id;
                            var name = response.data[0].name;
                            var price = response.data[0].actual_price ;
                            var qty = 1;
                            var item_exists = document.getElementById(id);
                            if(item_exists){
                                /*qty = item_exists.firstElementChild.children[2].children[0].children[0]
                                            .children["prod-quantity"].innerHTML;*/
                                qty = $(".cart-item-"+id).val();
                                var product_quantity = (parseInt(qty, 10) + 1);
                                AddProductToCart([id, name, price, product_quantity]);
                                //getCartAndDisplay();
                            }else{
                                if (!$(".order-empty").hasClass("d-none")) {
                                    $(".order-empty").addClass("d-none");
                            }
                                //getCartAndDisplay();
                                AddProductToCart([id, name, price, qty]);
                            }
                        break;
                        case 404:
                            $.toast({
                                heading: 'Producto no existe o sin stock',
                                text: 'Error',
                                position: 'top-right',
                                loaderBg: '#ff6849',
                                icon: 'info',
                                hideAfter: 2000,
                                stack: 6
                            });
                        break;
                        case 500:
                            $.toast({
                                heading: 'Error del sistema',
                                text: 'Error',
                                position: 'top-right',
                                loaderBg: '#ff6849',
                                icon: 'info',
                                hideAfter: 2000,
                                stack: 6
                            });
                        break;
                        case 403:
                            $.toast({
                                heading: response.data.msg,
                                text: 'Error',
                                position: 'top-right',
                                loaderBg: '#ff6849',
                                icon: 'info',
                                hideAfter: 2000,
                                stack: 6
                            });
                        break;
                        case 201:
                            $('#sellers').val(response.data.seller_id);
                            order_number = response.data.onote_id;
                            if(response.data.type == 1) {
                                $('.btn-ticket').click();
                            } else {
                                $('.btn-invoice').click();
                            }
                            if(response.data.client_id != null) {
                                AWApi.get('{{ url('/api/clients/') }}/'+response.data.client_id, function(res) {
                                    if(res.code == 200) {
                                        $('.simple-select1-sm').find('option').remove();
                                        $('.simple-select2-sm').find('option').remove();
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

                                        if (res.data.has_discount === 1){
                                            swal({
                                                title: "Descuento de cliente",
                                                text: "¿ Desea aplicar el descuento de "+ res.data.discount_percent +"% aplicado al cliente "+ res.data.name +" ?",
                                                icon: "warning",
                                                buttons: [
                                                    'No',
                                                    'Si'
                                                ]
                                                }).then(function(isConfirm) {
                                                    if (isConfirm) {
                                                        $('#discount_number').val(res.data.discount_percent);
                                                        applyDiscount();
                                                        
                                                        swal({
                                                        title: '¡ Descuento aplicado !',
                                                        
                                                        icon: 'success'
                                                        })
                                                    }});
                                                

                                                
                                                
                                        }
                                        $('#select_client').html(options);
                                        $('.simple-select2-sm').selectpicker('refresh');
                                        $('.simple-select2-sm').selectpicker('val', res.data.id);
                                    }
                                });

                            } else {
                                $('.simple-select1-sm').selectpicker('val','');
                                $('.simple-select2-sm').selectpicker('val','');
                            }

                            $('#datacells').empty();
                            if(response.data.observations.length > 0) {
                                //2020
                                let rows = '';
                                response.data.observations.map( function(obs) {
                                    rows += '<tr><td>'+obs[0]+'</td><td>'+obs[1]+'</td><td>'+obs[2]+'</td></tr>';
                                });
                                $('#datacells').append(rows);
                                $("#order_note_observations").modal("show");
                            }
                            loadItems();
                            getCartAndDisplay();
                            renderOTitle();
                        break;
                    }
                    

                        
                });
         $("#txtSearch").val('');
        
    }

    function applyDiscount() {
        let discount = $('#discount_number').val();
        let formData = new FormData();
        formData.append('discount_number', discount);
        AWApi.post('{{ url('api/pos/cart/discount')}}', formData, function(response){
            if (response.code == 200) {
                getCartAndDisplay();      
            } else {
                $.toast({
                    heading: "Error al aplicar descuento",
                    text: response.data.msg,
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: "info",
                    hideAfter: 6000,
                    stack: 6
                });
            }
        });
    }

    function createSelectDiscount(id, index) {

        html = null;
        html = "<select id ='"+id+"' onChange = 'changeDiscount(this)' class = 'desc_right'> ";
        for (var i = 0 ; i < discounts.length ; i++) {
        var item = discounts[i];

        //html += "<option value ='"+item.percent+"'>"+item.name+"</option>";
        html += '<option '+(item.id == index ? 'selected':'')+' value ='+'{"value":'+item.percent+',"id":'+item.id+'}'+'>'+item.name+'</option>';

        }
        html += "</select>"

       return html;
    }

    function changeDiscount(selectObject){

       var id = selectObject.id;
       updateDiscountItem(id, selectObject.value);
    


    }
    // add or update discount to item
    function updateDiscountItem(id, value) {
        var id = id;
        var valueToJson = JSON.parse(value);
        var discountValue = valueToJson.value;
        var discountId    = valueToJson.id;


        var data = new FormData();
        data.append('batch_id', id);
        data.append('discount_id', discountId)
        AWApi.post('{{ url('api/pos/cart/update-discount')}}', data, function(response){

               getCartAndDisplay();
               /*var item_exists = document.getElementById(id);
                //SELECT CART TO EDIT
               if (!item_exists.firstElementChild.classList.contains("selected")) {
                    item_exists.firstElementChild.classList.add("selected");
                }

                //SELECT QTY PRODUCT
                var qty = item_exists.firstElementChild.children[2].children[0].children[0]
                    .children["prod-quantity"].innerHTML;

                // SELECT PRICE TO PRODUCT
                var item_price = item_exists.firstElementChild.children[2].children[0].children[2].innerText;

                // DISCOUNT WITH COMBOBOX

                var discount = item_price - Math.round((item_price * discountValue)/100 );

                if(discountValue > 0){
                // SET SUBTOTAL PRICE WITH DISCOUNT
                    document.getElementsByClassName("selected")[0].children[1].children[1].innerText = currencyFormat.format(discount * qty);
                }else{
                // SET SUBTOTAL PRICE
                    document.getElementsByClassName("selected")[0].children[1].children[1].innerText = currencyFormat.format(item_price * qty);
                }

                var leer_carrito = document.getElementsByClassName("order-list")[0].children.length;

                var cart_total_payment = document.getElementsByClassName("summary")[0].firstElementChild.children[0]
                        .children[2].innerText.replace(",", "").replace(".00", "").replace(",", "");

                //SET TOTAL PRICE
                updateCartGlobal(id,null, null, null, discountValue, true);*/
                /*document.getElementsByClassName("summary")[0]
                    .firstElementChild.children[0].children[2]
                    .innerText = currencyFormat.format(cart_total_payment );*/

                });
    }

    function loadItems(){
        let formData  = new Object();
        formData.name = $("#txtSearch1").val(); 
        formData.brands = $("#item_brands").val(); 
        formData.categories = $("#item_categories").val(); 


        AWApi.get('{{ url('/api/pos/items') }}?'+$.param(formData), function (response) {
            $(".product").remove();
                var items = response;
                $.each(items, function(k, v) {
                    if (v.real_stock > 999) {
                        v.real_stock = '999+';
                    }
                    
                    var net_price     = Math.round(v.actual_price/1.19);
                    var net_tax_price = v.actual_price; //+ Math.round(v.actual_price * 0.19);
                    var label_price   = '';
                    if (pos_mode == 'ticket') {
                        var label_price =  v.actual_price; // + Math.round(v.actual_price * 0.19);
                    } else {
                        var label_price =  Math.round(v.actual_price/1.19);
                    }
                    
                    var pname = v.name;
                    if(v.imgUrl) {
                        var img_url ="{{asset('uploads/items')}}/"+v.imgUrl;
                    } else {
                        var img_url ="{{asset('img/defaults/box.png')}}";
                    }
                    
                    var html = "<div class=\"product\" id=\"p-" + v.id + "\" data-batch-id=\"" + v.id +
                        "\" data-name=\"" + v.name + "\" data-net_price=\"" + net_price + "\" data-price=\"" + net_tax_price + "\"> <div class=\"product-img\"><img src=" +
                        img_url + " class=\"img-fluid\">";
                    
                    html += "<span class=\"stock-tag\"><strong>" +
                        "<span class=\"stock numberCircle\">" + v.real_stock + "</span></strong></span>";

                    html += "<span class=\"price-tag\"><strong><span>$</span>" +
                        "<span class=\"price\">" + label_price + "</span></strong></span>";
                    html += "</div><div class=\"product-name\">" + v.name + "</div>";

                    // html += '<span class="numberCircle"><span>'+v.real_stock+'</span></span>';
                    html += "</div>";
                    $(".product-list").append(html);
                });

         // On clicking a product card, hide the .order-empty div
        // then retrieve product details using AJAX
        $("div.product").click(function (e) {
            clicked = 0;
            var id = $(this).attr("id").toString().replace("p-", "");
            var name = $(this).data("name");
            var price = $(this).data("price");
            var qty = 1;
            var item_exists = document.getElementById(id);
            if(item_exists){
                 qty = $(".cart-item-"+id).val();
                 /*qty = item_exists.firstElementChild.children[2].children[0].children[0]
                            .children["prod-quantity"].innerHTML;*/
                 var product_quantity = (parseInt(qty, 10) + 1);
                 AddProductToCart([id, name, price, product_quantity]);
            }else{
                if (!$(".order-empty").hasClass("d-none")) {
                    $(".order-empty").addClass("d-none");
                }

                AddProductToCart([id, name, price, qty]);
          }
        });

     });
    }

    function findProduct() {

    }

    function openPaymentMethod(){
        document.getElementById("payment-method").style.width = "100%";
    }
    function closePaymentMethod(){
        document.getElementById("payment-method").style.width = "0";
    }

    // Open cash tender drawer
    //2020
    
    function openDrawer() {

        if(pos_mode == 'invoice') {
            $("#references").show();
            $("#credit_sale").prop("checked", true);
            credit_sale = true; 
            $('.credit_sale').hide();
        }
        if(pos_mode == 'ticket') {
            cleanReferences();
            cleanAddedReferences();
            $("#checkbox_rut").prop("checked", true);
                ignore_rut = true;
            $("#references").hide();
            $('.credit_sale').hide();
            credit_sale = false;
        }

        if (!$('.sell-button').hasClass('d-none')) {
            $('.sell-button').addClass('d-none');
        }
        
        if (!$('#cash-payment').hasClass('d-none')) {
            $("#credit_sale").prop("checked", false);
            $('#cash-payment').addClass('d-none');
            $('#txtAmountTendered').val('');
            $('#txtChange').val('');
            $('#txtRemainingAmount').val(0);
            $('#txtRemarks').val('');
        }

        if (!$('#card-payment').hasClass('d-none')) {

            $("#credit_sale").prop("checked", true);
            $('#card-payment').addClass('d-none');
            $('#txtCardOperation').val('');
            $('#txtCardObservations').val('');
        }
        
        if (!$('#cheque-payment').hasClass('d-none')) {
            $("#credit_sale").prop("checked", true);
            $('#cheque-payment').addClass('d-none');
            $('#txtChequeNumber').val(1);
            $('#txtChequeObservation').val('');
            listDocuments();
        }

        if (!$('#intern-payment').hasClass('d-none')) {
            $('.credit_sale').show();
            $('#intern-payment').addClass('d-none');
            $('#internObservation').val('');
        }

        if (!$('#mix-payment').hasClass('d-none')) { 
            $('.credit_sale').show();
            $('#mix-payment').addClass('d-none');
            $('#select_type').val(1);
            $('.input-mix').val(0);
            $('.obs-mix').val('');
            renderMixList();
        }

        $('.payment-btn').removeClass('selected');
        $('.document-btn').removeClass('selected');
        //$('.payment-method').addClass('d-none');
        $('.document-type').removeClass('d-none');

        if(pos_mode == 'ticket') {
            $("#document-type").addClass("d-none");
            $("#client-select").removeClass("d-none")
            $(".rut-select").removeClass('d-none');
            $("#checkbox_rut").prop("checked", true);
                ignore_rut = true;
        } else {
            $("#client-select").removeClass("d-none");
            $("#payment-method").removeClass("d-none");
            $("#document-type").addClass("d-none"); 
            $(".rut-select").addClass('d-none');
                ignore_rut = false;
            $('#checkbox_rut').prop('checked',false);
        }

        /*
        $("#payment-method").removeClass("d-none");
               $("#document-type").addClass("d-none");
               $("#client-select").addClass("d-none")
               $(".rut-select").removeClass('d-none');
            }else{
               $("#client-select").removeClass("d-none");
               $("#payment-method").removeClass("d-none");
               $("#document-type").addClass("d-none"); 
               $(".rut-select").addClass('d-none');
        */

        document.getElementById("pay-drawer").style.width = "100%";
    }

    // Close cash tender drawer
    function closeDrawer() {
        document.getElementById("pay-drawer").style.width = "0";
    }

    function showReceipt() {
        document.getElementById("sales-receipt").style.width = "100%";
    }

    function hideReceipt() {
        document.getElementById("sales-receipt").style.width = "0";
    }

    function changeLocation(ts, id) {
        let formData = new FormData();
        formData.append('location_id', $(ts).val());
        AWApi.post('{{ url('api/pos/cart/')}}/item/'+id+'/location', formData, function(response){

        });
    }

    function makeStockSelect(stocks, location, id) {
        if(stocks == null) {
            return '<select disabled style="width:100px;float:right;margin-left:10px" class="form-control form-control-sm" ></select>';
        } else {
            let options = '';
            stocks.map(function(stock) {
                options += '<option '+(location == stock.location_id ? 'selected':'')+' value="'+stock.location_id+'" >'+stock.location_name+' ('+stock.count+')</option>';
            });
            return '<select onchange="changeLocation(this,'+id+')" style="width:100px;float:right;margin-left:10px" class="form-control form-control-sm">'+options+'</select>';
        }
    }

    // Retrieve cart items in session and display
    function getCartAndDisplay(){
     AWApi.get('{{ url('/api/pos/cart') }}', function (response) {
         // This will remove the 'selected' class from already existing cart item
            // Remember this runs only when a new product is clicked, so logically it
            // will remove highlight from the previously highlighted div element
            // to enable the newly added div element to have the 'selected' class
            $(".order-scroll > .order > .order-list").empty();
            if (response.length > 0) {
                if (!$(".order-empty").hasClass("d-none")) {
                    $(".order-empty").addClass("d-none");
                }
                // Loop through result, create html and append to cart
                // TODO: Add items to a sort of cart collection
                var total_price = 0.00;
                $.each(response, function (k, item) {
                    discount = item.discount_percent;
                    /*for (x in discounts) {
                        if(discounts[x].id === item.discount_id) {
                            discount = discounts[x].percent;
                        }
                    }*/
                    if (pos_mode == 'ticket') {
                        //var item_more_tax = Math.round(item.price*(100-discount)/100);
                        var item_more_tax = Math.round(item.price);
                    } else {
                        //var item_more_tax = Math.round(item.net_price*(100-discount)/100);
                        var item_more_tax = item.net_price;
                    }
                    
                    //discounts
                    


                    //for (var key in item) {
                    if (item.items.block_discount == 1){
                        
                        var cart_item = "<ul class='order-lines cart_item_id "+item.id+"' onclick='event.preventDefault(); clickedCartItem(" + item.item_id + ");' id='" + item.item_id + "' data-id='" + item.item_id + "' data-name='" + item.name + "'>";
                        cart_item += "<li class='order-line'><span class='product-name'>" +
                            item.name + "</span><span class='price'><span >$</span><span class='price-value'>"
                            + currencyFormat.format(Math.round(parseFloat(item.quantity) * parseFloat(item_more_tax))) + "</span></span><ul class='info-list'><li class='info'>" +
                            "<em>Cant: <span id='prod-quantity'><input class='cart-item-"+item.item_id+"' onMouseUp='changeItemMouse(this,"+item.item_id+","+item.quantity+",\""+item.name+"\", "+(parseFloat(item_more_tax))+","+item.id+")' onKeyUp='changeItemKey(this, "+item.item_id+",\""+item.name+"\", "+(parseFloat(item_more_tax))+","+item.id+")' style='width:70px' type='number' value='" + item.quantity + "' /></span></em>" + " a "
                            + "<span >$</span><span class='price-value'>" + item_more_tax + "</span>"
                            + makeStockSelect(item.stocks, item.location_id, item.id)
                            + "</li></ul></li></ul>";
                    }else{

                        var priceTotal = 0;
                        priceTotal += Math.round(parseFloat(item.quantity) * parseFloat(item_more_tax));
                        priceTotal -= Math.round(Math.round(parseFloat(item.quantity) * parseFloat(item_more_tax))*((item.discount_percent)/100));
                        var cart_item = "<ul class='order-lines cart_item_id "+item.id+"' onclick='event.preventDefault(); clickedCartItem(" + item.item_id + ");' id='" + item.item_id + "' data-id='" + item.item_id + "' data-name='" + item.name + "'>";
                        cart_item += "<li class='order-line'><span class='product-name'>" +
                            item.name + "</span><span class='price'><span >$</span><span class='price-value'>"
                            + priceTotal + "</span></span><ul class='info-list'><li class='info'>" +
                            "<em>Cant: <span id='prod-quantity'><input class='cart-item-"+item.item_id+"' onMouseUp='changeItemMouse(this,"+item.item_id+","+item.quantity+",\""+item.name+"\", "+(parseFloat(item_more_tax))+","+item.id+")' onKeyUp='changeItemKey(this, "+item.item_id+",\""+item.name+"\", "+(parseFloat(item_more_tax))+","+item.id+")' style='width:70px' type='number' value='" + item.quantity + "' /></span></em>" + " a "
                            + "<span >$</span><span class='price-value'>" + item_more_tax + "</span>"
                            + makeStockSelect(item.stocks, item.location_id, item.id)
                            + "<input class='desc_right form-control form-control-sm' onkeyup='saveDiscount(event,this,"+item.id+")' type='number' style='width:50px' value='"+discount+"' id='discount_"+item.id+"'/> ", 
                            + "</li></ul></li></ul>";
                    }
                        total_price = total_price + Math.round(parseFloat(item_more_tax) * parseFloat(item.quantity))
                        total_price -= Math.round(Math.round(parseFloat(item_more_tax) * parseFloat(item.quantity)) * parseFloat((item.discount_percent)/100));
                        $(".order-scroll > .order > .order-list").append(cart_item);
                    
                        //updateCartGlobal(item.item_id, item.name, item.quantity, item_more_tax, 0, false);
                    //}
                });

                // Update cart total price
                $(".summary").find(".value").first().text(currencyFormat.format(total_price));
                total_cart = total_price;
                if(pos_mode == 'invoice') {
                    let tax = Math.round(total_price * 0.19);
                    $(".summary").find(".tax_value").first().text(currencyFormat.format(tax));
                    $(".summary").find(".total_value").first().text(currencyFormat.format(total_price+tax));
                    total_cart = total_price+tax;
                }

                /*document.getElementsByClassName("summary")[0].firstElementChild.children[0]
                    .children[2].innerText = currencyFormat.format(total_price);*/

                // Show price summary
                if ($(".summary").hasClass("d-none")) {
                    $(".summary").removeClass("d-none");
                }
            }

            // If no item in cart, length of '.order-lines' will be zero(0)
            // If zero, show the 'cart is empty' icon and text
            // Also, remove cart total price summary
            // This is important for a case where no item is in cart list
            // I think this is redundant or if not, too much work.
            // TODO: Make code more efficient.
            if (parseInt($(".order").find(".order-lines").length) == 0) {
                if ($(".order-empty").hasClass("d-none")) {
                    $(".order-empty").removeClass("d-none");
                }

                if (!$(".summary").hasClass("d-none")) {
                    $(".summary").addClass("d-none");
                }
            }

     });
    }

    // Toggle selection for cart items (on item click event)
    function clickedCartItem(id) {
        clicked = 0;
        $(".selected").removeClass("selected");
        var order = document.getElementById(id);
        $(".order-line").removeClass("selected");
        $(".order-list").find("#" + id).children(".order-line").addClass("selected");
    }

    function cleanReferences(){
        $('#i_folio').val('');
        $('#i_razon_referencia').val('');
        $('#i_date').datepicker('setDate',moment(new Date()).format('DD/MM/YYYY'));
        
    }

    function cleanAddedReferences(){
        if (contReferences != 0){
            $('#dataReferences').find('tr').detach();
            let row = $('<tr></tr>');
            let folio= $('<th></th>').addClass('t_folio').text('Folio');
            let reason= $('<th></th>').addClass('t_folio').text('Razón Referencial');
            let doc_type= $('<th></th>').addClass('t_folio').text('Tipo de Documento');
            let date= $('<th></th>').addClass('t_folio').text('Fecha');
            let actions= $('<th></th>').addClass('t_actions').text('Acciones');
            row.append(folio);
            row.append(reason);
            row.append(doc_type);
            row.append(date);
            row.append(actions);
            $('#dataReferences').append(row);


        }
    }

    //
    function updateCartGlobal(id,name,quantity,price,discount,updateDiscount){

        id = parseInt(id);
        let item = new Object();
        item.id = id;
        item.name = name;
        item.quantity = quantity;
        item.price  = price;
        item.discount = discount;

        const exist = _.find(cartitems, { id : id });

        if(exist){
          if(updateDiscount){
             _.set(_.find(cartitems, { id: id }), 'discount', discount);
          }else{
             _.set(_.find(cartitems, { id: id }), 'quantity', quantity);
          }
        

        }else{
             cartitems.push(item);

        }
        var summary = _.sumBy(cartitems, function(o) {
         return o.discount > 0 ? Math.round((o.price - (o.price * o.discount)/100)) * o.quantity : (o.price * o.quantity);
        });

        document.getElementsByClassName("summary")[0]
            .firstElementChild.children[0].children[2]
            .innerText = currencyFormat.format(summary);

    }

    // Logic for displaying products and details in cart
    function AddProductToCart(values){
        var id = values[0];
        var name = values[1];
        var price = values[2];
        var quantity = values[3];
        var data = new FormData();
        
        data.append('batch_id', id);
        data.append('name', name)
        data.append('price', price);
        data.append('quantity', quantity);
        
        if(values[4] != null){
        console.log(values);
        var cart_item_id = values[4];
        data.append('cart_item_id', cart_item_id);
        }

        AWApi.post('{{ url('api/pos/cart/store')}}', data, function(response){
            console.log(response);

                if ($(".order-line").hasClass("selected")) {
                $(".order-line").removeClass("selected");
            }


            var product = $("#p-" + id);

            getCartAndDisplay();

            if(response.code == 403) {
                $.toast({
                    heading: "Error al agregar producto",
                    text: response.data.msg,
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: "info",
                    hideAfter: 6000,
                    stack: 6
                });
                return null;
            }
            if(response.new_batch_exists == "true" || response.new_batch_exists == "regular") {
                loadItems();
                /*product.find('.numberCircle span').text(response.stock);
                if(response.stock == 0) {
                    product.remove();
                }*/
                /*if(response.new_batch) {
                    var new_batch = response.new_batch;
                    id = new_batch.id;
                    price = new_batch.retail_price;

                    // If request has exceeded current stock quantity and a new stock exists,
                    // replace batch_id of product in the list with the new batch_id
                    product.attr("id", "p-"+id);
                    product.attr("data-batch-id", id);
                    product.attr("data-price", price);
                    product.find(".price").text(price);
                }

                var item_exists = document.getElementById(id);
                if (item_exists == null) {
                    appendToCart(id, name, price, quantity, response.discount);
                } else {
                    // Re-highlight already existing cart item (div)
                    if (!item_exists.firstElementChild.classList.contains("selected")) {
                        item_exists.firstElementChild.classList.add("selected");
                    }

                    // Update product quantity
                    var qty = item_exists.firstElementChild.children[2].children[0].children[0]
                        .children["prod-quantity"].innerHTML;
                    item_exists.firstElementChild.children[2].children[0].children[0]
                        .children["prod-quantity"].innerHTML = (parseInt(qty, 10) + 1).toString();

                    // update item subtotal
                    var item_price = item_exists.firstElementChild.children[2].children[0].children[2].innerText;
                    var updatedItemTotalPrice = (parseInt(qty, 10) + 1) * item_price;
                    document.getElementsByClassName("selected")[0].children[1].children[1].innerText = currencyFormat.format(updatedItemTotalPrice);

                    // Update cart total price
                    var displayed_total_unit_price = document.getElementsByClassName("summary")[0]
                        .firstElementChild.children[0].children[2].innerText.replace(",", "").replace(".00", "");
                    document.getElementsByClassName("summary")[0]
                        .firstElementChild.children[0].children[2]
                        .innerText = currencyFormat.format(parseFloat(displayed_total_unit_price) + parseFloat(item_price));

                    // Update PHP cart in session
                    var batch_id = item_exists.attributes[2].value;
                    var product_name = item_exists.attributes[4].value;
                    var product_price = parseInt(item_price, 10);
                    var product_quantity = (parseInt(qty, 10) + 1);

                     updateCartGlobal(id, name, quantity, price, 0, false);


                    //UpdateProduct([batch_id, product_name, product_price, product_quantity]);
                }*/
                

            }  else if(response.new_batch_exists == "false") {

                // remove item from product listing
                //product.remove();
                loadItems();
                // display a message
               /* $.toast({
                    heading: "Sin Stock",
                    text: "El stock del producto se ha agotado",
                    position: 'top-right',
                    loaderBg: '#ff6849',
                    icon: "info",
                    hideAfter: 6000,
                    stack: 6
                });*/
            }
                            
        });
    }

    function addItemNoStock(){
        AddProductToCart(['null', $('#item_no_stock_name').val(), $('#item_no_stock_price').val(), '1']);
        $("#add_item_no_stock").modal("hide");
        $("#item_no_stock_name").val("");
        $("#item_no_stock_price").val("");

    }

    function saveDiscount(e,ts, id) {
        if (e.keyCode = 13) {
            //do someting
            let formData = new FormData();
            formData.append('value', $(ts).val());
            AWApi.post('{{ url('api/pos/cart/')}}/item/'+id+'/discount', formData, function(response){
                if (response.code == 200) {
                    getCartAndDisplay();
                } else {
                    $.toast({
                        heading: 'Error',
                        text: response.data.msg,
                        position: 'top-right',
                        loaderBg: '#ff6849',
                        icon: 'info',
                        hideAfter: 4000,
                        stack: 6
                    });
                }
            });
        }
    }

    $('#filter').click(function(){
        tableDocuments.ajax.reload();
    });

    $('#clean').click(function(){
                //todo: Crear función dejar limpio todos los campo de un formulario por id
                $('#f_folio').val('');
                $('#f_type').val('');
                table.ajax.reload();
            });

    // Update an existing cart item
    function UpdateProduct(values) {
        var id = values[0];
        var name = values[1];
        var price = values[2];
        var quantity = values[3];

        updateCartGlobal(id, name, quantity, price, 0, false);

        
        var data = new FormData();
        data.append('batch_id', id);
        data.append('name', name)
        data.append('price', price);
        data.append('quantity', quantity);
        AWApi.post('{{ url('api/pos/cart/store')}}', data, function(response){
            if (response.hasOwnProperty('stock')) {
                let product = $('#p-'+id);
                if(product) {
                    product.find('.numberCircle span').text(response.stock);
                    if(response.stock == 0) {
                        product.remove();
                    }
                } else {
                    getCartAndDisplay();
                }
                
                //2020
            }
            if (response.hasOwnProperty('quantity')) {
                if (response.quantity == 0) {
                    getCartAndDisplay();
                    loadItems();
                }
            }           
        });
    }

    // Remove a product from cart based on given id
    function RemoveProduct(id) {
        var jqxhr = $.ajax({
            url: "/pos/cart/delete/" + id,
            type: "DELETE"
        });

        jqxhr.done(function (response, textStatus, jqXHR) {
            // Show toast notification
        });
    }

    // Append new item to cart
    function appendToCart(id, name, price, quantity, discount) {
        var cart_item = "<ul class='order-lines' onclick='event.preventDefault(); clickedCartItem(" + id + ");' id='" + id + "' data-id='" + id + "' data-name='" + name + "'>";
        cart_item += "<li class='order-line selected'><span class='product-name'>" +
            name + "</span><span class='price'><span>$</span><span class='price-value'>"
            + currencyFormat.format(price) + "</span></span><ul class='info-list'><li class='info'>" +
            "<em>Qty: <span id='prod-quantity'>" + quantity + "</span></em>" + " a "
            + "<span>$</span><span class='price-value'>" + price + "</span>"
            + "<input class='desc_right' onkeyup='saveDiscount(event, this,"+item.id+")' type='number' style='width:50px' value='"+discount+"' /> ",
            + "</li></ul></li></ul>";

        var total_price = document.getElementsByClassName("summary")[0].firstElementChild.children[0]
            .children[2].innerText.replace(",", "").replace(".00", "").replace(".", "");
        total_price = parseFloat(total_price) + parseFloat(price * quantity);

        $(".order-scroll > .order > .order-list").append(cart_item);

        // Update cart total price
        document.getElementsByClassName("summary")[0].firstElementChild.children[0]
            .children[2].innerText = currencyFormat.format(total_price);

        // Show price summary
        if ($(".summary").hasClass("d-none")) {
            $(".summary").removeClass("d-none");
        }
    }

    function saveReferences(){
       
       mix_references = [];

        if ($('#dataReferences tr').length == 1){


        }else{
            for (var i = 0; i <= contReferences; i++){
                if ($('#dataReferences').find('.t_folio_'+i).text() != "" ){
                    mix_references.push({
                        folio               :   $('#dataReferences').find('.t_folio_'+i).text(),
                        reason_reference    :   $('#dataReferences').find('.t_razon_referencia_'+i).text(),
                        doc_type            :   $('#dataReferences').find('.t_doc_type_'+i).text(),
                        // date                :   $('#dataReferences').find('.t_date_'+i).text()
                        date                :   moment($('#dataReferences').find('.t_date_'+i).text(), "DD/MM/YYYY").format('YYYY-MM-DD')
                        });

                
                }
                
            }
        }
    }

    $('.txt_moneys').change(function(){
        addCash();
    });

    $(function() {	
        $("#addReference").click(function() {


            if ($('#i_folio').val() === "" || $('#i_razon_referencia').val() === ""){
                $('#i_folio').css('border-color', 'red');
                $('#i_razon_referencia').css('border-color', 'red');
                swal('Error','Todos los campos son obligatorios','error');

            } else if ($('#i_folio').val().length > 18){
                    $('#i_razon_referencia').css('border-color', '');
                    $('#i_folio').css('border-color', 'red');
                    swal('Error','Folio no puede exceder la longitud de 18 caracteres','error');

            } else  {
                contReferences = contReferences + 1;

                var row = $('<tr></tr>');
                var folio = $('<td></td>').addClass('t_folio_'+contReferences).text($('#i_folio').val());
                var reason_reference = $('<td></td>').addClass('t_razon_referencia_'+contReferences).text($('#i_razon_referencia').val());
                var doc_type = $('<td></td>').addClass('t_doc_type_'+contReferences).text($( "#i_doc_type option:selected" ).text());
                var date = $('<td></td>').addClass('t_date_'+contReferences).text($('#i_date').val());
                var del = $('<td></td>').addClass('del_button');
                var del_but = $('<button></button>').addClass('delRef');
                var del_icon = $('<i></i>').addClass('fas fa-trash');
                del_but.append(del_icon);
                del.append(del_but);
                row.append(folio);
                row.append(reason_reference);
                row.append(doc_type);
                row.append(date);
                row.append(del);
                $('#dataReferences').append(row);
                cleanReferences();
                $('#i_folio').css('border-color', '');
                $('#i_razon_referencia').css('border-color', '');

                $('.delRef').click(function(){
                    $(this).parent().parent().remove();

                });
            }
        });
        
    });

</script>

<style>
    .product{
        height: 150px !important;
        width: 150px !important;
    }
    .product-img{
        margin-left: 15px;
        margin-right: 15px;
    }
    .product-name{
        margin-top: 10px;
        padding-bottom: 5px !important;
    }
    .numberCircle{
        top: 120px;
    }
</style>
</body>
