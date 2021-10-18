@extends('layouts.master')

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css') }}">
    <link rel="stylesheet" href="{{ asset('css/jquery-editable-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bootstrap-select/bootstrap-select.min.css') }}">
@endsection


@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
      <i class="glyphicon glyphicon-piggy-bank" style="padding-right: 5px;"></i> Documentos Conciliación

         <button type="button" class="btn btn-success btn-xs" id="myButton"> <i class="fa fa-plus" style="padding-right: 5px;"></i> Agregar Documento Conciliación</button> 
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Documentos Conciliación</li>
      </ol>
    </section>
    
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box box-primary flat box-xs collapsed-box">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-search" style="padding-right: 5px;"></i> Filtrar</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
                        </div>
                    </div> 
                    <!-- /.box-header -->
                    <div class="box-body">
                      <div class="row">
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
                        <div class="row">
                             <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="f_client_id">Cliente</label>
                                        <select class="form-control" name="client_id" id="f_client_id"></select>
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
                    


                <div class="box box-primary flat box-solid">

                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-list" style="padding-right: 5px;"></i> Listado de Conciliaciones</h3>  
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="col-md-12">

                          <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                              <li  class="active"><a href="#tab_2" data-toggle="tab"><i class="fa fa-exclamation" style="padding-right: 5px;"></i> Documentos Pendientes</a></li>
                              <li><a href="#tab_3" data-toggle="tab"><i class="fa fa-check" style="padding-right: 5px;"></i>Documentos Conciliados</a></li>
                            </ul>
                            <div class="tab-content">
                              <!-- /.tab-pane -->
                              <div class="tab-pane active" id="tab_2">
                                <table id="datas" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%" style="text-align: left;">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Cliente</th>
                                        <th>Fecha</th>
                                        <th>Metodo de pago</th>
                                        <th>Cantidad</th>
                                        <th>Descripción</th>
                                        <th>Acciones</th>
                                    </tr>
                                    </thead>
                                  </table>
                              </div>
                              <!-- /.tab-pane -->
                              <div class="tab-pane" id="tab_3">
                                <table id="datas_approved" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%" style="text-align: left;">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>N° Conciliación</th>
                                        <th>Cliente</th>
                                        <th>Fecha</th>
                                        <th>Metodo de pago</th>
                                        <th>Cantidad</th>
                                        <th>Descripción</th>
                                        <th>Acciones</th>
                                    </tr>
                                    </thead>
                                </table>
                              </div>
                              <!-- /.tab-pane -->
                            </div>
                            <!-- /.tab-content -->
                          </div>
                          <!-- nav-tabs-custom -->
                        </div>
                      
                    </div>
                </div>
            
            
            </div>


        </div>
    </section>
@endsection



