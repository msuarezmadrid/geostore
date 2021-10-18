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
        <i class="fa fa-users" style="padding-right: 5px;"></i> Conciliaciones
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Conciliaciones</li>
      </ol>
    </section>
    
    <section class="content">
        <div class="row">


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
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label for="f_name">Nombre</label>
                                <input type="text" class="form-control" id="f_name" placeholder="Nombre">
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label for="f_rut">Rut</label>
                                <input type="text" class="form-control" id="f_rut" placeholder="Rut">
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
                    <i class="fa fa-users"></i><h3 class="box-title"> Conciliaciones</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="btn-group">
                        
                        <button type="button" class="btn btn-xs btn-success" id="myButton"><i class="fa fa-plus" style="padding-right: 5px;"></i>Crear Conciliación</button>
                    </div>
              
                  <table id="datas" class="display nowrap table table-bordered table-hover" cellspacing="0" width="100%" style="text-align: left;">
                    <thead>
                    <tr>
                      <th>#</th>
                      <th>Nª Conciliación</th>
                      <th>Cliente</th>
                      <th>Creado por</th>
                      <th>Fecha creación</th>
                      <th>Acciones</th>
                    </tr>
                    </thead>
                  </table>
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
    <script src="{{ asset('js/utils.js') }}"></script>

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
                {field: 'Direccion', type: 'text', id: 's_address', value: 'Direccion'}
            ],
            [                
                {field: 'Comuna', type: 'combo', id: 's_comune', value: 'Comuna'}
            ],
            [
                {field: 'Giro', type: 'text', id: 's_industries', value: 'Giro'},
                {field: 'Codigo interno', type: 'text', id: 's_internal_code', value: 'Codigo interno'}                
            ],
            [
                {field: 'Telefono', type: 'text', id: 's_phone', value: 'Telefono'},
                {field: 'Rut', type: 'text', id: 's_rut', value: 'Rut'}
            ],
            [
                {field: 'Descuento', type: 'combo', id: 's_has_discount', value: 'Descuento'},
                {field: 'Valor', type: 'text', id: 's_discount_percent', value: '0'}

            ]
        ];
        var params = {
            title: 'Creaci&oacute;n Cliente',
            rows: rows,
            button: 'Agregar'
        }
        var modal_client = "modal_client";
        AWModal.create(modal_client, params);

        var rows = [
            [
                {field: 'Cliente', type: 'combo', id: 'add_contact_id', value: 'Cliente'}
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

            $( "#s_discount_percent" ).prop( "disabled", true );
            


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
                    var filters = new Object();
                    filters.name = $('#f_name').val();
                    filters.rut = $('#f_rut').val();
                    data.filters = filters;
                    AWApi.get('{{ url('/api/conciliations' ) }}?' + $.param(data), function (response) {
                        callback({
                            recordsTotal: response.data.recordsTotal,
                            recordsFiltered: response.data.recordsFiltered,
                            data: response.data.rows
                        });
                    });
                    },
                
                "paging": true,
                "columns": [
                    { "data": "id", "visible": false,"searchable":false},
                    { "data": "id"},
                    { "data": "name"},
                    { "data": "uname"},
                    { "data": "created_at", render: function(a, b, c, d) {
                        return utcToLocal(a);
                    }},
                    { 
                        "data": "id",
                        className:'text-center',
                        render: function ( data, type, full, meta ) {

                            var edit = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;'>";
                            edit += "<i class='fa fa-lg fa-eye fa-fw' onclick=edit("+data+");></i></button>";

                            var del = "<button class='btn btn-default btn-xs' type='button' style='padding-top: 3px;padding-bottom: 3px;'>";
                            del += "<i class='fa fa-lg fa-trash-o fa-fw' onclick=del("+data+");></i></button>";

                            return "<div class='btn-group'>"+edit+"</div>";
                        }
                    }
                ]
            });



            // set fields ----------------------

            (function setFieldValues() {
                fieldValues['rut'] = 'f_rut';
                fieldValues['name'] = 'f_name';
            })();


            // Buttons triggers ------------------

            $('#filter').click(function(){
                table.ajax.reload();
            });


            $('#clean').click(function(){
                //todo: Crear función dejar limpio todos los campo de un formulario por id
                $('#f_rut').val('');
                $('#f_name').val('');
                table.ajax.reload();
            });

        

            $('#myButton').click(function () {
                window.location.href = "/concilliation/add";
            });

            $('#' + modal_client + "_create").click(function(){

                var data = new FormData();
                
                data.append('name', $('#s_name').val());
                data.append('address', $('#s_address').val());
                data.append('comune_id', $('#s_comune').val());
                data.append('industries', $('#s_industries').val());
                data.append('internal_code', $('#s_internal_code').val());
                data.append('phone', $('#s_phone').val());
                data.append('has_discount', $('#s_has_discount').val());
                data.append('discount_percent', $('#s_discount_percent').val());
                
                if ( validaRut($('#s_rut').val())===true){
                    data.append('rut', $('#s_rut').val());
                }
                else{
                    data.append('rut', '');
                }
                
                AWApi.post('{{ url('/api/clients') }}', data, function(datas){
                    fieldValues = [];
                    fieldValues['name'] = 's_name';
                    fieldValues['address'] = 's_address';
                    fieldValues['industries'] = 's_industries';
                    fieldValues['internal_code'] = 's_internal_code';
                    fieldValues['comune_id'] = 's_comune';
                    fieldValues['rut'] = 's_rut';
                    fieldValues['phone'] = 's_phone';
                    fieldValues['has_discount'] = 's_has_discount';
                    fieldValues['discount_percent'] = 's_discount_percent';
                    submit(modal_client, datas, "Crear Cliente");
                });
            });

            $('#myButton_contacts').click(function () {
                AWValidator.clean(modal_add_contact);
                AWApi.get("{{ url('api/clients')}}"+"?with=contacts", function (datas) {
                    $("#add_contact_id").empty();
                    for (var i = 0; i < datas.data.clients.length; i++) {
                        $('<option />', {value: datas.data.clients[i].id, text:datas.data.clients[i].name }).appendTo($("#add_contact_id"));
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
                data.append('client_id', $("#add_contact_id").val());
                AWApi.post('{{ url('/api/contacts') }}', data, function(datas){
                    fieldValues = [];
                    fieldValues['name'] = 'add_contact_name';
                    fieldValues['email'] = 'add_contact_email';
                    fieldValues['phone'] = 'add_contact_phone';
                    fieldValues['client_id'] = 'add_contact_id';
                    submit(modal_add_contact, datas, "Crear Contacto");
                });
            });

        });


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
                    AWApi.delete('{{ url('/api/conciliations' ) }}/'+id, function(data) {
                        switch(data.code) {
                            case 200:
                                swal('Correcto', "Conciliación eliminada de forma exitosa", "success");
                                table.ajax.reload();
                            break;
                            case 401:
                                swal('Error del sistema' , response.data.msg , 'error');
                            break;
                            default:
                                swal('Error del sistema', 'Error del sistema, contacte al administrador', 'error');
                            break;   
                        }
                    });
                });
        }

        function edit(id) {
            window.location.href = "{{ url( '/concilliation/show' ) }}/"+id;
        }
    </script>
@endsection 

