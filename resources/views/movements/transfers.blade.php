@extends('layouts.master')

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css') }}">
@endsection


@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-exchange" style="padding-right: 5px;"></i>Transferencias
 <button type="button" class="btn btn-success btn-xs" id="myButton"> <i class="fa fa-plus" style="padding-right: 5px;"></i> Agregar Transferencias</button> 
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Transferencias</li>
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
                                    <label for="location_from_id">Almacén Origen</label>
                                        <select class="form-control" name="location" id="location_from_id"></select>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="form-group">
                                    <label for="location_to_id">Almacén Destino</label>
                                    <select class="form-control" name="location" id="location_to_id"></select>
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
                        <h3 class="box-title"><i class="fa fa-list" style="padding-right: 5px;"></i> Listado de Transferencias</h3>  
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="col-md-12">

                          <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                              <li class="active"><a href="#tab_1" data-toggle="tab"><i class="fa fa-file-o" style="padding-right: 5px;"></i> Borradores</a></li>
                              <li><a href="#tab_2" data-toggle="tab"><i class="fa fa-exclamation" style="padding-right: 5px;"></i> Transferencias Pendientes</a></li>
                              <li><a href="#tab_3" data-toggle="tab"><i class="fa fa-check" style="padding-right: 5px;"></i> Transferencias Aprobadas</a></li>
                            </ul>
                            <div class="tab-content">
                              <div class="tab-pane active" id="tab_1">
                                <table id="datas_draft" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%" style="text-align: left;">
                                    <thead>
                                    <tr>
                                      <th>#</th>
                                      <th>Código</th>
                                      <th>Fecha</th>
                                      <th>Almacen Origen</th>
                                      <th>Almacen Destino</th>
                                      <th>Acciones</th>
                                    </tr>
                                    </thead>
                                  </table>
                              </div>
                              <!-- /.tab-pane -->
                              <div class="tab-pane" id="tab_2">
                                <table id="datas" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%" style="text-align: left;">
                                    <thead>
                                    <tr>
                                      <th>#</th>
                                      <th>Código</th>
                                      <th>Fecha</th>
                                      <th>Almacen Origen</th>
                                      <th>Almacen Destino</th>
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
                                      <th>Almacen Destino</th>
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
        table_draft = null;
        fieldValues = [];

        // modals --------
        var rows = [
            [
                {field: 'Código', type: 'text', id: 's_code', value: 'Código'},
                {field: 'Fecha', type: 'text', id: 's_date', value: 'Fecha'}
            ],
            [
                {field: 'Almacén Origen', type: 'combo', id: 's_location_from_id', value: 'Almacén Origen'},
                {field: 'Almacén Destino', type: 'combo', id: 's_location_to_id', value: 'Almacén Destino'}
                
            ]
        ];

        var params = {
            title: 'Crear Transferencia',
            rows: rows,
            button: 'Agregar'
        }

        var modal_add_transfer = "modal_add_transfer";

        AWModal.create(modal_add_transfer, params);


        $(document).ready(function() {

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
                
                $('<option />', {value: '', text:'TODOS' }).appendTo($("#location_from_id"));
                $('<option />', {value: '', text:'TODOS' }).appendTo($("#location_to_id"));
                for (var i = 0; i < response.data.tree.length; i++) {
                    name = "";
                    for (var j = 0; j < response.data.tree[i].level -1; j++) {name += "";}
                    name += " [" + response.data.tree[i].code +"] "+response.data.tree[i].name;
                    $('<option />', {value: response.data.tree[i].id, text: name }).appendTo($("#location_from_id"));
                    $('<option />', {value: response.data.tree[i].id, text: name }).appendTo($("#location_to_id"));  
                }
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
                    filters.location_from_id = $('#location_from_id').val();
                    filters.location_to_id = $('#location_to_id').val();
                    data.filters = filters;

                    //extra data

                    AWApi.get('{{ url('/api/transfers' ) }}?' + $.param(data), function (response) {
                        callback({
                            recordsTotal: response.data.recordsTotal,
                            recordsFiltered: response.data.recordsFiltered,
                            data: response.data.transfers
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
                    { "data": "location_from_id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            name = "["+full.location_from.code+"] - " + full.location_from.name;
                            return name;
                        }
                    },
                    { "data": "location_to_id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            name = "["+full.location_to.code+"] - " + full.location_to.name;
                            return name;
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

            table_draft = $('#datas_draft').DataTable( {
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
                    filters.movement_status_id = 1;
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
                    filters.location_from_id = $('#location_from_id').val();
                    filters.location_to_id = $('#location_to_id').val();

                    data.filters = filters;

                    //extra data

                    AWApi.get('{{ url('/api/transfers' ) }}?' + $.param(data), function (response) {
                        callback({
                            recordsTotal: response.data.recordsTotal,
                            recordsFiltered: response.data.recordsFiltered,
                            data: response.data.transfers
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
                   { "data": "location_from_id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            name = "["+full.location_from.code+"] - " + full.location_from.name;
                            return name;
                        }
                    },
                    { "data": "location_to_id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            name = "["+full.location_to.code+"] - " + full.location_to.name;
                            return name;
                        }
                    },
                    
                    { 
                        "data": "id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {

                            var edit = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=edit("+data+");>";
                            edit += "<i class='fa fa-lg fa-eye fa-fw' ></i></button>";

                            var pub = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=publishOrder("+data+");>";
                            pub += "<i class='fa fa-lg fa-mail-forward fa-fw' ></i></button>";

                            var del = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=del("+data+");>";
                            del += "<i class='fa fa-lg fa-trash-o fa-fw' ></i></button>";

                            return "<div class='btn-group'>"+edit+" "+pub+" "+del+"</div>";
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
                    filters.location_from_id = $('#location_from_id').val();
                    filters.location_to_id = $('#location_to_id').val();

                    data.filters = filters;

                    //extra data

                    AWApi.get('{{ url('/api/transfers' ) }}?' + $.param(data), function (response) {
                        callback({
                            recordsTotal: response.data.recordsTotal,
                            recordsFiltered: response.data.recordsFiltered,
                            data: response.data.transfers
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
                    { "data": "location_from_id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            name = "["+full.location_from.code+"] - " + full.location_from.name;
                            return name;
                        }
                    },
                    { "data": "location_to_id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            name = "["+full.location_to.code+"] - " + full.location_to.name;
                            return name;
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
                fieldValues['location_to_id'] = 's_location_to_id';
                fieldValues['location_from_id'] = 's_location_from_id';
                fieldValues['movement_status_id'] = 's_movement_status_id';
                fieldValues['date'] = 's_date';
            })();


            // Buttons triggers ------------------

            $('#filter').click(function(){
                table.ajax.reload();
                table_approved.ajax.reload();
                table_draft.ajax.reload();
            });

            $('#clean').click(function(){
                //todo: Crear función dejar limpio todos los campo de un formulario por id
                $('#code').val('');
                $('#start_date').val('');
                $('#end_date').val('');
                $('#location_from_id').val('');
                $('#location_to_id').val('');

                table.ajax.reload();
                table_approved.ajax.reload();
                table_draft.ajax.reload();
            });

        

            $('#myButton').click(function () {
                AWValidator.clean(modal_add_transfer);
                $(".modal-body input").val("");

                AWApi.get('{{ url('/api/locations') }}',function (response) {
                    $("#s_location_to_id").empty();
                    $("#s_location_from_id").empty();
                    for (var i = 0; i < response.data.tree.length; i++) {
                        name = "";
                        for (var j = 0; j < response.data.tree[i].level -1; j++) {name += "";}
                        name += " [" + response.data.tree[i].code +"] "+response.data.tree[i].name;
                        $('<option />', {value: response.data.tree[i].id, text: name }).appendTo($("#s_location_to_id"));
                        $('<option />', {value: response.data.tree[i].id, text: name }).appendTo($("#s_location_from_id")); 
                    }
                });



                $('#' +modal_add_transfer).modal('show');
            });

            $('#' + modal_add_transfer + "_create").click(function(){

                var data = new FormData();
                
                data.append('name', $('#s_name').val());
                data.append('code', $('#s_code').val());
                data.append('date', moment($("#s_date").val(), 'DD/MM/YYYY').format('YYYY-MM-DD'));
                data.append('location_from_id', $('#s_location_from_id').val());
                data.append('location_to_id', $('#s_location_to_id').val());
                data.append('movement_status_id', 1);

                AWApi.post('{{ url('/api/transfers') }}', data, function(datas){
                    submit("", datas, "Agregar Transferencia");
                });
            });

        });

        // extra functions--------
        function publishOrder(id) {
            swal({
                title: "Publicar Transferencia",
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
                AWApi.put('{{ url('/api/transfers') }}/' + id, data, function(datas){
                    submit(modal_add_transfer, datas, "Publicar Transferencia");
                });
            });
            
        }

        function approveOrder(id) {
            swal({
                title: "Aprobar Transferencia",
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

                data.append('movement_status_id', 3);
                AWApi.put('{{ url('/api/transfers') }}/' + id, data, function(datas){
                    submit("", datas, "Aprobar Transferencia");
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
                $('#' +modal_add_transfer).modal('hide');

                if(id == "modal_add_transfer"){
                    window.location.href = "{{ url( '/transfers' ) }}/"+data.data.id;
                }
                table.ajax.reload();
                table_draft.ajax.reload();
                table_approved.ajax.reload();
            }
     
        }

        function del(id) {

            swal({
                    title: "Eliminar Transferencia",
                    text: "¿Esta seguro de realizar esta acción?'",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "SI",
                    closeOnConfirm: true,
                    cancelButtonText: "NO"
                },
                function () {
                    AWApi.delete('{{ url('/api/transfers' ) }}/'+id,function(data) {
                        submit("",data ,"Eliminar Transferencia");
                    });
                });
        }

        function edit(id) {
            window.location.href = "{{ url( '/transfers' ) }}/"+id;
        }
    </script>
@endsection 