@section('js')
    <!-- Utils ajax request -->
    <script src="{{ asset('js/api.js') }}"></script>
    <script src="{{ asset('js/validator.js') }}"></script>
    <script src="{{ asset('js/modal.js') }}"></script>
    <script src="{{ asset('js/awsidebar.js') }}"></script>
    <script src="{{ asset('js/jquery-editable-select.min.js') }}"></script>
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap-select/bootstrap-select.min.js') }}"></script>

    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>


    <script>

        //variables globales
        table = null;
        table_approved = null;
        fieldValues = [];
        
        // modals --------
        var rows = [
            [
                {field: 'Cliente', type: 'combo', id: 's_client_name', value: 'Cliente'}
            ],
            [
                {field: 'Cantidad', type: 'text', id: 's_amount', value: 'Cantidad'},
            ],
            [
                {field: 'Método de pago:', type: 'combo', id: 's_payment_method', value: 'Método de pago'},
                {field: 'Numero de documento', type: 'text', id: 's_doc_number', value: 'Numero de documento'},
            ],
            [
                {field: 'Descripción', type: 'textarea', id: 's_description', value: 'Descripción'},
            ],
            [
                {field: 'Imagen', type: 'file', id: 'upload-file-selector" onChange="itemImage(this);', value: 'Imagen'}
            ],
            [
                {field: '', type: 'imghid', id: 'i_image_preview', value: ''}
            ]
        ];

        var params = {
            title: 'Crear Conciliación',
            rows: rows,
            button: 'Agregar'
        }

        var modal_add_purchase = "modal_add_purchase";

        AWModal.create(modal_add_purchase, params);


        $(document).ready(function() {

            $('#upload-file-selector').click(function(){
              $('#upload-file-selector')[0].files[0] = null;
            });

            $('#s_client_name').selectpicker({
                liveSearch:true,
                noneSelectedText: 'Seleccione un cliente',
                noneResultsText: ''
            });

            $('#f_client_id').selectpicker({
                liveSearch:true,
                noneSelectedText: 'Seleccione un cliente',
                noneResultsText: ''
            });
               
            
            $('#s_date').datepicker({
                format: 'dd/mm/yyyy',
                gotoCurrent: true,
                language:'es'
            });
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

            var payment_method = $('#s_payment_method');
            $('<option />', {value: 0, text: 'Efectivo'}).appendTo(payment_method);
            $('<option />', {value: 1, text: 'Tarjeta'}).appendTo(payment_method);
            $('<option />', {value: 2, text: 'Cheque'}).appendTo(payment_method);
            $('<option />', {value: 3, text: 'Transferencia'}).appendTo(payment_method);
            
            $('#s_date').datepicker('setDate',moment(new Date()).format('DD/MM/YYYY'));


                AWApi.get('{{ url('/api/clients') }}',function (response) {
                 $('<option />', {value: '', text:'Seleccione un cliente' }).appendTo($("#s_client_name"));
                for (var i = 0; i < response.data.clients.length; i++) {
                    $('<option />', {value: response.data.clients[i].id, text: response.data.clients[i].name }).appendTo($("#s_client_name")) 
                }
                $("#s_client_name").append($("#s_client_name option").remove().sort(function(a, b) {
                        var at = $(a).text(), bt = $(b).text();
                        return (at > bt)?1:((at < bt)?-1:0);
                    }));
                    $('#s_client_name').val('');
                    $('#s_client_name').selectpicker('refresh');

                $('<option />', {value: '', text:'Seleccione un cliente' }).appendTo($("#f_client_id"));
                for (var i = 0; i < response.data.clients.length; i++) {
                    $('<option />', {value: response.data.clients[i].id, text: response.data.clients[i].name }).appendTo($("#f_client_id")) 
                }
                $("#f_client_id").append($("#f_client_id option").remove().sort(function(a, b) {
                        var at = $(a).text(), bt = $(b).text();
                        return (at > bt)?1:((at < bt)?-1:0);
                    }));
                    $('#f_client_id').val('');
                    $('#f_client_id').selectpicker('refresh');
            });
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
                    var filters = new Object();
                    
                    filters.code = $('#code').val();
                    filters.movement_status_id = 'No conciliado';
                    if ( moment($("#start_date").val(), 'DD/MM/YYYY').format('YYYY-MM-DD') != "Invalid date"){
                        filters.start_date = moment($("#start_date").val(), 'DD/MM/YYYY').format('YYYY-MM-DD');
                    }else{
                        filters.start_date = "";
                    }
                    if (  moment($("#end_date").val(), 'DD/MM/YYYY').format('YYYY-MM-DD') != "Invalid date"){
                        filters.end_date = moment($("#end_date").val(), 'DD/MM/YYYY').format('YYYY-MM-DD');
                    }else{
                        filters.end_date = "";
                    }
                    filters.client_id = $('#f_client_id').val();
                    data.filters = filters;

                    //extra data

                    AWApi.get('{{ url('/api/concilliation' ) }}?' + $.param(data), function (response) {
                        console.log(response);

                        callback({
                            recordsTotal: response.data.recordsTotal,
                            recordsFiltered: response.data.recordsFiltered,
                            data: response.data.concilliations
                        });
                    });
                },
                "paging": true,
                "columns": [
                    { "data": "id", "visible": false,},
                    { "data": "client_id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            name = "["+full.client.rut+"-"+full.client.rut_dv+"] - " + full.client.name;
                            return name;
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
                        "data":"payment_method",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            let result = '';
                            switch(full.payment_method){
                                case 'cash':
                                    result ='Efectivo';
                                break;
                                case 'card':
                                    result ='Tarjeta';
                                break;
                                case 'cheque':
                                    result ='Cheque';
                                break;
                                case 'transfer':
                                    result ='Transferencia';
                                break;

                            }
                            return result;
                        }
                    },
                    { "data": "amount", render : function(a,b,c,d){
                        return '$'+formatMoney(a, 'CL');
                        }
                    },
                    { "data": "description"},
                    { 
                        "data": "id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {

                            var edit = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=edit("+data+");>";
                            edit += "<i class='fa fa-lg fa-eye fa-fw' ></i></button>";

                            var approve = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=approveOrder("+data+");>";
                            approve += "<i class='fa fa-lg fa-thumbs-o-up fa-fw' ></i></button>";

                            var del = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=del("+data+");>";
                            del += "<i class='fa fa-lg fa-trash-o fa-fw' ></i></button>";

                            return "<div class='btn-group'>"+edit+" "+del+"</div>";
                        }
                    }
                ]
            });


            table_approved = $('#datas_approved').DataTable( {
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
                    var filters = new Object();
                    
                    filters.code = $('#code').val();
                    filters.movement_status_id = 'Conciliado';
                    if ( moment($("#start_date").val(), 'DD/MM/YYYY').format('YYYY-MM-DD') != "Invalid date"){
                        filters.start_date = moment($("#start_date").val(), 'DD/MM/YYYY').format('YYYY-MM-DD');
                    }else{
                        filters.start_date = "";
                    }
                    if (  moment($("#end_date").val(), 'DD/MM/YYYY').format('YYYY-MM-DD') != "Invalid date"){
                        filters.end_date = moment($("#end_date").val(), 'DD/MM/YYYY').format('YYYY-MM-DD');
                    }else{
                        filters.end_date = "";
                    }
                    filters.client_id = $('#f_client_id').val();
                    data.filters = filters;

                    //extra data

                    AWApi.get('{{ url('/api/concilliation' ) }}?' + $.param(data), function (response) {
                        callback({
                            recordsTotal: response.data.recordsTotal,
                            recordsFiltered: response.data.recordsFiltered,
                            data: response.data.concilliations
                        });
                    });
                },
                "paging": true,
                "columns": [
                    { "data": "id", "visible": false,},
                    { "data": "id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            return full.conciliation_detail[0].conciliation_id;
                        }
                    },
                    { "data": "client_id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            name = "["+full.client.rut+"-"+full.client.rut_dv+"] - " + full.client.name;
                            return name;
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
                        "data":"payment_method",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            let result = '';
                            switch(full.payment_method){
                                case 'cash':
                                    result ='Efectivo';
                                break;
                                case 'card':
                                    result ='Tarjeta';
                                break;
                                case 'cheque':
                                    result ='Cheque';
                                break;
                                case 'transfer':
                                    result ='Transferencia';
                                break;

                            }
                            return result;
                        }
                    },
                    { "data": "amount", render : function(a,b,c,d){
                        return '$'+formatMoney(a, 'CL');
                        }
                    },
                    { "data": "description"},
                    { 
                        "data": "id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {

                            var edit = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=edit("+data+");>";
                            edit += "<i class='fa fa-lg fa-eye fa-fw' ></i></button>";

                            return "<div class='btn-group'>"+edit+"</div>";
                        }
                    }
                ]
            });

            // set fields ----------------------

            (function setFieldValues() {
                fieldValues['code'] = 's_code';
                fieldValues['client_id'] = 'f_client_id';
                fieldValues['movement_status_id'] = 's_movement_status_id';
                fieldValues['date'] = 's_date';
            })();


            // Buttons triggers ------------------

            $('#filter').click(function(){
                table.ajax.reload();
                table_approved.ajax.reload();
            });

            $('#clean').click(function(){
                //todo: Crear función dejar limpio todos los campo de un formulario por id
                $('#code').val('');
                $('#start_date').val('');
                $('#end_date').val('');
                $('#f_client_id').val('');

                table.ajax.reload();
                table_approved.ajax.reload();
            });

        

            $('#myButton').click(function () {
                AWValidator.clean(modal_add_purchase);
                $(".modal-body input").val("");
                $('#' +modal_add_purchase).modal('show');
                $("#i_image_preview").hide();
            });

            $('#' + modal_add_purchase + "_create").click(function(){

                var data = new FormData();
                
                data.append('amount', $('#s_amount').val());
                data.append('client_id', $('#s_client_name').val());
                data.append('payment_method', $('#s_payment_method').val());
                data.append('doc_number', $('#s_doc_number').val());
                data.append('description', $('#s_description').val());
                data.append('file', $('#upload-file-selector')[0].files[0] );


                AWApi.post('{{ url('/api/concilliation') }}', data, function(datas){
                    submit(modal_add_purchase, datas, "Agregar Conciliación");


                });
            });        
            
        });

        // extra functions--------

        function approveOrder(id) {
            swal({
                title: "Aprobar Conciliación",
                text: "¿Esta seguro de realizar esta acción?'",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "SI",
                closeOnConfirm: true,
                cancelButtonText: "NO"
            },
            function () {
                data = new FormData();

                data.append('movement_status_id', 'Conciliado');
                AWApi.put('{{ url('/api/concilliation') }}/' + id, data, function(datas){
                    submit("", datas, "Aprobar Conciliación");
                });
            });
        }

        function submit(id,data, message)
        {
            var count = 0;
            if (id != ""){
                AWValidator.clean(id);
            }
            
   
            for (x in data.data.errors)
            {
                if(data.data.errors.unauthorized){
                    swal("Acceso Denegado", data.data.errors.unauthorized, "error");
                } else if(data.data.errors.message){
                    swal("error", data.data.errors.message, "error");
                }
                else{
                    AWValidator.error(fieldValues[x],data.data.errors[x].join('\n'));
                }
                count++;   
            }

            if (count == 0)
            {
                swal(message, "Información actualizada de forma exitosa", "success");
                $('#' +modal_add_purchase).modal('hide');
                table.ajax.reload();
                table_approved.ajax.reload();
                if(id == "modal_add_purchase"){
                    window.location.href = "{{ url( '/concilliation' ) }}/"+data.data.id;
                }
            }
     
        }

        function del(id) {

            swal({
                    title: "Eliminar Conciliación",
                    text: "¿Esta seguro de realizar esta acción?'",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "SI",
                    closeOnConfirm: true,
                    cancelButtonText: "NO"
                },
                function () {
                    AWApi.delete('{{ url('/api/concilliation' ) }}/'+id,function(data) {
                        submit("",data ,"Eliminar Conciliación");
                    });
                });
        }

        function edit(id) {
            window.location.href = "{{ url( '/concilliation' ) }}/"+id;
        }
        
        function itemImage(input) {
            
            if (input.files && input.files[0]) {
                console.log(input.files[0].type.indexOf('image'));
                if (input.files[0].type.indexOf('image') >= 0){
                    $("#i_image_preview").show();
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#i_image_preview').attr('src', e.target.result);
                    };

                    reader.readAsDataURL(input.files[0]);
                }else{
                    $("#i_image_preview").hide();
                }
            }
        }
    </script>
@endsection 

