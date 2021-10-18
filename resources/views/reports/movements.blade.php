
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
      .d-none{
            display:none
      }
    </style>
@endsection
@section('content')
    <section class="content-header">
        <h1>
        <i class="fa fa-arrow-circle-right" style="padding-right: 5px;"></i> Movimientos
        </h1>
        <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Movimientos Caja</li> 
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
                                    <label for="start_date">Caja</label>
                                    <select id="select_box" class="form-control">
                                        @foreach($boxes as $box)
                                            <option value="{{ $box->id }}">{{ $box->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-2">
                                <div class="form-group">
                                    <label for="end_date">Fecha:</label>
                                    <input type="date" class="form-control" id="start_date" placeholder="Fecha">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box box-primary box-solid flat">
                    <div class="box-header">
                        <h3 class="box-title">
                            <i class="fa fa-list" style="padding-right: 5px;"></i> 
                            <span class="resume">Flujo Caja</span>
                        </h3>
                        <button class="btn btn-success" style="float:right" onClick="exports()" >
                            <i class="fa fa-file-excel-o"></i>
                        </button>
                    </div>
                    <div class="box-body">
                        <div class="col-xs-12">
                            <table id="movements" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%" style="text-align: left;">
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Transact ID</th>
                                        <th>Tipo</th>
                                        <th>Monto</th>
                                        <th>N. Factura/Boleta</th>
                                        <th>Cajero / Vendedor</th>
                                        <th>Observaciones</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
        var table_movements = null;
        function changeCollapse(group) {
            $('.group-'+group).toggleClass('d-none');
        }
        function getInfo(rows, filter, group) {
            console.log(rows);
            switch(filter) {
                case 'balance':
                    let balance = 0;
                    for (x in rows) {
                        if(rows[x].transact_id == group &&
                         (  rows[x].type == 2
                         || rows[x].type == 12
                         || rows[x].type == 13
                         || rows[x].type == 14
                         || rows[x].type == 4
                         || rows[x].type == 7)
                        ) {
                            balance += parseInt(rows[x].amount);
                        }
                    }
                    return '$'+formatMoney(balance, 'CL');
                break;
                case 'real_balance':
                    let real_balance = null;
                    for (x in rows) {
                        if(rows[x].transact_id == group &&
                           rows[x].type == 3) {
                            real_balance += parseInt(rows[x].amount);
                        }
                    }
                    if (real_balance != null) {
                        return '$'+formatMoney(real_balance, 'CL');
                    } else {
                        return 'N/D';
                    }
                break;
                case 'pdf':
                    for(x in rows) {
                        if(rows[x].transact_id == group) {
                            return rows[x].sale_box_document;
                        }
                    }
                break;
            }
        }
        function exports() {
            let start_date  = $('#start_date').val()+' 00:00';
            let end_date    = $('#start_date').val()+' 23:59';
            data            = {};
            filters         = {};
            filters.start_date = moment(start_date).utc().format('YYYY-MM-DD HH:mm');
            filters.end_date   = moment(end_date).utc().format('YYYY-MM-DD HH:mm');
            filters.box_id     = $('#select_box').val();
            data.filters       = filters;
            AWApi.download('{{ url('/api/reports/sales/box/movements/export') }}?'+$.param(data));
        }

        function getPdf(transactId) {
            let data = {};
            data.filters = {};
            data.filters.transact_id = transactId;

            AWApi.get('{{ url('/api/reports/sales/box/movements/pdf') }}?'+$.param(data), function (response) {
                        if(response.code == 200) {
                            window.open(response.data.url);
                        }
            });
        }

        $(document).ready(function() {
            $('#start_date').val(moment().format('YYYY-MM-DD'));
            $('#start_date').on('change', function() {
                table_movements.ajax.reload();
            });
            $('#select_box').on('change', function() {
                table_movements.ajax.reload();
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
                "drawCallback": function ( settings ) {
                    var api = this.api();
                    var rows = api.rows( {page:'current'} ).nodes();
                    var last=null;
                    var info = api.rows( {page:'current'} ).data();
                    api.column(1, {page:'current'} ).data().each( function ( group, i ) {
                        $(rows).eq( i ).addClass("group-"+group);
                        if ( last !== group ) {
                            const total_price  = getInfo(info, 'balance', group);
                            const close_price  = getInfo(info, 'real_balance', group);
                            const file = getInfo(info, 'pdf', group);
                            let transact_id = group;
                            let text = 'Saldo Caja : '+total_price;
                            text    += ' | Saldo Cierre Caja : '+close_price;
                            let hasFile = '';
                            if(file != null && file != '') {
                                hasFile = `<button class="btn btn-danger" style="float:right" onClick="getPdf('`+group+`')" >
                                    <i class="fa fa-file-pdf-o"></i>
                                </button>`;
                            }
                            $(rows).eq( i ).before(
                                `<tr class="group" style="cursor:pointer" onClick="changeCollapse('`+group+`')" >
                                    <td colspan="6">
                                        <b>`+text+`</b>`
                                        +hasFile+
                                        `
                                    </td>
                                </tr>`
                            );
                            last = group;
                        }
                    } );
                },
                "ajax": function (data, callback, settings) {
                    let start_date  = $('#start_date').val()+' 00:00';
                    let end_date    = $('#start_date').val()+' 23:59';
                    filters         = {};
                    filters.start_date = moment(start_date).utc().format('YYYY-MM-DD HH:mm');
                    filters.end_date   = moment(end_date).utc().format('YYYY-MM-DD HH:mm');
                    filters.box_id     = $('#select_box').val();
                    data.filters       = filters;
                    AWApi.get('{{ url('/api/reports/sales/box/movements' ) }}?'+$.param(data), function (response) {
                        callback({
                            recordsTotal: response.data.total,
                            recordsFiltered: response.data.filtered,
                            data: response.data.rows
                        });
                    });
                },
                "lengthMenu" : [1, 2, 5, 10, 20],
                "paging": true,
                "ordering": false,
                //"scrollX": true,
                "columns": [
                    {"data":"id", "visible": false},
                    {"data":"transact_id", "visible": false},
                    {"data":"type", "render":function(a, b, c, d) {
                        switch(a) {
                            case 1: return 'Apertura de caja';           break;
                            case 2: return 'Agrega fondos';              break;
                            case 3: return 'Cierre de caja';             break;
                            case 4: return 'Venta Boleta con efectivo'; break;
                            case 5: return 'Venta Boleta con tarjeta';  break;
                            case 6: return 'Venta Boleta con cheque';   break;
                            case 7: return 'Venta Factura con efectivo'; break;
                            case 8: return 'Venta Factura con tarjeta';  break;
                            case 9: return 'Venta Factura con cheque';   break;
                            case 10: return 'Venta boleta con crédito interno'; break;
                            case 11: return 'Venta factura con crédito interno'; break
                            case 12: return 'Ley de redondeo en boleta'; break;
                            case 13: return 'Ley de redondeo en factura'; break;
                            case 14: return 'Devolución por nota de crédito'; break;
                            case 15: return 'Venta boleta con APP'; break;
                            case 16: return 'Venta factura con APP'; break;
                            case 17: return 'Venta boleta con transferencia bancaria'; break;
                            case 18: return 'Venta factura con transferencia bancaria'; break;

                        }
                    }},
                    {"data":"amount", "render":function(a,b,c,d) {
                        return '$'+formatMoney(a, 'CL');
                    }},
                    {"data":"doc_id"},
                    {"data":"name", "render":function(a,b,c,d) {
                           
                        return ((c.cajero != null) ? c.cajero :'')+((c.name != null) ? " / "+c.name :'');
                    }},
                    {"data":"observations"},
                    {"data":"created_at", "render":function(a, b, c, d) {
                        return utcToLocal(a);
                    }}
                ]
            });
        });
    </script>
@endsection