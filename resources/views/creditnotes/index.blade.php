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
      <i class="fa fa-cubes" style="padding-right: 5px;"></i> Notas de Crédito
       <button type="button" class="btn btn-success btn-xs" id="addCN"><i class="fa fa-plus" style="padding-right: 5px;"></i> Agregar Nota de Crédito</button>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Notas de Crédito</li> 
    </ol>
</section>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-primary flat box-xs collapsed-box">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-search" style="padding-right: 5px;"></i>Filtrar</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                    </div>
                </div> 
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label for="f_name">Cliente</label>
                                <input class="form-control" id="f_name" placeholder="Cliente" />
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label for="f_folio">Folio</label>
                                <input class="form-control" id="f_folio" placeholder="Folio" />
                            </div>
                        </div>
                        <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="start_date">Fecha Inicio</label>
                                    <input type="date" class="form-control" id="start_date" placeholder="Fecha Inicio">
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="end_date">Fecha Fin</label>
                                    <input type="date" class="form-control" id="end_date" placeholder="Fecha Fin">
                                </div>
                            </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="button" id="filter" class="btn btn-primary pull-right margin"> Filtrar </button>
                    <button type="button" id="clean" class="btn btn-default pull-right margin"> Limpiar </button>
                </div>
            </div>
            <div class="box box-primary box-solid flat">
                <div class="box-body">
                    <div class="col-xs-12" id="div_datas">
                        <table id="datas" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%" style="text-align: left;">
                          <thead>
                            <tr>
                              <th>#</th>
                              <th>Folio</th>
                              <th>Cliente</th>
                              <th>Fecha</th>
                              <th>Neto</th>
                              <th>IVA</th>
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
@endsection

@section('js')
<script src="{{ asset('js/api.js') }}"></script>
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('js/validator.js') }}"></script>
    <script src="{{ asset('js/modal.js') }}"></script>
    <script src="{{ asset('js/awsidebar.js') }}"></script>
    <script src="{{ asset('js/jquery-editable-select.min.js') }}"></script>
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
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script>
        var table_credits = null;
        var item_list     = [];
        var sale_id       = null;
        var doc_type      = null;

        $(document).ready(function() {
            $('#start_date').val(moment().startOf('month').format('YYYY-MM-DD'));
            $('#end_date').val(moment().endOf('month').format('YYYY-MM-DD'));

            $('#addCN').click(function() {
                window.location.href = "{{ url( '/creditnotes/add' ) }}";
            });
            
            table_credits = $('#datas').DataTable( {
                "language": {
                    "url": "{{ asset('json/Spanish.json') }}"
                },
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "searching": false,
                "ajax": function (data, callback, settings) {

                    var filters = new Object();
                    let start_date = $('#start_date').val()+' 00:00';
                    let end_date   = $('#end_date').val()+' 23:59';
                    
                    filters.name = $('#f_name').val();
                    filters.folio = $('#f_folio').val();

                    
                    filters.start_date = moment(start_date).utc().format('YYYY-MM-DD HH:mm');
                    filters.end_date   = moment(end_date).utc().format('YYYY-MM-DD HH:mm');


                    data.filters = filters;


                    AWApi.get('{{ url('/api/creditnotes') }}?'+$.param(data), function (response) {
                        callback({
                            recordsTotal: response.data.total,
                            recordsFiltered: response.data.filtered,
                            data: response.data.rows
                        });
                    });
                },
                "paging": true,
                "ordering": false,
                "scrollX": true,
                "columns": [
                    { 'data' : 'id', "visible": false },
                    { 'data' : 'folio' },
                    { 'data' : 'client' },
                    { 
                        "data": "created_at",
                        className:'text-center',
                        render: function ( data, type, full, meta ) { 
                            date = moment(data).format("DD/MM/YYYY");
                            return date;
                            }
                    },
                    { 
                        "data": "net",
                        className:'text-center',
                        render: function ( data, type, full, meta ) { 
                            return "$"+formatMoney(full.net,'CL');
                            }
                    },
                    { 
                        "data": "tax",
                        className:'text-center',
                        render: function ( data, type, full, meta ) { 
                            return "$"+formatMoney(full.tax,'CL');
                        }
                    },
                    { 
                        "data": "total",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            return "$"+formatMoney(full.total,'CL');
                        }
                    },
                    { 
                        "data": "id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) { 
                            console.log(full)

                            var viewCreditNote = "<button class='btn btn-danger btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=callCreditNote("+full.folio+");>";
                            viewCreditNote += "<i class='fa fa-print'></i> </button>";

                            return "<div class='btn-group'>"+viewCreditNote+"</div>";
                        }
                    },
                ]
            });


            // Buttons triggers ------------------

            $('#filter').click(function(){
                table_credits.ajax.reload();
            });


            $('#clean').click(function(){
                //todo: Crear función dejar limpio todos los campo de un formulario por id
                $('#f_name').val('');
                $('#f_folio').val('');
                table_credits.ajax.reload();
            });
    
        });

        function callCreditNote(folio){
       
                AWApi.get('{{ url('api/creditnote/busca')}}/?folio='+folio, function (response) {
                    if(response.code == 200) {
                        console.log(response.data.data.ruta);
                        window.open(AFE_URL_API+'/'+response.data.data.ruta+'?api_token='+API_TOKEN,"mywindow");
                    }
                });
            }
    </script>
@endsection