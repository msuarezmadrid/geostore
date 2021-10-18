@extends('layouts.master')

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/printjs/print.min.css') }}">


@endsection


@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-exchange" style="padding-right: 5px;"></i> Ventas
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Reportes</li>
      </ol>
    </section>
    
    <section class="content">
        <div class="row">


        <div class="box box-primary flat box-xs collapsed-box " aria-expanded="false">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-search" style="padding-right: 5px;"></i>Filtrar</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        </div>
                    </div> 
                    <!-- /.box-header -->
                    <div class="box-body">
                      <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label for="f_name">Razón Social</label>
                                <input type="text" class="form-control" id="f_name" placeholder="Nombre">
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label for="f_rut">Rut</label>
                                <input type="text" class="form-control" id="f_rut" placeholder="Rut">
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label for="f_type">Tipo de documento</label>
                                <select type="text" class="form-control" id="f_type">
                                    <option value=0 selected>Ambos</option>
                                    <option value=1>Boleta</option>
                                    <option value=2>Factura</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="start_date">Fecha Inicio</label>
                                    <input type="text" class="form-control" id="start_date" placeholder="Fecha Inicio">
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="end_date">Fecha Fin</label>
                                    <input type="text" class="form-control" id="end_date" placeholder="Fecha Fin">
                                </div>
                            </div>
                      </div>                      
                    </div>
                        
                    
                    <!-- /.box-body -->
                    <div class="box-footer">
                        <button type="button" id="clean" class="btn btn-default pull-right margin"> Limpiar </button>
                        <button type="button" id="filter" class="btn btn-primary pull-right margin"> Filtrar </button>
                    </div>
                    <!-- /.box-footer -->
                </div>
            <div class="col-xs-12 col-md-12">


           

            <div class="box box-primary flat box-solid">

                <div class="box-header">
                    <i class="fa fa-users"></i><h3 class="box-title"> Ventas</h3>
                    <div id="bulk_actions-3" class="btn-group" style="float:right">
                        <button id="print"  data-toggle="dropdown" aria-expanded="false" class="btn btn-danger btn-md" type="button">
                            <span><i class="fa fa-file-pdf-o pr-1"></i></span> 
                        </button>
                        <ul id="dropdown_print_actions" class="dropdown-menu dropdown-menu-right" >
                            <li><a style="color:#333" href="#" id="printByFolio">Por folio</a></li>
                            <li><a style="color:#333" href="#" id="printByClient">Por cliente</a></li>
                        </ul>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">

              
                  <table id="datas" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%" style="text-align: left;">
                    <thead>
                    <tr>
                      <th>#</th>
                      <th>Razón Social</th>
                      <th>Rut</th>
                      <th>Folio</th>
                      <th>Fecha</th>
                      <th>Tipo Documento</th>
                      <th>Neto</th>
                      <th>IVA</th>
                      <th>Total</th>
                    </tr>
                    </thead>
                  </table>
                  <div style="margin-right:10px">
                        
                        <input type="text" class="form-control" id="t_numeric">
                    </div>
                </div>
            </div>
            <!-- /.box -->
            </div>
            <!--<div class="col-xs-12 col-md-6">
             <div class="box box-primary flat box-solid">

                <div class="box-header">
                    <i class="fa fa-fax"></i><h3 class="box-title"> Contactos por cliente</h3>
                </div>
                <!-- /.box-header -->
               <!-- <div class="box-body">
                    <div class="btn-group">
                        <button type="button" class="btn btn-xs btn-success" id="myButton_contacts"><i class="fa fa-plus" style="padding-right: 5px;"></i>Agregar Contacto</button>
                    </div>
                  <table id="datas_contacts" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%" style="text-align: left;">
                    <thead>
                    <tr>
                      <th>#</th>
                      <th>Cliente</th>
                      <th>Contacto</th>
                      <th>Telefono</th>
                      <th>Email</th>
                      <th>Acciones</th>
                    </tr>
                    </thead>
                  </table>
                </div>
            </div>
            <!-- /.box -->

           <!-- </div>-->
        </div>
    </section>
@endsection



