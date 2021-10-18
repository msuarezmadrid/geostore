@extends('layouts.master')

@section('css')
	<!-- bootstrap colorpicker -->
    <link rel="stylesheet" href="{{ asset('plugins/colorpicker/bootstrap-colorpicker.min.css')}} ">
    <style>
        td{
            padding-top: 2px !important;
            padding-bottom: 1px !important;
        }
    </style>
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 id="roles_title">Roles <small>Listado de permisos</small></h1>
      <ol class="breadcrumb">
        <li><a href="{{url('dashboards')}}"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li><a href="{{url('roles')}}">Roles</a></li>
        <li class="active">Permisos</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                  
            <div class="box box-primary box-solid flat">
                <div class="box-header">
                    <h3 class="box-title">Listado de permisos</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>

                </div>  
                
                <!-- /.box-header -->
                <div class="box-body">
                <div class="col col-xs-10 col-xs-offset-1">
                  <table id="datas" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                      <th>Recurso</th>
                      <th class="text-center">Ver</th>
                      <th class="text-center">Crear</th>
                      <th class="text-center">Editar</th>
                      <th class="text-center">Eliminar</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Usuarios</td>
                            <td class="text-center"><input type="checkbox" id="users_view"></td>
                            <td class="text-center"><input type="checkbox" id="users_create"></td>
                            <td class="text-center"><input type="checkbox" id="users_edit"></td>
                            <td class="text-center"><input type="checkbox" id="users_delete"></td>
                        </tr>
                        <tr>
                            <td>Roles/Permisos</td>
                            <td class="text-center"><input type="checkbox" id="roles_view"></td>
                            <td class="text-center"><input type="checkbox" id="roles_create"></td>
                            <td class="text-center"><input type="checkbox" id="roles_edit"></td>
                            <td class="text-center"><input type="checkbox" id="roles_delete"></td>
                        </tr>
                        <tr>
                            <td>Productos</td>
                            <td class="text-center"><input type="checkbox" id="items_view"></td>
                            <td class="text-center"><input type="checkbox" id="items_create"></td>
                            <td class="text-center"><input type="checkbox" id="items_edit"></td>
                            <td class="text-center"><input type="checkbox" id="items_delete"></td>
                        </tr>
                        <tr>
                            <td>Almacenes</td>
                            <td class="text-center"><input type="checkbox" id="warehouses_view" checked="checked"></td>
                            <td class="text-center"><input type="checkbox" id="warehouses_create" checked="checked"></td>
                            <td class="text-center"><input type="checkbox" id="warehouses_edit" checked="checked"></td>
                            <td class="text-center"><input type="checkbox" id="warehouses_delete" checked="checked"></td>
                        </tr>
                        <tr>
                            <td>Clientes</td>
                            <td class="text-center"><input type="checkbox" id="contacts_view" checked="checked"></td>
                            <td class="text-center"><input type="checkbox" id="contacts_create" checked="checked"></td>
                            <td class="text-center"><input type="checkbox" id="contacts_edit" checked="checked"></td>
                            <td class="text-center"><input type="checkbox" id="contacts_delete" checked="checked"></td>
                        </tr>
                        <tr>
                            <td>Proveedores</td>
                            <td class="text-center"><input type="checkbox" id="contacts_view" checked="checked"></td>
                            <td class="text-center"><input type="checkbox" id="contacts_create" checked="checked"></td>
                            <td class="text-center"><input type="checkbox" id="contacts_edit" checked="checked"></td>
                            <td class="text-center"><input type="checkbox" id="contacts_delete" checked="checked"></td>
                        </tr>
                       
                       <tr>
                            <td>Estaciones de Trabajo</td>
                            <td class="text-center"><input type="checkbox" id="courses.view" checked="checked"></td>
                            <td class="text-center"><input type="checkbox" id="courses.create" checked="checked"></td>
                            <td class="text-center"><input type="checkbox" id="courses.edit" checked="checked"></td>
                            <td class="text-center"><input type="checkbox" id="courses.delete" checked="checked"></td>
                        </tr>
                        <tr>
                            <td>Ordenes de Entrada</td>
                            <td class="text-center"><input type="checkbox" id="documents.view" checked="checked"></td>
                            <td class="text-center"><input type="checkbox" id="documents.create" checked="checked"></td>
                            <td class="text-center"><input type="checkbox" id="documents.edit" checked="checked"></td>
                            <td class="text-center"><input type="checkbox" id="documents.delete" checked="checked"></td>
                        </tr>
                        <tr>
                            <td>Ordenes de Salida</td>
                            <td class="text-center"><input type="checkbox" id="documents.view" checked="checked"></td>
                            <td class="text-center"><input type="checkbox" id="documents.create" checked="checked"></td>
                            <td class="text-center"><input type="checkbox" id="documents.edit" checked="checked"></td>
                            <td class="text-center"><input type="checkbox" id="documents.delete" checked="checked"></td>
                        </tr>
                        <tr>
                            <td>Ordenes de Trabajo</td>
                            <td class="text-center"><input type="checkbox" id="documents.view" checked="checked"></td>
                            <td class="text-center"><input type="checkbox" id="documents.create" checked="checked"></td>
                            <td class="text-center"><input type="checkbox" id="documents.edit" checked="checked"></td>
                            <td class="text-center"><input type="checkbox" id="documents.delete" checked="checked"></td>
                        </tr>
                        <tr>
                            <td>Transferencias</td>
                            <td class="text-center"><input type="checkbox" id="documents.view" checked="checked"></td>
                            <td class="text-center"><input type="checkbox" id="documents.create" checked="checked"></td>
                            <td class="text-center"><input type="checkbox" id="documents.edit" checked="checked"></td>
                            <td class="text-center"><input type="checkbox" id="documents.delete" checked="checked"></td>
                        </tr>
                        <tr>
                            <td>Ajustes de Inventario</td>
                            <td class="text-center"><input type="checkbox" id="documents.view" checked="checked"></td>
                            <td class="text-center"><input type="checkbox" id="documents.create" checked="checked"></td>
                            <td class="text-center"><input type="checkbox" id="documents.edit" checked="checked"></td>
                            <td class="text-center"><input type="checkbox" id="documents.delete" checked="checked"></td>
                        </tr>
                    </tbody>
                  </table>
                </div>
                </div>

            <!--<div class="box-footer">
                <button type="button" class="btn btn-primary pull-right" id="editBtn" >Guardar</button>
            </div>    
            </div>-->
            <!-- /.box -->
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
    
	
	<!-- bootstrap colorpicker -->
    <script src="{{ asset('plugins/colorpicker/bootstrap-colorpicker.min.js')}} "></script>
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>

    
    <script>

        table = null;
        var permissions = null;
        function changePermissionValue(id) {
            console.log(id);
            var data = new FormData();
                      
              
        }


        $(document).ready(function() {

            AWApi.get('{{ url('/api/roles' ) }}/{{$id}}?with=all-permissions', 
                function (response) {

                $("#roles_title").html(response.data.role.name + " <small>Listado de permisos</small>");
                for (var i = 0; i < response.data.allpermissions.length; i++) {
                    if(response.data.allpermissions[i].value == 1){
                        document.getElementById(response.data.allpermissions[i].resource+"_"+response.data.allpermissions[i].action).checked = true;
                    }else{
                        document.getElementById(response.data.allpermissions[i].resource+"_"+response.data.allpermissions[i].action).checked = false;
                    }
                }
                permissions = response.data.allpermissions;
        
                for (var i = 0; i < permissions.length; i++) {
                    $('#'+permissions[i].resource+"_"+permissions[i].action).change(function(event)  {
                        for (var i = 0; i < permissions.length; i++) {
                            if(this.id == permissions[i].resource+"_"+permissions[i].action){
                                id = permissions[i].id;
                            }
                        }
                        checked = 0;
                        if(this.checked){
                            checked = 1;
                        }
                        data = new FormData();
                        data.append('value', checked);
                        data.append('permission_id', id);

                        AWApi.put('{{ url('/api/roles') }}/'+{{$id}}+"/permissions", data,function(datas){
                            submit('', datas);
                        });
                    });
                }
                        
            });
            /*
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

                    AWApi.get('{{ url('/api/roles' ) }}/{{$id}}?with=all-permissions', function (response) {
                        $("#roles_title").html(response.data.role.name + " <small>Listado de permisos</small>");
                        callback({
                            recordsTotal: response.data.recordsTotal,
                            recordsFiltered: response.data.recordsFiltered,
                            data: response.data.allpermissions
                        });
                    });
                },
                "paging": false,
                "ordering": false,
                "columns": [
                    { "data": "id", "visible": false,},
                    { "data": "name"},
                    { "data": "id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            if(full.value == "1"){
                                return '<select class="form-control input-sm" @can("roles.edit") onchange=changePermissionValue('+data+') @endcan id="val_'+data+'">'
                                + '<option selected value="1">SI</option> '
                                + '<option value="0">NO</option>'
                                +'</select>';
                            }
                            else{
                                return '<select class="form-control input-sm" @can("roles.edit") onchange=changePermissionValue('+data+') @endcan id="val_'+data+'">'
                                + '<option value="1">SI</option>'
                                + '<option selected value="0">NO</option>'
                                +'</select>';
                            }
                            
                            //return "<div class='btn-group'>@can('roles.view')"+edit+"@endcan @can('roles.delete')"+del+"@endcan </div>";
                        }
                    }
                ]
            });*/

            fieldValues = [];


            AWApi.get('{{ url('/api/roles') }}', function selectRoles(datas) {

                var roles = $('#role_id');

                $.map(datas.data.roles, function (data) {
                    $('<option />', {
                        value: data.id,
                        text: data.name
                    }).appendTo(roles);
                });
            });

            $("#goBack").click(function(){
              window.location.href = "{{ url( '/roles' ) }}";
            });

        
        });

        

        function submit(id,data)
        {
            var count = 0;

            AWValidator.clean('form');
            if(data.data.errors){
                swal("Acceso Denegado", data.data.errors.unauthorized, "error");
            }else{
                for (x in data.data.errors)
                {
                    AWValidator.error(fieldValues[x],data.data.errors[x].join('\n'));
                    count++;
                }

                if (count == 0)
                {
                    swal("Actualizar Permiso", "Información actualizada de forma exitosa", "success");
                    
                }else{
                    swal("Actualizar Permiso", "Información no pudo ser actualizada", "error");
                }
            }
            
        }

    </script>
@endsection