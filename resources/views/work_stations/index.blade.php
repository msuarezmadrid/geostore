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
        <i class="fa fa-industry" style="padding-right: 5px;"></i> Estaciones de Trabajo
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Estaciones de Trabajo</li>
      </ol>
    </section>
    
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-md-12">
                

            <div class="box box-primary flat box-solid">

                <div class="box-header">
                    <i class="fa fa-list"></i><h3 class="box-title"> Listado</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="btn-group">
                        <button type="button" class="btn btn-xs btn-success" id="myButton"><i class="fa fa-plus" style="padding-right: 5px;"></i>Agregar Estación de Trabajo</button>
                    </div>
                  <table id="datas" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%" style="text-align: left;">
                    <thead>
                    <tr>
                      <th>#</th>
                      <th>Código</th>
                      <th>Nombre</th>
                      <th>Descripción</th>
                      <th>Acciones</th>
                    </tr>
                    </thead>
                  </table>
                </div>
            </div>
            <!-- /.box -->
            </div>
            <div class="col-xs-12 col-md-6" hidden>
            
             <div class="box box-primary flat box-solid ">

                <div class="box-header">
                    <i class="fa fa-fax"></i><h3 class="box-title"> Contactos por proveedor</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="btn-group">
                        <button type="button" class="btn btn-xs btn-success" id="myButton_contacts"><i class="fa fa-plus" style="padding-right: 5px;"></i>Agregar Contacto</button>
                    </div>
                  <table id="datas_contacts" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%" style="text-align: left;">
                    <thead>
                    <tr>
                      <th>#</th>
                      <th>Proveedor</th>
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
        table_contacts = null;
        fieldValues = [];
        selected_work_station_id = null;

        // modals --------
        var rows = [
            [
            	{field: 'Código', type: 'text', id: 's_code', value: 'Código'},
                {field: 'Nombre', type: 'text', id: 's_name', value: 'Nombre'}
            ],
            [
            	{field: 'Descripción', type: 'textarea', rows: 3,id: 's_description', value: 'Descripción'}
            ]
        ];
        var params = {
            title: 'Agregar de Estación de Trabajo',
            rows: rows
        }
        var modal_work_station = "modal_work_station";
        AWModal.create(modal_work_station, params);




        var rows = [
            [
            	{field: 'Código', type: 'text', id: 'we_code', value: 'Código'},
                {field: 'Nombre', type: 'text', id: 'we_name', value: 'Nombre'}
            ],
            [
            	{field: 'Descripción', type: 'textarea', rows: 3,id: 'we_description', value: 'Descripción'}
            ]
        ];
        var params = {
            title: 'Editar de Estación de Trabajo',
            rows: rows
        }
        var modal_edit_work_station = "modal_edit_work_station";
        AWModal.create(modal_edit_work_station, params);