@section('js')
    <!-- Utils ajax request -->
    <script src="{{ asset('js/api.js') }}"></script>
    <script src="{{ asset('js/validator.js') }}"></script>
    <script src="{{ asset('js/modal.js') }}"></script>
    <script src="{{ asset('js/awsidebar.js') }}"></script>
    <script src="{{ asset('js/autoNumeric.min.js') }}"></script>


    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="{{ asset('plugins/printjs/print.min.js') }}"></script>




    <script>

        //variables globales
        table = null;
        table_contacts = null;
        fieldValues = [];
        sales_ids = [];
        const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;

        // modals --------


        $(document).ready(function() {

            $('#t_numeric').hide();

            $('#start_date').datepicker({
                format: 'dd/mm/yyyy',
                gotoCurrent: true,
                language:'es'
            });
            $('#end_date').datepicker({
                format: 'dd/mm/yyyy',
                gotoCurrent: true,
                language:'es'
            });

            $('#start_date').datepicker('setDate',moment(new Date()).format('DD/MM/YYYY'));
            $('#end_date').datepicker('setDate',moment(new Date()).format('DD/MM/YYYY'));



            // data Table --------------

            table = $('#datas').DataTable( {
                "language": {
                    "url": "{{ asset('json/Spanish.json') }}"
                },
                "responsive": true,
                "processing": true,
                "serverSide": true,
                "lengthChange": false,
                "searching": false,
                "ajax": function (data, callback, settings) {

                    //extra data

                    sales_ids['ids']= [];
                    sales_ids['start_date']= [];
                    sales_ids['end_date']= [];
                    var filters = new Object();
                    
                    filters.name = $('#f_name').val();
                    filters.rut = $('#f_rut').val();
                    filters.type = $('#f_type').val();

                    let start_date  = $('#start_date').val()+' 00:00';
                    let end_date    = $('#end_date').val()+' 23:59';

                    if ( moment($("#start_date").val(), 'DD/MM/YYYY').format('YYYY-MM-DD') != "Invalid date"){
                        filters.start_date = moment(start_date, 'DD/MM/YYYY HH:mm').utc().format('YYYY-MM-DD HH:mm');
                        sales_ids['start_date'] = filters.start_date;
                    }else{
                        filters.start_date = "";
                    }
                    if (  moment($("#end_date").val(), 'DD/MM/YYYY').format('YYYY-MM-DD') != "Invalid date"){
                        filters.end_date   = moment(end_date, 'DD/MM/YYYY HH:mm').utc().format('YYYY-MM-DD HH:mm');
                        sales_ids['end_date'] = filters.end_date;
                    }else{
                        filters.end_date = "";
                    }
                    
                    sales_ids['start_date'] = filters.start_date;
                    sales_ids['end_date'] = filters.end_date;
                    data.filters = filters;

                    //extra data

                    AWApi.get('{{ url('/api/reports/salesGrid' ) }}?' + $.param(data), function (response) {
                        callback({
                            recordsTotal: response.data.recordsTotal,
                            recordsFiltered: response.data.recordsFiltered,
                            data: response.data.sales,
                        });

                        
                        if (response.data.recordsFiltered == 0){
                            $('#print').hide() ;  
                        } else {
                            $('#print').show() ;
                        }
                        response.data.salesReport.forEach(function(salesReport, index) {
                            sales_ids['ids'].push(salesReport.id);
                            sales_ids['ids'] = Array.from(new Set(sales_ids['ids']));
                        });
                    });
                },
                "paging": true,
                "columns": [
                    { "data": "id", "visible": false,"searchable":false},
                    { "data": "name",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            return full.client.name;
                        }

                     },
                    { "data": "rut",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            return full.client.rut+'-'+full.client.rut_dv;
                        }

                     },
                     { "data": "folio" },
                     { "data": "date",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            date = moment(data).format("DD/MM/YYYY");
                            return date;
                        }
                     },
                     { "data": "Tipo documento",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            if (full.type == 1) {
                                return 'Boleta'
                            }else{
                                return 'Factura'
                            }
                        }

                    },
                    { "data": "net",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {

                   
                            let net = new AutoNumeric('#t_numeric', {
                                currencySymbol : ' $',
                                decimalCharacter : ',',
                                digitGroupSeparator : '.',
                                decimalPlaces:'0'
                            });
                            
                            $('#t_numeric').hide();
                            net.set(full.net);


                            return net.getFormatted();
                            

                           
                        }
                    
                    },
                    { "data": "tax",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {

                   
                            let tax = new AutoNumeric('#t_numeric', {
                                currencySymbol : ' $',
                                decimalCharacter : ',',
                                digitGroupSeparator : '.',
                                decimalPlaces:'0'
                            });
                            
                            $('#t_numeric').hide();
                            tax.set(full.tax);


                            return tax.getFormatted();
                        }
                    },
                    { "data": "total",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {

                   
                            let total = new AutoNumeric('#t_numeric', {
                                currencySymbol : ' $',
                                decimalCharacter : ',',
                                digitGroupSeparator : '.',
                                decimalPlaces:'0'
                            });
                            
                            $('#t_numeric').hide();
                            total.set(full.total);


                            return total.getFormatted();
                        }
                    }
                    

                     
                ]
            });



            // set fields ----------------------

            (function setFieldValues() {
                fieldValues['rut'] = 'f_rut';
                fieldValues['name'] = 'f_name';
                fieldValues['type'] = 'f_type';
            })();


            // Buttons triggers ------------------

            $('#filter').click(function(){
                sales_ids['ids']= [];
                table.ajax.reload();
            });


            $('#clean').click(function(){
                //todo: Crear función dejar limpio todos los campo de un formulario por id
                $('#f_rut').val('');
                $('#f_name').val('');
                $('#f_type').val('0');
                $('#start_date').datepicker('setDate',moment(new Date()).format('DD/MM/YYYY'));
                $('#end_date').datepicker('setDate',moment(new Date()).format('DD/MM/YYYY'));
                sales_ids['ids']= [];
                table.ajax.reload();
            });



            $("#printByFolio").click(function() {
                

                    //extra data
                    var filters = new FormData();
                    
                    filters.append('ids', JSON.stringify(sales_ids['ids']));
                    filters.append('start_date', sales_ids['start_date']);
                    filters.append('end_date', sales_ids['end_date']);
                    filters.append('timezone', timezone);
                    filters.append('type', 'folio');

                    //extra data

                    AWApi.post('{{ url('api/report/printSales' ) }}' , filters, function (response) {
                        if(response.code == 200) {
                            window.open(response.data.url,"mywindow1");
                        }
                        

                       
                        
                    });

        });
            $("#printByClient").click(function() {
                

                    //extra data
                    var filters = new FormData();
                    
                    filters.append('ids', JSON.stringify(sales_ids['ids']));
                    filters.append('start_date', sales_ids['start_date']);
                    filters.append('end_date', sales_ids['end_date']);
                    filters.append('timezone', timezone);
                    filters.append('type', 'client_id');

                    //extra data

                    AWApi.post('{{ url('api/report/printSales' ) }}' , filters, function (response) {
                        if(response.code == 200) {
                            window.open(response.data.url,"mywindow1");
                        }
                        

                       
                        
                    });

        });

        });

        // extra functions--------


    </script>
@endsection 

