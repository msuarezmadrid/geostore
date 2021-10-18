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
        <i class="fa fa-arrow-right" style="padding-right: 5px;"></i> 
        Ordenes de Salida
        <button type="button" class="btn btn-success btn-xs" id="myButton">
        <i class="fa fa-plus" style="padding-right: 5px;"></i> 
        Agregar Orden de Salida</button> 
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Ordenes de Salida</li>
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
                    <!-- /.box-header -->
                    <div class="box-body">
                      <div class="row">
                            <div class="col-xs-4">
                                <div class="form-group">
                                    <label for="code">Código</label>
                                    <input type="text" class="form-control" id="code" placeholder="Código">
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group">
                                    <label for="start_date">Fecha Inicio</label>
                                    <input type="text" class="form-control" id="start_date" placeholder="Fecha Inicio">
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="form-group">
                                    <label for="end_date">Fecha Fin</label>
                                    <input type="text" class="form-control" id="end_date" placeholder="Fecha Fin">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                             <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="location_id">Almacén</label>
                                        <select class="form-control" name="location" id="location_id"></select>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="client_id">Cliente</label>
                                    <select class="form-control" name="location" id="client_id"></select>
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
                        <h3 class="box-title"><i class="fa fa-list" style="padding-right: 5px;"></i> Listado de Ordenes de Salida</h3>  
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="col-md-12">

                          <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                              <li class="active"><a href="#tab_2" data-toggle="tab"><i class="fa fa-exclamation" style="padding-right: 5px;"></i> Ordenes de Salida Pendientes</a></li>
                              <li><a href="#tab_3" data-toggle="tab"><i class="fa fa-check" style="padding-right: 5px;"></i>Ordenes de Salida Aprobadas</a></li>
                            </ul>
                            <div class="tab-content">
                              <!-- /.tab-pane -->
                              <div class="tab-pane active" id="tab_2">
                                <table id="datas" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%" style="text-align: left;">
                                    <thead>
                                    <tr>
                                      <th>#</th>
                                      <th>Código</th>
                                      <th>Fecha</th>
                                      <th>Almacen Origen</th>
                                      <th>Cliente</th>
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
                                      <th>Código</th>
                                      <th>Fecha</th>
                                      <th>Almacen Origen</th>
                                      <th>Cliente</th>
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
                <!-- /.box -->
                    
            
            
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
        let itemMax = 1000;

        // modals --------
        var rows = [
            [
                {field: 'Código', type: 'text', id: 's_code', value: 'Código'},
                {field: 'Fecha', type: 'text', id: 's_date', value: 'Fecha'}
            ],
            [
                {field: 'Almacén', type: 'combo', id: 's_location_id', value: 'Almacén'},
                {field: 'Cliente:', type: 'combo', id: 's_client_id', value: 'Cliente'}
                
            ]
        ];

        var params = {
            title: 'Crear Orden de Salida',
            rows: rows,
            button: 'Agregar'
        }

        var modal_add_sale = "modal_add_sale";

        AWModal.create(modal_add_sale, params);


        $(document).ready(function() {

            $('#s_client_id').selectpicker({
                liveSearch:true,
            });

            $('#s_location_id').selectpicker({
                liveSearch:true,
            });
                
            $('#client_id').selectpicker({
                liveSearch:true,
            });

            $('#location_id').selectpicker({
                liveSearch:true,
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
            
            $('#s_date').datepicker('setDate',moment(new Date()).format('DD/MM/YYYY'));

            AWApi.get('{{ url('/api/locations') }}',function (response) {
                
                $('<option />', {value: '', text:'TODOS' }).appendTo($("#location_id"));
                for (var i = 0; i < response.data.tree.length; i++) {
                    name = "";
                    for (var j = 0; j < response.data.tree[i].level -1; j++) {name += "";}
                    name += " [" + response.data.tree[i].code +"] "+response.data.tree[i].name;
                    $('<option />', {value: response.data.tree[i].id, text: name }).appendTo($("#location_id")); 
                }

                $("#location_id").append($("#location_id option").remove().sort(function(a, b) {
                        var at = $(a).text(), bt = $(b).text();
                        return (at > bt)?1:((at < bt)?-1:0);
                    }));
                $('#location_id').val('');
                $('#location_id').selectpicker('refresh');
            });

            AWApi.get('{{ url('/api/clients') }}',function (response) {
                 $('<option />', {value: '', text:'TODOS' }).appendTo($("#client_id"));
                for (var i = 0; i < response.data.clients.length; i++) {
                    $('<option />', {value: response.data.clients[i].id, text: response.data.clients[i].name }).appendTo($("#client_id")) 
                }
                $("#client_id").append($("#client_id option").remove().sort(function(a, b) {
                        var at = $(a).text(), bt = $(b).text();
                        return (at > bt)?1:((at < bt)?-1:0);
                    }));
                    $('#client_id').val('');
                    $('#client_id').selectpicker('refresh');
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
                    filters.name = "";
                    filters.code = $('#code').val();
                    filters.movement_status_id = 2;
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
                    filters.location_id = $('#location_id').val();
                    filters.client_id = $('#client_id').val();
                    data.filters = filters;

                    //extra data

                    AWApi.get('{{ url('/api/sales' ) }}?' + $.param(data), function (response) {
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
                    { "data": "code"},
                    { "data": "date",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            date = moment(data).format("DD/MM/YYYY");
                            return date;
                        }
                    },
                    { "data": "location_id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            name = "["+full.location.code+"] - " + full.location.name;
                            return name;
                        }
                    },
                    { "data": "client_id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            return full.client.name;
                        }
                    },
                    
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

                            return "<div class='btn-group'>"+edit+" "+approve+" "+del+"</div>";
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
                    filters.name = "";
                    filters.code = $('#code').val();
                    filters.movement_status_id = 3;
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
                    filters.location_id = $('#location_id').val();
                    filters.client_id = $('#client_id').val();

                    data.filters = filters;

                    //extra data

                    AWApi.get('{{ url('/api/sales' ) }}?' + $.param(data), function (response) {
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
                    { "data": "code"},
                    { "data": "date",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            date = moment(data).format("DD/MM/YYYY");
                            return date;
                        }
                    },
                    { "data": "location_id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            name = "["+full.location.code+"] - " + full.location.name;
                            return name;
                        }
                    },
                    { "data": "client_id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            return full.client.name;
                        }
                    },
                    
                    { 
                        "data": "id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {

                            var edit = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=edit("+data+");>";
                            edit += "<i class='fa fa-lg fa-eye fa-fw' ></i></button>";

                            

                            var del = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=del("+data+");>";
                            del += "<i class='fa fa-lg fa-trash-o fa-fw' ></i></button>";

                            return "<div class='btn-group'>"+edit+"</div>";
                        }
                    }
                ]
            });

            // set fields ----------------------

            (function setFieldValues() {
                fieldValues['code'] = 's_code';
                fieldValues['client_id'] = 's_client_id';
                fieldValues['location_id'] = 's_location_id';
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
                $('#location_id').val('');
                $('#client_id').val('');
                $('#client_id').selectpicker('refresh');
                $('#location_id').selectpicker('refresh');


                table.ajax.reload();
                table_approved.ajax.reload();
            });

        

            $('#myButton').click(function () {
                AWValidator.clean(modal_add_sale);
                $(".modal-body input").val("");

                AWApi.get('{{ url('/api/locations') }}',function (response) {
                    $('#s_location_id').empty()
                    for (var i = 0; i < response.data.tree.length; i++) {
                        name = "";
                        for (var j = 0; j < response.data.tree[i].level -1; j++) {name += "";}
                        name += " [" + response.data.tree[i].code +"] "+response.data.tree[i].name;
                        $('<option />', {value: response.data.tree[i].id, text: name }).appendTo($("#s_location_id")); 
                    }
                    $("#s_location_id").append($("#s_location_id option").remove().sort(function(a, b) {
                        var at = $(a).text(), bt = $(b).text();
                        return (at > bt)?1:((at < bt)?-1:0);
                    }));
                $("#s_location_id").val($("#s_location_id option:first").val());
                $('#s_location_id').selectpicker('refresh');
                });

                AWApi.get('{{ url('/api/clients') }}',function (response) {
                    $('#s_client_id').empty()
                    for (var i = 0; i < response.data.clients.length; i++) {
                        $('<option />', {value: response.data.clients[i].id, text: response.data.clients[i].name }).appendTo($("#s_client_id")); 
                    }
                    $("#s_client_id").append($("#s_client_id option").remove().sort(function(a, b) {
                        var at = $(a).text(), bt = $(b).text();
                        return (at > bt)?1:((at < bt)?-1:0);
                    }));
                $("#s_client_id").val($("#s_client_id option:first").val());   
                $('#s_client_id').selectpicker('refresh');
                });

                AWApi.get('{{ url('/api/movement_statuses') }}',function (response) {
                    for (var i = 0; i < response.data.movement_statuses.length; i++) {
                        $('<option />', {value: response.data.movement_statuses[i].id, text: response.data.movement_statuses[i].name }).appendTo($("#s_movement_status_id")); 
                    }
                });


                $('#' +modal_add_sale).modal('show');
            });

            $('#' + modal_add_sale + "_create").click(function(){

                var data = new FormData();
                
                data.append('name', $('#s_name').val());
                data.append('code', $('#s_code').val());
                data.append('date', moment($("#s_date").val(), 'DD/MM/YYYY').format('YYYY-MM-DD'));
                data.append('location_id', $('#s_location_id').val());
                data.append('client_id', $('#s_client_id').val());
                data.append('movement_status_id', 2);

                AWApi.post('{{ url('/api/sales') }}', data, function(datas){
                    submit(modal_add_sale, datas, "Agregar Orden de Salida");
                });
            });

        });

        // extra functions--------
        function publishOrder(id) {
            swal({
                title: "Publicar Orden de Salida",
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

                data.append('movement_status_id', 2);
                AWApi.put('{{ url('/api/sales') }}/' + id, data, function(datas){
                    submit("", datas, "Publicar Orden de Salida");
                });
            });
            
        }

        function approveOrder(id) {
            swal({
                title: "Aprobar Orden de Salida",
                text: "¿Esta seguro de realizar esta acción?'",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "SI",
                closeOnConfirm: false,
                closeOnCancel: true,
                cancelButtonText: "NO"
            },
            function (confirm) {
                if(confirm) {
                    data = new FormData();
                    swal({
                        title: "Aprobando Orden...",
                        type: "info",
                        showCancelButton: false,
                        showConfirmButton: false,
                        text: "Se está aprobando la orden, esto puede tardar varios segundos",
                        icon: "{{asset('img/pos/loading_spinner.gif')}}",
                        });

                    data.append('movement_status_id', 3);
                    AWApi.put('{{ url('/api/sales') }}/' + id, data, function(datas){
                        submit("", datas, "Aprobar Orden de Salida");
                    });
                }
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
                $('#' +modal_add_sale).modal('hide');

                if(id == "modal_add_sale"){
                    window.location.href = "{{ url( '/sales' ) }}/"+data.data.id;
                }
                table.ajax.reload();
                table_approved.ajax.reload();
            }
     
        }

        function del(id) {

            swal({
                    title: "Eliminar Orden de Salida",
                    text: "¿Esta seguro de realizar esta acción?'",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "SI",
                    closeOnConfirm: true,
                    cancelButtonText: "NO"
                },
                function () {
                    AWApi.delete('{{ url('/api/sales' ) }}/'+id,function(data) {
                        submit("",data ,"Eliminar Orden de Salida");
                    });
                });
        }

        function edit(id) {
            window.location.href = "{{ url( '/sales' ) }}/"+id;
        }
    </script>
@endsection 

