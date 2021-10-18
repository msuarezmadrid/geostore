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
        <i class="glyphicon glyphicon-screenshot" style="padding-right: 5px;"></i> Ventas sin stock
        </h1>
        <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Ventas sin stock</li> 
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
                            <span>Productos mas vendidos sin stock</span>
                        </h3>
                        <button class="btn btn-success" style="float:right" onClick="exports()" >
                            <i class="fa fa-file-excel-o"></i>
                        </button>
                    </div>
                    <div class="box-body">
                        <div class="col-xs-12" id="div_sales">
                            <table id="sales" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%" style="text-align: left;">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Cantidad</th>
                                    <th>Producto</th>
                                    <th>Total</th>
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
        var table_sales     = null;
        var table_movements = null;
        var seller_id       = null;

        function exports() {
            let start_date  = $('#start_date').val()+' 00:00';
            let end_date    = $('#end_date').val()+' 23:59';
            data    = {};
            filters = {};
            filters.download = '1';
            filters.start_date = moment(start_date).utc().format('YYYY-MM-DD HH:mm');
            filters.end_date   = moment(end_date).utc().format('YYYY-MM-DD HH:mm');
            data.filters = filters;
            AWApi.download('{{ url('/api/reports/export_sales_without_stock') }}?'+$.param(data));
        }

        $(document).ready(function() {
            
            $('#start_date').val(moment().startOf('month').format('YYYY-MM-DD'));
            $('#end_date').val(moment().endOf('month').format('YYYY-MM-DD'));
            table_sales = $('#sales').DataTable( {
                "language": {
                    "url": "{{ asset('json/Spanish.json') }}"
                },
                'binfo':false,
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
                    AWApi.get('{{ url('/api/reports/sales_without_stock' ) }}?'+$.param(data), function (response) {
                        callback({
                            recordsTotal: response.data.recordsTotal,
                            recordsFiltered: response.data.recordsFiltered,
                            data: response.data.sales
                        });
                    });
                },
                "paging": true,
                "ordering": false,
                //"scrollX": true,
                "columns": [
                    {"data":"id", "visible": false},
                    {
                        "className":"qty","data":"qty", "render" : function(a,b,c,d){
                            return Math.round(a*100)/100;
                        }
                    },
                    {"data":"item_name"},
                    {
                        "className":"total","data":"total", "render" : function(a,b,c,d){
                            return '$'+formatMoney(a, 'CL');
                        }
                    }
                ]
            });
            
            $('#filter').click( () => {
                table_sales.ajax.reload();
            });
            $('#clean').click( () => {
                $('#start_date').val(moment().startOf('month').format('YYYY-MM-DD'));
                $('#end_date').val(moment().endOf('month').format('YYYY-MM-DD'));
                table_sales.ajax.reload();
            });



       
    });

   $(window).load(function (){
        $('#filter').click();
   });
    </script>
@endsection