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
        <i class="fa fa-truck" style="padding-right: 5px;"></i> Proveedores
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Proveedores</li>
      </ol>
    </section>
    
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-md-6">
                

            <div class="box box-primary flat box-solid">

                <div class="box-header">
                    <i class="fa fa-truck"></i><h3 class="box-title"> Listado de Proveedores</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="btn-group">
                        <button type="button" class="btn btn-xs btn-success" id="myButton"><i class="fa fa-plus" style="padding-right: 5px;"></i>Agregar Proveedor</button>
                    </div>
                  <table id="datas" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%" style="text-align: left;">
                    <thead>
                    <tr>
                      <th>#</th>
                      <th>Nombre</th>
                      <th>Acciones</th>
                    </tr>
                    </thead>
                  </table>
                </div>
            </div>
            <!-- /.box -->
            </div>
            <div class="col-xs-12 col-md-6">
            
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

        // modals --------
        var rows = [
            [
                {field: 'Nombre', type: 'text', id: 's_name', value: 'Nombre'}
            ]
        ];
        var params = {
            title: 'Creaci&oacute;n Proveedor',
            rows: rows,
            button: 'Agregar'
        }
        var modal_supplier = "modal_supplier";
        AWModal.create(modal_supplier, params);

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
                    
                    filters.name = $('#name').val();

                    data.filters = filters;

                    //extra data

                    AWApi.get('{{ url('/api/suppliers' ) }}?' + $.param(data), function (response) {
                        callback({
                            recordsTotal: response.data.recordsTotal,
                            recordsFiltered: response.data.recordsFiltered,
                            data: response.data.suppliers
                        });
                    });
                },
                "paging": true,
                "columns": [
                    { "data": "id", "visible": false,},
                    { "data": "razon_social"},
                    { 
                        "data": "id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {

                            var edit = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;'>";
                            edit += "<i class='fa fa-lg fa-edit fa-fw' onclick=edit("+data+");></i></button>";

                            var del = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;'>";
                            del += "<i class='fa fa-lg fa-trash-o fa-fw' onclick=del("+data+");></i></button>";

                            return "<div class='btn-group'>"+" "+del+"</div>";
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
                    { "data": "supplier.razon_social"},
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
                AWValidator.clean(modal_supplier);
                $(".modal-body input").val("");
                $('#' +modal_supplier).modal('show');
            });

            $('#' + modal_supplier + "_create").click(function(){

                var data = new FormData();
                
                data.append('name', $('#s_name').val());
                AWApi.post('{{ url('/api/suppliers') }}', data, function(datas){
                    fieldValues = [];
                    fieldValues['name'] = 's_name';
                    submit(modal_supplier, datas, "Crear Proveedor");
                });
            });

            $('#myButton_contacts').click(function () {
                AWValidator.clean(modal_add_contact);
                AWApi.get("{{ url('api/suppliers')}}"+"?with=contacts", function (datas) {
                    $("#add_contact_id").empty();
                    for (var i = 0; i < datas.data.suppliers.length; i++) {
                        $('<option />', {value: datas.data.suppliers[i].id, text:datas.data.suppliers[i].razon_social }).appendTo($("#add_contact_id"));
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
                $('#' +modal_supplier).modal('hide');
                $('#' +modal_add_contact).modal('hide');
                table.ajax.reload();
                table_contacts.ajax.reload();
            }
     
        }

        function del(id) {

            swal({
                    title: "Eliminar Proveedor",
                    text: "¿Esta seguro de realizar esta acción?'",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "SI",
                    closeOnConfirm: true,
                    cancelButtonText: "NO"
                },
                function () {
                    AWApi.delete('{{ url('/api/suppliers' ) }}/'+id, function(data) {
                        submit("", data, "Eliminar Proveedor");
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

        function edit(id) {
            window.location.href = "{{ url( '/suppliers' ) }}/"+id;
        }
    </script>
@endsection 

