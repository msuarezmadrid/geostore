@extends('layouts.master')
@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery-editable-select.min.css') }}">
    <style>
      .attribute-item{
        max-width: 400px;
        word-wrap: break-word;
      }
    </style>
@endsection
@section('content')
    <section class="content-header">
        <h1>
        <i class="fa fa-dollar" style="padding-right: 5px;"></i> Ventas
        </h1>
        <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Ventas</li> 
        </ol>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary flat box-xs ">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-search" style="padding-right: 5px;"></i>Filtrar</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-2">
                                <div class="form-group">
                                    <label for="start_date">Fecha Inicio</label>
                                    <input type="date" class="form-control" id="start_date" placeholder="Fecha Inicio">
                                </div>
                            </div>
                            <div class="col-xs-2">
                                <div class="form-group">
                                    <label for="end_date">Fecha Fin</label>
                                    <input type="date" class="form-control" id="end_date" placeholder="Fecha Fin">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="button" id="clean" class="btn btn-default pull-right margin"> Limpiar </button>
                        <button type="button" id="filter" class="btn btn-primary pull-right margin"> Filtrar </button>
                    </div>
                </div>
                <div class="box box-primary box-solid flat">
                    <div class="box-header">
                        <h3 class="box-title">
                            <i class="fa fa-list" style="padding-right: 5px;"></i> 
                            <span class="total_sales">Total : $0</span>
                        </h3>
                        <button class="btn btn-success" style="float:right" onClick="exports(0)" >
                            <i class="fa fa-file-excel-o"></i>
                        </button>
                    </div>
                    <div class="box-body">
                        <div class="col-xs-12" id="div_seller">
                            <table id="sellers" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%" style="text-align: left;">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Vendedor</th>
                                    <th>Cantidad</th>
                                    <th class="asdfasd">Efectivo</th>
                                    <th class="asdfasd">Cheques</th>
                                    <th class="asdfasd">Tarjeta</th>
                                    <th class="asdfasd">Interno</th>
                                    <th class="asdfasd">Mixto</th>
                                    <th class="asdfasd">App</th>
                                    <th class="asdfasd">Transferencia</th>
                                    <th>Total</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="box_movements" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="modalLabel">Movimientos</h4>
                </div>
                <div class="modal-body">
                        <div class="row">
                            <form class="form-horizontal">
                                <div class="col-md-4 form-group">
                                    <label class="control-label col-sm-3">Tipo : </label>
                                    <div class="col-sm-9">
                                        <select id="doc_type" class="form-control">
                                            <option value="0">Todos</option>
                                            <option value="2">Efectivo</option>
                                            <option value="4">Tarjeta</option>
                                            <option value="3">Cheque</option>
                                            <option value="5">Interno</option>
                                            <option value="6">Mixto</option>
                                            <option value="15">App</option>
                                            <option value="17">Transferencia</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <table id="movements" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tipo</th>
                                        <th>Monto</th>
                                        <th>N.Factura/ Boleta</th>
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
@endsection
@section('js')
    <script src="{{ asset('js/api.js') }}"></script>
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('js/validator.js') }}"></script>
    <script src="{{ asset('js/modal.js') }}"></script>
    <script src="{{ asset('js/awsidebar.js') }}"></script>
    <script src="{{ asset('js/jquery-editable-select.min.js') }}"></script>

    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script>
        var table_sales     = null;
        var table_movements = null;
        var seller_id       = null;
        function showMovements(sid) {
            seller_id = sid;
            $('#box_movements').modal('show');
        }
        function exports(sid) {
            let start_date  = $('#start_date').val()+' 00:00';
            let end_date    = $('#end_date').val()+' 23:59';
            data    = {};
            filters = {};
            filters.start_date = moment(start_date).utc().format('YYYY-MM-DD HH:mm');
            filters.end_date   = moment(end_date).utc().format('YYYY-MM-DD HH:mm');
            if(sid > 0) {
                filters.seller_id = sid;
            }
            data.filters = filters;
            AWApi.download('{{ url('/api/reports/sales/export') }}?'+$.param(data));
        }

         function disable_payment_methods_not_allowed(){
        AWApi.get('{{ url('/api/configs/params') }}/MIX', function (response) {
            if (response.code == 200){
                if(response.data.value == 0){
                    jQuery("#doc_type option:contains('Mix')").remove();
                    if ($(".mix_total").length > 0){
                            $('.mix_total').remove();
                    }
                }
            }
        });

        AWApi.get('{{ url('/api/configs/params') }}/INTERN', function (response) {
            if (response.code == 200){
                if(response.data.value == 0){
                    jQuery("#doc_type option:contains('Interno')").remove();
                    if ($(".intern_total").length > 0){
                            $('.intern_total').remove();
                    }
                }
            }
        });

        AWApi.get('{{ url('/api/configs/params') }}/CARD', function (response) {
            if (response.code == 200){
                if(response.data.value == 0){
                    jQuery("#doc_type option:contains('Tarjeta')").remove();
                    if ($(".card_total").length > 0){
                            $('.card_total').remove();
                    }
                }
            }
        });

        AWApi.get('{{ url('/api/configs/params') }}/CHEQUE', function (response) {
            if (response.code == 200){
                if(response.data.value == 0){
                    jQuery("#doc_type option:contains('Cheque')").remove();      
                    if ($(".cheque_total").length > 0){
                            $('.cheque_total').remove();
                    }
                }
            }
        });

        AWApi.get('{{ url('/api/configs/params') }}/APP', function (response) {
            if (response.code == 200){
                if(response.data.value == 0){
                    jQuery("#doc_type option:contains('App')").remove();      
                    if ($(".app_total").length > 0){
                            $('.app_total').remove();
                    }
                }
            }
        });

        AWApi.get('{{ url('/api/configs/params') }}/TRANSFER', function (response) {
            if (response.code == 200){
                if(response.data.value == 0){
                    jQuery("#doc_type option:contains('Transferencia')").remove();      
                    if ($(".transfer_total").length > 0){
                            $('.transfer_total').remove();
                    }
                }
            }
        });

        AWApi.get('{{ url('/api/configs/params') }}/CASH', function (response) {
            if (response.code == 200){
                if(response.data.value == 0){
                    jQuery("#doc_type option:contains('Efectivo')").remove();
                    if ($(".cash_total").length > 0){
                            $('.cash_total').remove();
                    }
                }
            }
        });
    }
        $(document).ready(function() {
            $('#box_movements').on('shown.bs.modal', function (e) {
                $('#doc_type').val(0);
                table_movements.ajax.reload();
            });
            $('#doc_type').on('change', function() {
                table_movements.ajax.reload();
            });
            
            $('#start_date').val(moment().startOf('day').format('YYYY-MM-DD'));
            $('#end_date').val(moment().endOf('day').format('YYYY-MM-DD'));
            table_sales = $('#sellers').DataTable( {
                "language": {
                    "url": "{{ asset('json/Spanish.json') }}"
                },
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "lengthChange": true,
                "dom": '<"pull-right"l><tr><ip>',
                "searching": false,
                "ajax": function (data, callback, settings) {
                    let start_date = $('#start_date').val()+' 00:00';
                    let end_date   = $('#end_date').val()+' 23:59';
                    data.start_date = moment(start_date).utc().format('YYYY-MM-DD HH:mm');
                    data.end_date   = moment(end_date).utc().format('YYYY-MM-DD HH:mm');
                    AWApi.get('{{ url('/api/reports/sales/resume' ) }}?'+$.param(data), function (response) {
                        $('.total_sales').text("Total : $"+formatMoney(response.data.total, 'CL'));
                        callback({
                            recordsTotal: response.data.recordsTotal,
                            recordsFiltered: response.data.recordsFiltered,
                            data: response.data.rows
                        });
                    });
                },
                "paging": false,
                "ordering": false,
                //"scrollX": true,
                "columns": [
                    {"data":"seller_id", "visible": false},
                    {"data":"name"},
                    {"data":"seller_quantity"},
                    {"className":"cash_total","data":"cash_total", "render" : function(a,b,c,d){
                        return '$'+formatMoney(a, 'CL');
                    }},
                    {"className":"cheque_total","data":"cheque_total", "render" : function(a,b,c,d){
                        return '$'+formatMoney(a, 'CL');
                    }},
                    {"className":"card_total","data":"card_total", "render" : function(a,b,c,d){
                        return '$'+formatMoney(a, 'CL');
                    }},
                    {"className":"intern_total","data":"intern_total", "render" : function(a,b,c,d){
                        return '$'+formatMoney(a, 'CL');
                    }},
                    {"className":"mix_total","data":"mix_total", "render" : function(a,b,c,d){
                        return '$'+formatMoney(a, 'CL');
                    }},
                    {"className":"app_total","data":"app_total", "render" : function(a,b,c,d){
                        return '$'+formatMoney(a, 'CL');
                    }},
                    {"className":"transfer_total","data":"transfer_total", "render" : function(a,b,c,d){
                        return '$'+formatMoney(a, 'CL');
                    }},
                    {"className":"sum_total","data":"sum_total", "render" : function(a,b,c,d){
                        return '$'+formatMoney(a, 'CL');
                    }},
                    {"data":"id","class":"text-center",  "render" : function(a,b,c,d){
                        return `
                        <button class="btn" onClick="showMovements(${c.seller_id})" ><i class="fa fa-eye"></i></button>
                        <button class="btn" onClick="exports(${c.seller_id})" ><i class="fa fa-file-excel-o"></i></button>
                        `;
                    }}
                ],
                "fnDrawCallback": function( oSettings ){
                    disable_payment_methods_not_allowed();
                }
            });
            table_movements = $('#movements').DataTable( {
                "language": {
                    "url": "{{ asset('json/Spanish.json') }}"
                },
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "lengthChange": true,
                "dom": '<"pull-right"l><tr><ip>',
                "searching": false,
                "ajax": function (data, callback, settings) {
                    let start_date  = $('#start_date').val()+' 00:00';
                    let end_date    = $('#end_date').val()+' 23:59';
                    filters         = {};
                    filters.start_date = moment(start_date).utc().format('YYYY-MM-DD HH:mm');
                    filters.end_date   = moment(end_date).utc().format('YYYY-MM-DD HH:mm');
                    filters.type       = $('#doc_type').val();
                    filters.seller_id  = seller_id;
                    data.filters       = filters;
                    AWApi.get('{{ url('/api/reports/sales/movements' ) }}?'+$.param(data), function (response) {
                        callback({
                            recordsTotal: response.data.total,
                            recordsFiltered: response.data.filtered,
                            data: response.data.rows
                        });
                    });
                },
                "paging": true,
                "ordering": false,
                //"scrollX": true,
                "columns": [
                    {"data":"seller_id", "visible": false},
                    {"data":"payment_method", "render":function(a, b, c, d) {
                        type=0;                        
                        switch(c.payment_method){
                            case 'intern': 
                                if (c.type == 1){
                                    type = 10;
                                break;
                                }else{
                                    type = 11;
                                break;
                                }
                            case 'cash': 
                                if (c.type == 1){
                                    type = 4;
                                break;
                                }else{
                                    type = 7;
                                break;
                                }
                            case 'cheque': 
                                if (c.type == 1){
                                    type = 6;
                                break;
                                }else{
                                    type = 9;
                                break;
                                }
                            case 'card': 
                                if (c.type == 1){
                                    type = 5;
                                break;
                                }else{
                                    type = 8;
                                break;
                                }
                            case 'diff': 
                                if (c.type == 1){
                                    type = 12;
                                break;
                                }else{
                                    type = 13;
                                break;
                                }
                            case 'app': 
                                if (c.type == 1){
                                    type = 15;
                                break;
                                }else{
                                    type = 16;
                                break;
                                }
                            case 'transfer': 
                                if (c.type == 1){
                                    type = 17;
                                break;
                                }else{
                                    type = 18;
                                break;
                                }
                        }
                        switch(type) {
                            case 4: return 'Venta Boleta  con efectivo'; break;
                            case 5: return 'Venta Boleta  con tarjeta';  break;
                            case 6: return 'Venta Boleta  con cheque';   break;
                            case 7: return 'Venta Factura con efectivo'; break;
                            case 8: return 'Venta Factura con tarjeta';  break;
                            case 9: return 'Venta Factura con cheque';   break;
                            case 10: return 'Venta boleta con crédito interno'; break;
                            case 11: return 'Venta factura con crédito interno'; break
                            case 12: return 'Venta boleta con pago mixto'; break;
                            case 13: return 'Venta factura con crédito mixto'; break;
                            case 15 : return 'Venta boleta con pago por app'; break;
                            case 16 : return 'Venta factura con pago por app'; break;
                            case 17 : return 'Venta boleta con pago por transferencia'; break;
                            case 18 : return 'Venta factura con pago por transferencia'; break;
                        }
                    }},
                    {"data":"total", "render":function(a,b,c,d) {
                        return '$'+formatMoney(a, 'CL');
                    }},
                    {"data":"folio"},
                    {"data":"created_at", "render":function(a, b, c, d) {
                        return utcToLocal(a);
                    }}
                ]
            });
            $('#filter').click( () => {
                table_sales.ajax.reload();
                disable_payment_methods_not_allowed();
            });
            $('#clean').click( () => {
                $('#start_date').val(moment().startOf('day').format('YYYY-MM-DD'));
                $('#end_date').val(moment().endOf('day').format('YYYY-MM-DD'));
                table_sales.ajax.reload();
                disable_payment_methods_not_allowed();
            });


            $(".form-control").on('keyup', function(e) {
                //Keycode 13 = Enter
                if(e.keyCode == 13) {
                    table_sales.ajax.reload();
                    disable_payment_methods_not_allowed();
                }
            });

       
    });

   $(window).load(function (){
        $('#filter').click();
   });
    </script>
@endsection