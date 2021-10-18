@extends('layouts.master')

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
    <!-- bootstrap datepicker -->
    <link rel="stylesheet" href="{{ asset('plugins/datepicker/datepicker3.css')}} ">
	<!-- bootstrap colorpicker -->
    <link rel="stylesheet" href="{{ asset('plugins/colorpicker/bootstrap-colorpicker.min.css')}} ">
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Roles
        <small>Listado de roles</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('dashboards')}}"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Roles</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                  
            <div class="box box-primary box-solid flat">
                <div class="box-header">
                    <h3 class="box-title">Listado de Roles</h3>
                    
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>

                </div>  
                
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="col-xs-12">
                        <div class="btn-group">                       
                            <button type="button" class="btn btn-success btn-xs" id="myButton"><i class="fa fa-plus" style="padding-right: 5px;"></i> Agregar Rol</button>   
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <table id="datas" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                              <th>#</th>
                              <th>Nombre</th>
                              <th>F. Creación</th>
                              <th>F. Modificación</th>
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
	<script src="{{ asset('js/awsidebar.js') }}"></script>

	<!-- bootstrap colorpicker -->
    <script src="{{ asset('plugins/colorpicker/bootstrap-colorpicker.min.js')}} "></script>
     <!-- bootstrap datepicker -->
    <script src="{{ asset('plugins/datepicker/bootstrap-datepicker.js') }}"></script>
	<!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
   

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>

    
    <script>

        //variables globales
        table = null;

        fieldValues = [];

        // modals --------
        var rows = [
            [
                {field: 'Nombre', type: 'text', id: 's_name', value: 'Nombre'}
            ]
        ];

        var params = {
            title: 'Creaci&oacute;n de Rol',
            rows: rows
        }

        var modal_role = "modal_role";

        AWModal.create(modal_role, params);

		
        
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

                    AWApi.get('{{ url('/api/roles' ) }}', function (response) {
                        console.log("yava");
                        console.log(response);
                        callback({
                            recordsTotal: response.data.recordsTotal,
                            recordsFiltered: response.data.recordsFiltered,
                            data: response.data.roles
                        });
                    });
                },
                "paging": true,
                "ordering": false,
                "columns": [
                    { "data": "id", "visible": false,},
                    { "data": "name"},
                    { "data": "created_at" },
                    { "data": "updated_at" },
                    { 
                        "data": "id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {
                            var edit = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=edit("+data+");>";
                            edit += "<i class='fa fa-lg fa-eye fa-fw' ></i></button>";

                            var del = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;' onclick=del("+data+");>";
                            del += "<i class='fa fa-lg fa-trash-o fa-fw' ></i></button>";


                            return "<div class='btn-group'>"+edit+" "+del+"</div>";
                        }
                    }
                ]
            });

            // set fields ----------------------

            (function setFieldValues() {
                fieldValues['name'] = 's_name';          
            })();

            

            $('#myButton').click(function () {
                AWValidator.clean(modal_role);
                $(".modal-body input").val("");
				$('#color_s_color').colorpicker('setValue','#003399');
                $('#' +modal_role).modal('show');
            });

            $('#' + modal_role + "_create").click(function(){

                var data = new FormData();
                
                data.append('name', $('#s_name').val());
                AWApi.post('{{ url('/api/roles') }}', data, function(datas){
                    submit(modal_role, datas);
                });
            });
			
			$('#color_s_color').colorpicker();

        });

        // extra functions--------

        function submit(id,data)
        {
            var count = 0;
            AWValidator.clean(id);
            for (x in data.data.errors)
            {
                AWValidator.error(fieldValues[x],data.data.errors[x].join('\n'));
                count++;
            }

            if (count == 0)
            {
                swal("Actualizar Rol", "Información actualizada de forma exitosa", "success");
                $('#' +modal_role).modal('toggle');
                table.ajax.reload();
            }
        }

        function del(id) {

            swal({
                    title: "Eliminar Rol",
                    text: "¿Esta seguro de realizar esta acción?'",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "SI",
                    closeOnConfirm: true,
                    cancelButtonText: "NO"
                },
                function () {
                    AWApi.delete('{{ url('/api/roles' ) }}/'+id,function(data) {
                        table.ajax.reload();
                    });
                });
        }

        function edit(id) {
            window.location.href = "{{ url( '/roles' ) }}/"+id;
        }
    </script>
@endsection