/*
        var rows = [
            [
                {field: 'Proveedor', type: 'combo', id: 'add_contact_id', value: 'Proveedor'}
            ],
            [
                {field: 'Nombre', type: 'text', id: 'add_contact_name', value: 'Nombre'}
            ],
            [
                {field: 'Teléfono', type: 'text', id: 'add_contact_phone', value: 'Teléfono'},
                {field: 'Email', type: 'text', id: 'add_contact_email', value: 'Email'}
            ]
        ];
        var params = {
            title: 'Crear Contacto',
            rows: rows,
            button: 'Guardar'
        }
        var modal_add_contact = "modal_add_contact";
        AWModal.create(modal_add_contact, params);

*/
        $(document).ready(function() {


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
                
                    data.filters = filters;

                    //extra data

                    AWApi.get('{{ url('/api/work_stations' ) }}?' + $.param(data), function (response) {
                        callback({
                            recordsTotal: response.data.recordsTotal,
                            recordsFiltered: response.data.recordsFiltered,
                            data: response.data.work_stations
                        });
                    });
                },
                "paging": true,
                "columns": [
                    { "data": "id", "visible": false,},
                    { "data": "code"},
                    { "data": "name"},
                    { "data": "description"},
                    { 
                        "data": "id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {

                            var edit = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;'>";
                            edit += "<i class='fa fa-lg fa-edit fa-fw' onclick=editWorkStation("+data+");></i></button>";

                            var del = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;'>";
                            del += "<i class='fa fa-lg fa-trash-o fa-fw' onclick=del("+data+");></i></button>";

                            return "<div class='btn-group'>"+edit +" "+del+"</div>";
                        }
                    }
                ]
            });


            table_contacts = $('#datas_contacts').DataTable( {
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
                    
                    filters.name = $('#name').val();

                    data.filters = filters;
                    data.with = "contacts"
                    //extra data

                    AWApi.get('{{ url('/api/suppliers' ) }}?' + $.param(data), function (response) {
                        callback({
                            recordsTotal: response.data.contactsTotal,
                            recordsFiltered: response.data.contactsFiltered,
                            data: response.data.contacts
                        });
                    });
                },
                "paging": true,
                "columns": [
                    { "data": "id", "visible": false,},
                    { "data": "supplier.name"},
                    { "data": "contact.name"},
                    { "data": "contact.phone"},
                    { "data": "contact.email"},
                    { 
                        "data": "id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {

                            var edit = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;'>";
                            edit += "<i class='fa fa-lg fa-edit fa-fw' onclick=editContact("+data+");></i></button>";

                            var del = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;'>";
                            del += "<i class='fa fa-lg fa-trash-o fa-fw' onclick=delContact("+data+");></i></button>";

                            return "<div class='btn-group'>"+" "+del+"</div>";
                        }
                    }
                ]
            });

            // set fields ----------------------

            (function setFieldValues() {
                fieldValues['name'] = 's_name';
                fieldValues['lastname'] = 's_lastname';
            })();


            // Buttons triggers ------------------

            $('#filter').click(function(){
                table.ajax.reload();
            });

            $('#clean').click(function(){
                //todo: Crear función dejar limpio todos los campo de un formulario por id
                $('#s_name').val('');
                $('#s_lastname').val('');
                table.ajax.reload();
            });

        

            $('#myButton').click(function () {
                AWValidator.clean(modal_work_station);
                $(".modal-body input").val("");
                $(".modal-body textarea").html("");
                $('#' +modal_work_station).modal('show');
            });

            $('#' + modal_work_station + "_create").click(function(){

                var data = new FormData();
                console.log("ASDASDASD");
                console.log( $("#s_description").val());
                data.append('name', $('#s_name').val());
                data.append('code', $("#s_code").val());
                data.append('description', $("#s_description").val());

                AWApi.post('{{ url('/api/work_stations') }}', data, function(datas){
                    fieldValues = [];
                    fieldValues['name'] = 's_name';
                    fieldValues['code'] = 's_code';
                    fieldValues['description'] = 's_description';
                    submit(modal_work_station, datas, "Crear Estación de Trabajo");
                });
            });


            $('#' + modal_edit_work_station + "_create").click(function(){

                var data = new FormData();
                data.append('name', $('#we_name').val());
                data.append('code', $("#we_code").val());
                data.append('description', $("#we_description").val());

                AWApi.put('{{ url('/api/work_stations') }}/'+selected_work_station_id, data, function(datas){
                    fieldValues = [];
                    fieldValues['name'] = 'we_name';
                    fieldValues['code'] = 'we_code';
                    fieldValues['description'] = 'we_description';
                    submit(modal_edit_work_station, datas, "Actualizar Estación de Trabajo");
                });
                $(".modal").modal('hide');
            });


          /*  $('#myButton_contacts').click(function () {
                AWValidator.clean(modal_add_contact);
                AWApi.get("{{ url('api/suppliers')}}"+"?with=contacts", function (datas) {
                    $("#add_contact_id").empty();
                    for (var i = 0; i < datas.data.suppliers.length; i++) {
                        $('<option />', {value: datas.data.suppliers[i].id, text:datas.data.suppliers[i].name }).appendTo($("#add_contact_id"));
                    }
                    
                });
                $(".modal-body input").val("");
                $('#' +modal_add_contact).modal('show');
            });

            $('#' + modal_add_contact + "_create").click(function(){

                var data = new FormData();
                
                data.append('name', $('#add_contact_name').val());
                data.append('phone', $('#add_contact_phone').val());
                data.append('email', $('#add_contact_email').val());
                data.append('supplier_id', $("#add_contact_id").val());
                AWApi.post('{{ url('/api/contacts') }}', data, function(datas){
                    fieldValues = [];
                    fieldValues['name'] = 'add_contact_name';
                    fieldValues['email'] = 'add_contact_email';
                    fieldValues['phone'] = 'add_contact_phone';
                    fieldValues['supplier_id'] = 'add_contact_id';
                    submit(modal_add_contact, datas, "Crear Contacto");
                });
            });
*/

        });

        // extra functions--------

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
                $('#' +modal_work_station).modal('hide');
                table.ajax.reload();
            }
     
        }

        function del(id) {

            swal({
                    title: "Eliminar Estación de Trabajo",
                    text: "¿Esta seguro de realizar esta acción?'",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "SI",
                    closeOnConfirm: true,
                    cancelButtonText: "NO"
                },
                function () {
                    AWApi.delete('{{ url('/api/work_stations' ) }}/'+id, function(data) {
                        submit("", data, "Eliminar Estación de Trabajo");
                    });
                });
        }

        function delContact(id) {

            swal({
                    title: "Eliminar Contacto",
                    text: "¿Esta seguro de realizar esta acción?'",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "SI",
                    closeOnConfirm: true,
                    cancelButtonText: "NO"
                },
                function () {
                    AWApi.delete('{{ url('/api/supplier_contacts' ) }}/'+id, function(data) {
                        submit("", data, "Eliminar Contacto");
                        table_contacts.ajax.reload();
                    });
                });
        }
        function editWorkStation(id){
        	selected_work_station_id = id;
        	AWValidator.clean(modal_edit_work_station);
            $(".modal-body input").val("");
            $(".modal-body textarea").html("");
        	AWApi.get('{{url("/api/work_stations")}}/'+id, function(data){
        		console.log(data);
        		$("#we_code").val(data.data.code);
        		$("#we_name").val(data.data.name);
        		$("#we_description").html(data.data.description);
        		$('#' +modal_edit_work_station).modal('show');
        	})
        		
                
        }

        function edit(id) {
            window.location.href = "{{ url( '/suppliers' ) }}/"+id;
        }
    </script>
@endsection 

