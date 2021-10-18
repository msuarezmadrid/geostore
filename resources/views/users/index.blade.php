@extends('layouts.master')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users" style="padding-right: 5px;"></i> Usuarios
         <button type="button" class="btn btn-xs btn-success" id="myButton"><i class="fa fa-plus" style="padding-right: 5px;"></i>Agregar Usuario</button>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Usuarios</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                
            <div class="box box-primary box-xs flat">
                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-search" style="padding-right: 5px;"></i>Filtros</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div> 
                <!-- /.box-header -->
                <div class="box-body">
                  <div class="row">
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label for="name">Nombre</label>
                                <input type="text" class="form-control" id="name" placeholder="Nombre">
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label for="name">Email</label>
                                <input type="text" class="form-control" id="email" placeholder="Email">
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
            <!-- /.box -->

            <div class="box box-primary box-solid flat">

                <div class="box-header">
                    <h3 class="box-title"><i class="fa fa-list" style="padding-right: 5px;"></i>Lista de Usuarios</h3> 
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="col-xs-12" style="padding-bottom: 5px;">
                        <div class="btn-group">
                           
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <table id="datas" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                              <th>#</th>
                              <th>Nombre</th>
                              <th>Email</th>
                              <th>Administrador</th>
                              <th>Acciones</th>
                            </tr>
                        </thead>
                        </table>
                    </div>
                  
                </div>
            </div>
            <!-- /.box -->
        </div>
        </div>
    </section>
    <!-- /.content -->

@endsection

@section('js')
    <!-- Utils ajax request -->
    <script src="{{ asset('js/api.js') }}"></script>
    <script src="{{ asset('js/validator.js') }}"></script>
    <script src="{{ asset('js/modal.js') }}"></script>

    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>

    
    <script>

        //variables globales
        table = null;

        fieldValues = [];

        // modals --------
        var rows = [
            [
                {field: 'Nombre', type: 'text', id: 's_name', value: 'Nombre'}
            ],
            [
                {field: 'Email', type: 'text', id: 's_email', value: 'Email'}
            ],
            [
                {field: 'Rol', type: 'combo', id: 's_roles', value: 'Rol'},
                {field: 'Admin', type: 'combo', id: 's_admin', value: 'Admin'}
            ],
            [
                {field: 'Contraseña', type: 'password', id: 's_password', value: ''},
                {field: 'Confirmar Contraseña', type: 'password', id: 's_password_confirmation', value: ''}
            ]
        ];

        var params = {
            title: 'Creaci&oacute;n Usuario',
            rows: rows,
            button: 'Agregar'
        }

        var user_id = "modal_user";

        AWModal.create(user_id, params);


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
                    filters.email = $('#email').val();
                    filters.admin = $('#admin').val();

                    data.filters = filters;

                    //extra data

                    AWApi.get('{{ url('/api/users' ) }}?' + $.param(data), function (response) {
                        callback({
                            recordsTotal: response.data.recordsTotal,
                            recordsFiltered: response.data.recordsFiltered,
                            data: response.data.users
                        });
                    });
                },
                "paging": true,
                "columns": [
                    { "data": "id", "visible": false,},
                    { "data": "name"},
                    { "data": "email" },
                    { "data": "admin" },
                    { 
                        "data": "id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {

                            var edit = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=edit("+data+");>";
                            edit += "<i class='fa fa-lg fa-eye fa-fw' ></i></button>";

                            var del = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=del("+data+");>";
                            del += "<i class='fa fa-lg fa-trash-o fa-fw'></i></button>";

                            return "<div class='btn-group'>"+edit+" "+del+"</div>";
                        }
                    }
                ]
            });

            // set fields ----------------------

            (function setFieldValues() {
                fieldValues['name'] = 's_name';
                fieldValues['email'] = 's_email';
                fieldValues['admin'] = 's_admin';
                fieldValues['role'] = 's_roles';
                fieldValues['password'] = 's_password';
                fieldValues['password_confirmation'] = 's_password_confirmation';
                
            })();

            // Extra Data (combos) --------------

            var admin = $('#s_admin');
            $('<option />', {value: 0, text: 'NO'}).appendTo(admin);
            $('<option />', {value: 1, text: 'SI'}).appendTo(admin);

            AWApi.get('{{ url('/api/roles') }}', function selectRoles(datas) {

                var roles = $('#s_roles');

                $.map(datas.data.roles, function (data) {
                    console.log(data);
                    $('<option />', {
                        value: data.id,
                        text: data.name
                    }).appendTo(roles);
                });
            });
            // Buttons triggers ------------------

            $('#filter').click(function(){
                table.ajax.reload();
            });

            $('#clean').click(function(){
                //todo: Crear función dejar limpio todos los campo de un formulario por id
                $('#name').val('');
                $('#email').val('');
                $('#admin').val('');
                table.ajax.reload();
            });


            $("#export").click(function(){
            
            var filters = new Object();
            var data    = new Object();

            filters.name = $('#nombre').val();
            filters.email = $('#email').val();

            data.filters = filters;
            
            AWApi.download("{{ url('api/users/export') }}?" + $.param(data), function (response) {
                
            });
            
        });

            $('#myButton').click(function () {
                AWValidator.clean(user_id);
                $(".modal-body input").val("");
                $('#' +user_id).modal('show');
            });

            $('#' + user_id + "_create").click(function(){

                var data = new FormData();
                
                data.append('name', $('#s_name').val());
                data.append('email', $('#s_email').val());
                data.append('admin', $('#s_admin').val());
                data.append('role_id', $('#s_roles').val());
                data.append('password', $('#s_password').val());
                data.append('password_confirmation', $('#s_password_confirmation').val());
                

                AWApi.post('{{ url('/api/users') }}', data, function(datas){
                    submit(user_id, datas);
                });
            });

        });

        // extra functions--------

        function submit(id,data)
        {
            var count = 0;
            AWValidator.clean(id);
            for (x in data.data.errors)
            {
                if(data.data.errors.unauthorized){
                    swal("Acceso Denegado", data.data.errors.unauthorized, "error");
                }
                else{
                    AWValidator.error(fieldValues[x],data.data.errors[x].join('\n'));
                }
                count++;   
            }

            if (count == 0)
            {
                swal("Actualizar Usuario", "Información actualizada de forma exitosa", "success");
                $('#' +user_id).modal('toggle');
                table.ajax.reload();
            }
        }

        function del(id) {

            swal({
                    title: "Eliminar Usuario",
                    text: "¿Esta seguro de realizar esta acción?'",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "SI",
                    closeOnConfirm: true,
                    cancelButtonText: "NO"
                },
                function () {
                    AWApi.delete('{{ url('/api/users' ) }}/'+id,function(data) {

                        table.ajax.reload();
                        if(data.data.errors){
                            if(data.data.errors.unauthorized){
                                swal("Acceso Denegado", data.data.errors.unauthorized, "error");
                            }
                        }else{
                            swal("Eliminar Usuario", "Información actualizada correctamente", "success");
                        }
                    });
                });
        }

        function edit(id) {
            window.location.href = "{{ url( '/users' ) }}/"+id;
        }
    </script>
@endsection